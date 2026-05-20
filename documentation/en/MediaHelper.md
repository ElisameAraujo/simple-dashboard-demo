# 🎞️ MediaHelper

The **MediaHelper** class provides utility functions for manipulating and displaying media files (images, videos, PDFs, etc.) stored on disks configured in Laravel (`config/filesystems.php`).  
It simplifies common operations such as checking file existence, generating public URLs, obtaining internal paths, downloading files, and identifying MIME types.

---

## 📂 Available Functions

### `mediaExists(?string $disk = 'public', ?string $path = null): bool`

Checks if a media file exists on a disk.

-   `$disk`: Disk configured in `filesystems.php`. Optional, default `public`.
-   `$path`: Relative path of the media within the disk.

Returns `true` if the file exists, `false` otherwise.

```php
MediaHelper::mediaExists('my-disk', 'uploads/avatar.jpg');
// true or false
```

---

### `showMedia(string $path, ?string $disk = 'public', ?string $placeholder = null): ?string`

Returns the **public URL** of the media or a _placeholder_ if the file doesn't exist.

-   `$path`: Relative path of the media within the disk.
-   `$disk`: Disk configured in `filesystems.php`. Optional, default `public`.
-   `$placeholder`: Path to fallback image/file in `public/`. Optional.

```blade
<img src="{{ MediaHelper::showMedia('uploads/avatar.jpg') }}" />

<img src="{{ MediaHelper::showMedia('uploads/avatar.jpg', 'my-disk', 'images/default-avatar.png') }}" />
```

---

### `mediaFullPath(string $path, ?string $disk = 'public'): ?string`

Returns the complete path relative to the project, **without the APP_URL**.

-   `$path`: Relative path of the media.
-   `$disk`: Disk configured in `filesystems.php`. Optional, default `public`.

```php
MediaHelper::mediaFullPath('uploads/file.pdf', 'my-disk');
// "/storage/my-disk/uploads/file.pdf"
```

---

### `downloadMedia(string $path, ?string $customName = null, ?string $disk = 'public')`

Returns a **download response** for the media.

-   `$path`: Relative path of the media.
-   `$customName`: Custom name for the downloaded file. Optional.
-   `$disk`: Disk configured in `filesystems.php`. Optional, default `public`.

If `$customName` is not provided, it automatically uses the file's **basename**.

```php
// download with the file's actual name
return MediaHelper::downloadMedia('reports/relatorio-final.pdf');

// download with a custom name
return MediaHelper::downloadMedia('reports/relatorio-final.pdf', 'Report.pdf');

// download from another disk
return MediaHelper::downloadMedia('reports/relatorio-final.pdf', null, 'my-disk');
```

---

### `mediaMimeType(string $path, ?string $disk = 'public'): string`

Returns the **MIME type** of the media (e.g., `image/jpeg`, `video/mp4`).  
If the file doesn't exist or the MIME type cannot be identified, returns `"mimetype unknown"`.

-   `$path`: Relative path of the media.
-   `$disk`: Disk configured in `filesystems.php`. Optional, default `public`.

```php
MediaHelper::mediaMimeType('uploads/avatar.jpg');
// "image/jpeg"

MediaHelper::mediaMimeType('videos/demo.mp4', 'my-disk');
// "video/mp4"

MediaHelper::mediaMimeType('nonexistent-file.txt', 'my-disk');
// "mimetype unknown"
```

---

## ✅ Important Notes

-   The `$disk` parameter always refers to the disk configured in `config/filesystems.php`.
-   The `$path` parameter must be the relative path within the disk (e.g., `uploads/file.jpg`).
-   The `$placeholder` parameter is optional and only used in `showMedia`.
-   `downloadMedia` aborts with `404` if the file doesn't exist.
-   `mediaMimeType` never returns `null`, always returns a valid string.
