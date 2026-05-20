<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DiskHelper
{
    /**
     * `saveFile`:
     * Save a file to the specified disk and accepts subfolder(s) if user need more organization.
     * @param mixed $file File that will be saved
     * @param string $disk Disk where the file will be saved
     * @param array|string|null $subfolders Subfolders within the disk where the file will be saved (optional)
     */
    public static function saveFile($file, ?string $disk = 'public', array|string|null $subfolders = null)
    {
        $filename   = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension  = $file->getClientOriginalExtension();
        $slug       = Str::slug($filename);
        $finalName  = "{$slug}-" . now()->format('YmdHis') . ".{$extension}";

        $folderPath = self::buildFolderPath($subfolders);

        // retorna apenas o caminho relativo dentro do disco
        return $file->storeAs($folderPath, $finalName, ['disk' => $disk]);
    }


    /**
     * `updateFile`:
     * This function allow to update an existing file by saving a new one and deleting the old one.
     * @param mixed $newFile File that will be saved
     * @param string $oldFile Path to the old file that will be removed
     * @param string $disk Disk where the file will be saved
     * @param array|string|null $subfolders Subfolders where the file can be located (optional)
     * @return string
     */
    public static function updateFile($newFile, string $oldFile, ?string $disk = 'public', array|string|null $subfolders = null): string
    {
        $newPath = self::saveFile($newFile, $disk, $subfolders);

        if ($oldFile && Storage::disk($disk)->exists($oldFile)) {
            Storage::disk($disk)->delete($oldFile);
        }

        return $newPath;
    }


    /**
     * removeFile
     * Removes a file from the specified disk and subfolder(s).
     * @param string $file File path to be removed
     * @param string $disk Disk where the file is stored
     * @param array|string|null $subfolders Subfolders where the file can be located (optional)
     * @return bool
     */
    public static function removeFile(string $file, ?string $disk = 'public', array|string|null $subfolders = null): bool
    {
        $path = self::getFilePath($file, $subfolders, $disk);

        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }

        return false;
    }

    /**
     * `fileUrl`:
     * Returns the public URL of a file stored on a disk.
     * @param string $file Relative path saved in the database (e.g., "my-disk/uploads/file.jpg" or "file.jpg")
     * @param string|null $disk Name of the disk where the file is stored (default: public)
     * @param array|string|null $subfolders Optional subfolders (if necessary)
     * @return string|null Public URL or null if the file does not exist
     */
    public static function fileUrl(string $file, ?string $disk = 'public', array|string|null $subfolders = null): ?string
    {
        $path = self::getFilePath($file, $subfolders, $disk);

        if (!Storage::disk($disk)->exists($path)) {
            return null;
        }

        return Storage::url($path);
    }

    /**
     * `fileSize`:
     * Returns the formatted size of a file stored on a disk.
     * @param string $file File path within the disk
     * @param string|null $disk Name of the disk where the file is stored
     * @param array|string|null $subfolders Optional subfolders within the disk (array or string)
     * @return string|null Formatted size (e.g., "1.2 MB") or null if the file does not exist
     */
    public static function fileSize(string $file, ?string $disk = 'public', array|string|null $subfolders = null): ?string
    {
        $path = self::getFilePath($file, $subfolders, $disk);

        if (!Storage::disk($disk)->exists($path)) {
            return null;
        }

        $bytes = Storage::disk($disk)->size($path);
        return self::formatSize($bytes);
    }

    /*=======================|
    | Private Methods        |
    |=======================*/

    /**
     * `buildFolderPath`:
     * Builds the subfolder path from a string or array
     * @param array|string|null $subfolders
     * @return string
     */
    private static function buildFolderPath(array|string|null $subfolders): string
    {
        if (is_array($subfolders)) {
            return implode('/', array_map(fn($f) => trim($f, '/'), $subfolders));
        }

        if (is_string($subfolders)) {
            return trim($subfolders, '/');
        }

        return '';
    }

    /**
     * `getFilePath`:
     * Returns the full path (subfolders + file)
     * @param string $file File name
     * @param array|string|null $subfolders Optional subfolders
     * @return string Full path
     */
    private static function getFilePath(string $file, array|string|null $subfolders = null, ?string $disk = 'public'): string
    {
        $folderPath = self::buildFolderPath($subfolders);
        $relative   = $folderPath ? $folderPath . '/' . ltrim($file, '/') : $file;

        return $relative;
    }

    /**
     * `formatSize`:
     * Converts a value in bytes to a readable string (e.g., "1.2 MB")
     * @param int $bytes Size in bytes
     * @return string Formatted size
     */
    private static function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
