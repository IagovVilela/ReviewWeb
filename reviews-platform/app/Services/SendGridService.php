<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendGridService
{
    protected $apiKey;
    protected $fromEmail;
    protected $fromName;

    public function __construct()
    {
        $this->apiKey = env('SENDGRID_API_KEY');
        $this->fromEmail = env('MAIL_FROM_ADDRESS', 'noreply@reviewsplatform.com');
        $this->fromName = env('MAIL_FROM_NAME', 'Reviews Platform');
    }

    /**
     * Send email using SendGrid Web API (no SMTP, uses HTTP/HTTPS)
     */
    public function send($to, $subject, $htmlContent, $textContent = null)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.sendgrid.com/v3/mail/send', [
                'personalizations' => [
                    [
                        'to' => [
                            [
                                'email' => $to,
                            ]
                        ],
                        'subject' => $subject,
                    ]
                ],
                'from' => [
                    'email' => $this->fromEmail,
                    'name' => $this->fromName,
                ],
                'content' => [
                    [
                        'type' => 'text/html',
                        'value' => $htmlContent,
                    ]
                ],
            ]);

            if ($response->successful()) {
                Log::info('SendGrid email sent successfully', [
                    'to' => $to,
                    'subject' => $subject,
                    'status' => $response->status()
                ]);
                return true;
            } else {
                Log::error('SendGrid API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'to' => $to
                ]);
                return false;
            }

        } catch (\Exception $e) {
            Log::error('SendGrid service exception', [
                'error' => $e->getMessage(),
                'to' => $to
            ]);
            return false;
        }
    }

    /**
     * Send email from view template
     */
    public function sendFromView($to, $subject, $view, $data = [])
    {
        $htmlContent = view($view, $data)->render();
        return $this->send($to, $subject, $htmlContent);
    }
}







