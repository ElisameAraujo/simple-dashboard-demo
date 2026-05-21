<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MediaHelper
{
    /**
     * `mediaExists`:
     * Checks if a media file exists on a configured disk.
     * @param string|null $disk Disk configured in filesystems.php (default: public)
     * @param string|null $path Relative path of the media within the disk
     * @return bool
     */
    public static function mediaExists(?string $disk = 'public', ?string $path = null): bool
    {
        $disk = $disk ?? 'public';

        return !empty($path) && Storage::disk($disk)->exists($path);
    }

    /**
     * `showMedia`:
     * Returns the public URL of the media or a placeholder if it does not exist.
     * @param string $path Relative path of the media within the disk
     * @param string|null $disk Disk configured in filesystems.php (default: public)
     * @param string|null $placeholder Path to fallback image/file in public/
     * @return string|null
     */
    public static function showMedia(string $path, ?string $disk = 'public', ?string $placeholder = null): ?string
    {
        $disk = $disk ?? 'public';

        if (self::mediaExists($disk, $path)) {
            return Storage::url("{$disk}/{$path}");
        }

        return $placeholder ? asset($placeholder) : null;
    }

    /**
     * `mediaFullPath`:
     * Returns the public media URL path without the configured APP_URL.
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
     * Returns a media download response.
     * @param string $path Relative path to the media
     * @param string|null $customName Custom name for the downloaded file
     * @param string|null $disk Disk configured in filesystems.php (default: public)
     * @return BinaryFileResponse
     */
    public static function downloadMedia(string $path, ?string $customName = null, ?string $disk = 'public'): BinaryFileResponse
    {
        $disk = $disk ?? 'public';

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
     * If the file does not exist, returns the translated file not found message.
     * If the file cannot be identified, returns the translated unknown MIME type message.
     * @param string $path Relative path of the media
     * @param string|null $disk Disk configured in filesystems.php (default: public)
     * @return string
     */
    public static function mediaMimeType(string $path, ?string $disk = 'public'): string
    {
        $disk = $disk ?? 'public';

        if (!self::mediaExists($disk, $path)) {
            return __('error_messages.file_not_found');
        }

        $fullPath = Storage::disk($disk)->path($path);
        $mimeType = mime_content_type($fullPath);

        return $mimeType ?: __('error_messages.mimetype_unknown');
    }
}
