<?php

namespace App\Http\Controllers;

use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    private function cloudinary(): CloudinaryService
    {
        return app(CloudinaryService::class);
    }

    private function deletePhotoStorage(?string $path): void
    {
        if (!$path) {
            return;
        }
        if (CloudinaryService::isCloudinaryUrl($path)) {
            $this->cloudinary()->deleteByUrl($path);
            return;
        }
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
    /**
     * Show the user profile page
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update basic info
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password if provided
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => __('profile.current_password_incorrect')]);
            }

            $user->password = Hash::make($request->new_password);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            $this->deletePhotoStorage($user->photo);

            $cloudinary = $this->cloudinary();
            if ($cloudinary->isConfigured()) {
                $url = $cloudinary->upload($request->file('photo'), 'profile-photos');
                if ($url !== null) {
                    $user->photo = $url;
                } else {
                    $path = $request->file('photo')->store('profile-photos', 'public');
                    $user->photo = $path;
                }
            } else {
                $path = $request->file('photo')->store('profile-photos', 'public');
                $user->photo = $path;
            }
        }

        $user->save();

        return back()->with('success', __('profile.update_success'));
    }

    /**
     * Delete profile photo
     */
    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->photo) {
            $this->deletePhotoStorage($user->photo);
            $user->photo = null;
            $user->save();
        }

        return back()->with('success', __('profile.photo_removed_success'));
    }
}

