<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    protected string $cloudName;
    protected string $apiKey;
    protected string $apiSecret;
    protected string $folder;

    public function __construct()
    {
        $this->cloudName = config('cloudinary.cloud_name', '');
        $this->apiKey = config('cloudinary.api_key', '');
        $this->apiSecret = config('cloudinary.api_secret', '');
        $this->folder = rtrim(config('cloudinary.folder', 'avalieganhe'), '/');
    }

    public function isConfigured(): bool
    {
        return $this->cloudName !== '' && $this->apiKey !== '' && $this->apiSecret !== '';
    }

    /**
     * Upload a file to Cloudinary. Returns secure_url or null on failure.
     */
    public function upload(UploadedFile $file, string $folderPrefix): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $timestamp = (string) time();
        $params = [
            'folder' => $this->folder,
            'timestamp' => $timestamp,
        ];
        ksort($params);
        $paramsStr = implode('&', array_map(fn ($k, $v) => $k . '=' . $v, array_keys($params), $params));
        $signature = sha1($paramsStr . $this->apiSecret);

        try {
            $response = Http::attach(
                'file',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload", [
                'api_key' => $this->apiKey,
                'timestamp' => $timestamp,
                'signature' => $signature,
                'folder' => $this->folder,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['secure_url'] ?? null;
            }
            \Log::warning('Cloudinary upload failed', ['response' => $response->body()]);
            return null;
        } catch (\Throwable $e) {
            \Log::error('Cloudinary upload error', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Upload raw image content (e.g. from base64). Returns secure_url or null.
     */
    public function uploadFromString(string $imageData, string $folderPrefix, string $extension = 'png'): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $timestamp = (string) time();
        $params = [
            'folder' => $this->folder,
            'timestamp' => $timestamp,
        ];
        ksort($params);
        $paramsStr = implode('&', array_map(fn ($k, $v) => $k . '=' . $v, array_keys($params), $params));
        $signature = sha1($paramsStr . $this->apiSecret);

        try {
            $response = Http::asMultipart()
                ->attach('file', $imageData, 'image.' . $extension)
                ->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload", [
                    'api_key' => $this->apiKey,
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                    'folder' => $this->folder,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['secure_url'] ?? null;
            }
            \Log::warning('Cloudinary upload from string failed', ['response' => $response->body()]);
            return null;
        } catch (\Throwable $e) {
            \Log::error('Cloudinary upload from string error', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Delete an image by its Cloudinary URL. Returns true if deleted or not found.
     */
    public function deleteByUrl(string $url): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        $publicId = $this->extractPublicIdFromUrl($url);
        if ($publicId === null) {
            return false;
        }
        return $this->deleteByPublicId($publicId);
    }

    /**
     * Extract public_id from Cloudinary URL.
     * Format: https://res.cloudinary.com/cloud_name/image/upload/v123/folder/file.png
     */
    public function extractPublicIdFromUrl(string $url): ?string
    {
        if (!str_contains($url, 'cloudinary.com')) {
            return null;
        }
        if (!preg_match('#/upload/(?:v\d+/)?(.+?)(?:\.[a-z]+)?$#', $url, $m)) {
            return null;
        }
        return $m[1];
    }

    /**
     * Delete an image by public_id.
     */
    public function deleteByPublicId(string $publicId): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        $timestamp = (string) time();
        $params = [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
        ];
        ksort($params);
        $paramsStr = implode('&', array_map(fn ($k, $v) => $k . '=' . $v, array_keys($params), $params));
        $signature = sha1($paramsStr . $this->apiSecret);

        try {
            $response = Http::asForm()->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/destroy", [
                'api_key' => $this->apiKey,
                'timestamp' => $timestamp,
                'signature' => $signature,
                'public_id' => $publicId,
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            \Log::error('Cloudinary delete error', ['message' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Returns true if the given path/url is a Cloudinary URL.
     */
    public static function isCloudinaryUrl(?string $path): bool
    {
        return $path !== null && str_starts_with($path, 'http') && str_contains($path, 'cloudinary.com');
    }
}
