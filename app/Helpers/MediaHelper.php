<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaHelper
{
    /**
     * `mediaExists`:
     * Checks if a media exists on a disk
     * @param string|null $disk Disk configured in filesystems.php (default: public)
     * @param string|null $path Relative path of the media within the disk
     * @return bool
     */
    public static function mediaExists(?string $disk = 'public', ?string $path = null): bool
    {
        return !empty($path) && Storage::disk($disk)->exists($path);
    }

    /**
     * `showMedia`:
     * Returns the public URL of the media or a placeholder if it doesn't exist
     * @param string $path Relative path of the media within the disk
     * @param string|null $disk Disk configured in filesystems.php (default: public)
     * @param string|null $placeholder Path to fallback image/file in public/
     * @return string|null
     */
    public static function showMedia(string $path, ?string $disk = 'public', ?string $placeholder = null): ?string
    {
        if (self::mediaExists($disk, $path)) {
            return Storage::url("{$disk}/{$path}");
        }

        return $placeholder ? asset($placeholder) : null;
    }

    /**
     * `mediaFullPath`:
     * Returns the full path relative to the project (without `APP_URL`).
     * @param string $path Relative path to the media
     * @param string|null $disk Disk configured in filesystems.php (default: public)
     * @return string|null
     */
    public static function mediaFullPath(string $path, ?string $disk = 'public'): ?string
    {
        $url = self::showMedia($path, $disk);
        return $url ? Str::replace(config('app.url'), '', $url) : null;
    }

    /**
     * `downloadMedia`
     * Returns a media download response
     * @param string $path Relative path to the media
     * @param string|null $customName Custom name for the downloaded file
     * @param string|null $disk Disk configured in filesystems.php (default: public)
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response|null
     */
    public static function downloadMedia(string $path, ?string $customName = null, ?string $disk = 'public')
    {
        if (!self::mediaExists($disk, $path)) {
            return abort(404, __('error_messages.file_not_found'));
        }

        $fullPath   = Storage::disk($disk)->path($path);
        $customName = $customName ?? basename($path);

        return response()->download($fullPath, $customName);
    }

    /**
     * `mediaMimeType`:
     * Returns the MIME type of the media (e.g., `image/jpeg`, `video/mp4`).
     * If the file does not exist or cannot be identified, returns "mimetype unknown".
     * @param string $path Relative path of the media
     * @param string|null $disk Disk configured in filesystems.php (default: public)
     * @return string
     */
    public static function mediaMimeType(string $path, ?string $disk = 'public'): string
    {
        if (!self::mediaExists($disk, $path)) {
            return __('error_messages.file_not_found');
        }

        $fullPath = Storage::disk($disk)->path($path);
        return mime_content_type($fullPath) ?? __('error_messages.mimetype_unknown');
    }
}
