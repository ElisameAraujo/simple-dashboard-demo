<?php

namespace App\Services\Media;

use App\Helpers\DiskHelper;
use App\Helpers\MediaHelper;
use DOMDocument;
use DOMElement;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class RichTextMediaManager
{
    public function __construct(
        private readonly string $temporaryDirectory = 'temp'
    ) {}

    public function uploadTemporaryImage(UploadedFile $file, string $disk, string $temporaryKey): array
    {
        return $this->storeImage($file, $disk, [$this->temporaryDirectory, $temporaryKey]);
    }

    public function uploadOwnerImage(UploadedFile $file, string $disk, string|int $ownerKey): array
    {
        return $this->storeImage($file, $disk, (string) $ownerKey);
    }

    public function commitTemporaryImages(string $disk, string $temporaryKey, string|int $ownerKey, string $html): string
    {
        $temporaryKey = $this->cleanKey($temporaryKey);
        $ownerKey = $this->cleanKey((string) $ownerKey);
        $temporaryFolder = $this->temporaryFolder($temporaryKey);

        if (! Storage::disk($disk)->directoryExists($temporaryFolder)) {
            return $html;
        }

        $referencedTemporaryImages = $this->extractDiskImages($html, $disk)
            ->filter(fn (array $image): bool => str_starts_with($image['path'], "{$temporaryFolder}/"))
            ->values();

        foreach (Storage::disk($disk)->files($temporaryFolder) as $temporaryPath) {
            $ownerPath = "{$ownerKey}/".basename($temporaryPath);

            if (Storage::disk($disk)->exists($temporaryPath)) {
                Storage::disk($disk)->move($temporaryPath, $ownerPath);
            }
        }

        foreach ($referencedTemporaryImages as $image) {
            $temporaryPath = $image['path'];
            $ownerPath = "{$ownerKey}/".basename($temporaryPath);

            if (Storage::disk($disk)->exists($ownerPath)) {
                $html = $this->replaceImageSource($html, $image['src'], MediaHelper::showMedia($ownerPath, $disk));
            }
        }

        $this->deleteDirectory($disk, $temporaryFolder);
        $this->syncOwnerImages($disk, $ownerKey, $html);

        return $html;
    }

    public function syncOwnerImages(string $disk, string|int $ownerKey, string $html): void
    {
        $ownerKey = $this->cleanKey((string) $ownerKey);

        if (! Storage::disk($disk)->directoryExists($ownerKey)) {
            return;
        }

        $referencedImages = $this->extractDiskPaths($html, $disk)
            ->filter(fn (string $path): bool => str_starts_with($path, "{$ownerKey}/"))
            ->values()
            ->all();

        foreach (Storage::disk($disk)->files($ownerKey) as $file) {
            if (! in_array($file, $referencedImages, true)) {
                DiskHelper::removeFile($file, $disk);
            }
        }

        $this->deleteDirectoryIfEmpty($disk, $ownerKey);
    }

    public function deleteOwnerDirectory(string $disk, string|int $ownerKey): void
    {
        $this->deleteDirectory($disk, $this->cleanKey((string) $ownerKey));
    }

    public function deleteTemporaryDirectory(string $disk, string $temporaryKey): void
    {
        $this->deleteDirectory($disk, $this->temporaryFolder($temporaryKey));
    }

    public function pruneTemporaryDirectories(string $disk, int $olderThanHours = 24): int
    {
        if (! Storage::disk($disk)->directoryExists($this->temporaryDirectory)) {
            return 0;
        }

        $deleted = 0;
        $threshold = now()->subHours($olderThanHours);

        foreach (Storage::disk($disk)->directories($this->temporaryDirectory) as $directory) {
            $files = Storage::disk($disk)->allFiles($directory);

            if ($files === []) {
                $this->deleteDirectory($disk, $directory);
                $deleted++;

                continue;
            }

            $hasRecentFiles = collect($files)->contains(function (string $file) use ($disk, $threshold): bool {
                return Carbon::createFromTimestamp(Storage::disk($disk)->lastModified($file))->greaterThan($threshold);
            });

            if (! $hasRecentFiles) {
                $this->deleteDirectory($disk, $directory);
                $deleted++;
            }
        }

        return $deleted;
    }

    private function storeImage(UploadedFile $file, string $disk, array|string $folder): array
    {
        $path = DiskHelper::saveFile($file, $disk, $this->cleanFolder($folder));
        $url = MediaHelper::showMedia($path, $disk);

        return [
            'path' => $path,
            'url' => $url,
            'location' => $url,
        ];
    }

    private function extractDiskPaths(string $html, string $disk)
    {
        return $this->extractDiskImages($html, $disk)
            ->pluck('path')
            ->unique()
            ->values();
    }

    private function extractDiskImages(string $html, string $disk)
    {
        if (blank($html)) {
            return collect();
        }

        $document = new DOMDocument();

        libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="utf-8" ?>'.$html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        return collect($document->getElementsByTagName('img'))
            ->map(fn (DOMElement $image): string => $image->getAttribute('src'))
            ->filter()
            ->map(fn (string $src): array => [
                'src' => $src,
                'path' => $this->diskPathFromUrl($src, $disk),
            ])
            ->filter(fn (array $image): bool => filled($image['path']))
            ->values();
    }

    private function diskPathFromUrl(string $url, string $disk): ?string
    {
        $path = parse_url($url, PHP_URL_PATH) ?: $url;
        $path = ltrim(urldecode($path), '/');

        $publicPrefix = ltrim(parse_url(Storage::url("{$disk}/"), PHP_URL_PATH) ?: "storage/{$disk}/", '/');
        $publicPrefixPosition = strpos($path, $publicPrefix);

        if ($publicPrefixPosition !== false) {
            return ltrim(substr($path, $publicPrefixPosition + strlen($publicPrefix)), '/');
        }

        $diskPrefix = "{$disk}/";
        $diskPrefixPosition = strpos($path, $diskPrefix);

        if ($diskPrefixPosition !== false) {
            return ltrim(substr($path, $diskPrefixPosition + strlen($diskPrefix)), '/');
        }

        return null;
    }

    private function replaceImageSource(string $html, string $currentSource, ?string $newSource): string
    {
        if (! $newSource) {
            return $html;
        }

        return str_replace($currentSource, $newSource, $html);
    }

    private function deleteDirectoryIfEmpty(string $disk, string $directory): void
    {
        if (Storage::disk($disk)->allFiles($directory) === []) {
            $this->deleteDirectory($disk, $directory);
        }
    }

    private function deleteDirectory(string $disk, string $directory): void
    {
        if (Storage::disk($disk)->directoryExists($directory)) {
            Storage::disk($disk)->deleteDirectory($directory);
        }
    }

    private function temporaryFolder(string $temporaryKey): string
    {
        return $this->temporaryDirectory.'/'.$this->cleanKey($temporaryKey);
    }

    private function cleanFolder(array|string $folder): array|string
    {
        if (is_array($folder)) {
            return array_map(fn (string $item): string => $this->cleanKey($item), $folder);
        }

        return $this->cleanKey($folder);
    }

    private function cleanKey(string $key): string
    {
        $key = preg_replace('/[^A-Za-z0-9_-]/', '', $key) ?? '';

        if ($key === '') {
            throw new InvalidArgumentException('A media folder key cannot be empty.');
        }

        return $key;
    }
}
