<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use RuntimeException;

class RouteHelper
{
    /**
     * Base path for all route imports.
     */
    protected const ROUTES_BASE = 'routes';

    /**
     * Build the full path to a route file or folder.
     */
    protected static function buildPath(string ...$segments): string
    {
        $clean = array_map(fn($s) => trim($s, '/\\'), $segments);
        return base_path(implode(DIRECTORY_SEPARATOR, $clean));
    }

    /**
     * Normalize subfolders into an array.
     */
    protected static function normalizeFolders(string|array|null $folders): array
    {
        if ($folders === null) {
            return [];
        }

        $folders = is_array($folders) ? $folders : [$folders];

        return collect($folders)
            ->flatMap(fn(string $folder) => preg_split('/[\/\\\\]+/', $folder, -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn(string $folder) => self::normalizePathSegment($folder, 'Route folder'))
            ->values()
            ->all();
    }

    /**
     * Normalize the list of excluded files.
     */
    protected static function normalizeExcept(string|array|null $except): array
    {
        if ($except === null) {
            return [];
        }

        $except = is_array($except) ? $except : [$except];

        return array_map(
            fn(string $file) => self::normalizeRouteFileName($file),
            $except
        );
    }

    /**
     * Normalize a route filename and ensure it points to a PHP route file.
     */
    protected static function normalizeRouteFileName(string $filename): string
    {
        $filename = trim($filename);

        if ($filename === '') {
            throw new InvalidArgumentException('Route filename cannot be empty.');
        }

        if (str_contains($filename, '/') || str_contains($filename, '\\')) {
            throw new InvalidArgumentException('Route filename must not contain folders. Use the folders parameter instead.');
        }

        if (str_contains($filename, '..')) {
            throw new InvalidArgumentException('Route filename cannot contain path traversal segments.');
        }

        $nameWithoutPhp = preg_replace('/\.php$/i', '', $filename);

        if ($nameWithoutPhp === '' || str_contains($nameWithoutPhp, '.')) {
            throw new InvalidArgumentException('Route filename must be provided without extension, or with a single .php extension.');
        }

        return "{$nameWithoutPhp}.php";
    }

    /**
     * Normalize a route path segment and prevent path traversal.
     */
    protected static function normalizePathSegment(string $segment, string $label): string
    {
        $segment = trim($segment, '/\\');

        if ($segment === '') {
            throw new InvalidArgumentException("{$label} cannot be empty.");
        }

        if ($segment === '.' || $segment === '..' || str_contains($segment, '..')) {
            throw new InvalidArgumentException("{$label} cannot contain path traversal segments.");
        }

        if (str_contains($segment, '/') || str_contains($segment, '\\')) {
            throw new InvalidArgumentException("{$label} must be a single path segment.");
        }

        return $segment;
    }

    /**
     * Ensure a route path is still contained inside the routes directory.
     */
    protected static function ensureInsideRoutes(string $path): void
    {
        $routesPath = realpath(base_path(self::ROUTES_BASE));

        if ($routesPath === false) {
            throw new RuntimeException('Routes base folder not found.');
        }

        $resolvedPath = file_exists($path) ? realpath($path) : $path;

        if ($resolvedPath === false) {
            throw new RuntimeException("Unable to resolve route path: {$path}");
        }

        $routesPath = rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $routesPath), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $resolvedPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $resolvedPath);

        if (!str_starts_with($resolvedPath, $routesPath)) {
            throw new InvalidArgumentException('Route path must stay inside the routes directory.');
        }
    }

    /**
     * Validate that a folder exists.
     */
    protected static function ensureFolderExists(string $path): void
    {
        if (!is_dir($path)) {
            throw new RuntimeException("Route folder not found: {$path}");
        }
    }

    /**
     * Import a single route file.
     */
    protected static function importFile(string $path): mixed
    {
        if (!file_exists($path)) {
            throw new RuntimeException("Route file not found: {$path}");
        }

        return require $path;
    }

    /*=============================================================
     * PUBLIC API
     ============================================================*/

    /**
     * `importRouteFile`:
     * Imports a route file located in `routes/` or a subfolder within it.
     * @param string $filename Name of the route file (without .php extension, or with a single .php extension)
     * @param string|array|null $folders Name of the folder or folders within `routes/` (optional)
     * @return mixed Result of requiring the route file
     */
    public static function importRouteFile(string $filename, string|array|null $folders = null): mixed
    {
        $folders = self::normalizeFolders($folders);
        $filename = self::normalizeRouteFileName($filename);

        $segments = array_merge(
            [self::ROUTES_BASE],
            $folders,
            [$filename]
        );

        $path = self::buildPath(...$segments);

        self::ensureInsideRoutes($path);

        return self::importFile($path);
    }

    /**
     * `importRoutesFromFolder`:
     * Imports all route files from a specified folder within `routes/`, with optional subfolders and exclusions.
     * @param string $rootFolder Root folder within `routes/`
     * @param string|array|null $subfolders Subfolder(s) within the root folder (optional)
     * @param string|array|null $except File name(s) to be excluded (without .php extension) (optional)
     * @return void
     * @throws RuntimeException If the specified folder does not exist
     */
    public static function importRoutesFromFolder(string $rootFolder, string|array|null $subfolders = null, string|array|null $except = null): void
    {
        $rootFolder = self::normalizePathSegment($rootFolder, 'Route root folder');
        $subfolders = self::normalizeFolders($subfolders);
        $except = self::normalizeExcept($except);

        $basePath = self::buildPath(
            self::ROUTES_BASE,
            $rootFolder,
            ...$subfolders
        );

        self::ensureInsideRoutes($basePath);
        self::ensureFolderExists($basePath);

        foreach (scandir($basePath) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $fullPath = $basePath . DIRECTORY_SEPARATOR . $file;

            if (is_file($fullPath) && str_ends_with($file, '.php')) {

                if (in_array($file, $except, true)) {
                    continue;
                }

                require $fullPath;
            }
        }
    }

    /**
     * `listAllRoutes`:
     * Returns a list of all routes registered in the application, including URI, name, method, and action.
     * @return array List of routes with basic information.
     */
    public static function listAllRoutes(): array
    {
        return collect(Route::getRoutes())
            ->map(function ($route) {
                return [
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                    'method' => implode('|', $route->methods()),
                    'action' => $route->getActionName(),
                ];
            })
            ->toArray();
    }
}
