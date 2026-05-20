<?php

namespace App\Helpers;

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
        if (!$folders) {
            return [];
        }

        return is_array($folders) ? $folders : [$folders];
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
            fn($f) => str_ends_with($f, '.php') ? $f : "{$f}.php",
            $except
        );
    }

    /**
     * Validate that a folder exists.
     */
    protected static function ensureFolderExists(string $path): void
    {
        if (!is_dir($path)) {
            throw new \Exception("Route folder not found: {$path}");
        }
    }

    /**
     * Import a single route file.
     */
    protected static function importFile(string $path)
    {
        if (!file_exists($path)) {
            throw new \Exception("Route file not found: {$path}");
        }

        return require $path;
    }

    /*=============================================================
     * PUBLIC API
     ============================================================*/

    /**
     * `importRouteFile`:
     * Imports a route file located in `routes/` or a subfolder within it.
     * @param string $filename Name of the route file (without .php extension)
     * @param string|array|null $folder Name of the folder within `routes/` (optional)
     * @return mixed Result of requiring the route file
     */
    public static function importRouteFile(string $filename, string|array|null $folders = null)
    {
        $folders = self::normalizeFolders($folders);

        $segments = array_merge(
            [self::ROUTES_BASE],
            $folders,
            ["{$filename}.php"]
        );

        $path = self::buildPath(...$segments);

        return self::importFile($path);
    }

    /**
     * `importRoutesFromFolder`:
     * Imports all route files from a specified folder within `routes/`, with optional subfolders and exclusions.
     * @param string $rootFolder Root folder within `routes/`
     * @param string|array|null $subfolders Subfolder(s) within the root folder (optional)
     * @param string|array|null $except File name(s) to be excluded (without .php extension) (optional)
     * @return void
     * @throws \Exception If the specified folder does not exist
     */
    public static function importRoutesFromFolder(string $rootFolder, string|array|null $subfolders = null, string|array|null $except = null): void
    {
        $subfolders = self::normalizeFolders($subfolders);
        $except = self::normalizeExcept($except);

        // Mount the final path
        $basePath = self::buildPath(
            self::ROUTES_BASE,
            $rootFolder,
            ...$subfolders
        );

        self::ensureFolderExists($basePath);

        foreach (scandir($basePath) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $fullPath = $basePath . DIRECTORY_SEPARATOR . $file;

            if (is_file($fullPath) && str_ends_with($file, '.php')) {

                if (in_array($file, $except)) {
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
        return collect(\Illuminate\Support\Facades\Route::getRoutes())
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
