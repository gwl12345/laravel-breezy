<?php

namespace App\Models\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasProfilePhoto
{
    public function updateProfilePhoto(UploadedFile $photo, string $path = 'profile-photos'): void
    {
        tap($this->profile_photo_path, function ($previous) use ($photo, $path) {
            $storedPath = $photo->storePublicly($path, ['disk' => $this->profilePhotoDisk()]);

            $this->forceFill([
                'profile_photo_path' => $storedPath,
            ])->save();

            if ($previous) {
                Storage::disk($this->profilePhotoDisk())->delete($previous);
            }
        });
    }

    public function deleteProfilePhoto(): void
    {
        if (! $this->profile_photo_path) {
            return;
        }

        Storage::disk($this->profilePhotoDisk())->delete($this->profile_photo_path);

        $this->forceFill([
            'profile_photo_path' => null,
        ])->save();
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->profile_photo_path
            ? Storage::disk($this->profilePhotoDisk())->url($this->profile_photo_path)
            : $this->defaultProfilePhotoUrl();
    }

    protected function profilePhotoDisk(): string
    {
        return config('filesystems.default_profile_photo_disk', 'public');
    }

    protected function defaultProfilePhotoUrl(): string
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?d=mp&s=200';
    }
}
