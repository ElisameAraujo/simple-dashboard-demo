<?php

namespace App\Support;

use Symfony\Component\Yaml\Yaml;

class ModuleDocumentationRepository
{
    public static function for(string $slug): array
    {
        $locale = app()->getLocale();
        $path = resource_path("docs/modules/{$locale}/{$slug}.yaml");

        if (! file_exists($path) && $locale !== config('app.fallback_locale')) {
            $path = resource_path('docs/modules/' . config('app.fallback_locale', 'en') . "/{$slug}.yaml");
        }

        if (! file_exists($path)) {
            return [];
        }

        return Yaml::parseFile($path) ?? [];
    }
}
