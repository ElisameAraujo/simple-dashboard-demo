# 🖴 DiskHelper

## Overview

The **DiskHelper** class provides utility functions to manipulate files on disks configured in Laravel (`config/filesystems.php`).  
It simplifies common operations such as saving, updating, removing, getting size, downloading, and generating public URLs for files.

---

## 📂 Available Functions

### `saveFile($file, ?string $disk = 'public', array|string|null $subfolders = null): string`

Saves a file within the Laravel disk structure.

-   `$file`: File to be saved (instance of `UploadedFile`).
-   `$disk`: Disk where the file will be saved. Optional, default `public`.
-   `$subfolders`: Subfolder or array of subfolders within the disk. Optional.

The filename is generated automatically with a unique identifier.  
Returns the **relative path** of the file within the disk.

```php
DiskHelper::saveFile($file, 'my-disk');
// "my-disk/my-saved-file-20251218170832.jpg"

DiskHelper::saveFile($file, 'my-disk', 'uploads');
// "my-disk/uploads/my-saved-file-20251218170832.jpg"

DiskHelper::saveFile($file, 'my-disk', ['uploads','reports','2015','dec','21']);
// "my-disk/uploads/reports/2015/dec/21/my-saved-file-20251218170832.jpg"
```

---

### `updateFile($file, string $oldFile, ?string $disk = 'public', array|string|null $subfolders = null): string`

Replaces an existing file with a new one.

-   `$file`: New file.
-   `$oldFile`: Path of the old file to be removed.
-   `$disk`: Disk where the file is stored. Optional, default `public`.
-   `$subfolders`: Subfolder(s) where the file is stored. Optional.

Returns the path of the new file.

```php
DiskHelper::updateFile($file, 'old.jpg');
// "new-file-20251218173922.jpg"

DiskHelper::updateFile($file, 'uploads/old.jpg', 'my-disk');
// "uploads/new-file-20251218173922.jpg"
```

---

### `removeFile(string $file, ?string $disk = 'public', array|string|null $subfolders = null): bool`

Removes a file saved on the disk.

-   `$file`: File path.
-   `$disk`: Disk where the file is stored. Optional, default `public`.
-   `$subfolders`: Subfolder(s) where the file is stored. Optional.

Returns `true` if the file was removed, `false` otherwise.

```php
DiskHelper::removeFile('file.jpg');
// true

DiskHelper::removeFile('uploads/file.jpg', 'my-disk');
// true
```

---

### `fileSize(string $file, ?string $disk = 'public', array|string|null $subfolders = null): ?string`

Returns the formatted size of a file saved on the disk.

-   `$file`: File path.
-   `$disk`: Disk where the file is stored. Optional, default `public`.
-   `$subfolders`: Subfolder(s) where the file is stored. Optional.

Returns a formatted string (e.g., `"256 KB"`) or `null` if the file does not exist.

```php
DiskHelper::fileSize('image.jpg');
// "256 KB"

DiskHelper::fileSize('image.jpg', 'my-disk', ['archives','2025','dec','21']);
// "256 KB"
```

---

### `fileUrl(string $file, ?string $disk = 'public', array|string|null $subfolders = null): ?string`

Returns the public URL of a file stored on a disk.

-   `$file`: File path.
-   `$disk`: Disk where the file is stored. Optional, default `public`.
-   `$subfolders`: Subfolder(s) where the file is stored. Optional.

Returns the public URL or `null` if the file does not exist.

```php
DiskHelper::fileUrl('image.jpg');
// "https://mysite.com/storage/image.jpg"

DiskHelper::fileUrl('uploads/image.jpg', 'my-disk');
// "https://mysite.com/storage/my-disk/uploads/image.jpg"
```

---

## ✅ Important Notes

-   The `$disk` parameter always refers to the disk configured in `config/filesystems.php`.
-   The `$subfolders` parameter can be a string (`'uploads'`) or an array (`['uploads','2025','dec']`).
-   The return value of `saveFile` is always the relative path within the disk, without the `public/` prefix.
