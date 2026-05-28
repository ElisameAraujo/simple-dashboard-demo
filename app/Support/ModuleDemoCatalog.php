<?php

namespace App\Support;

class ModuleDemoCatalog
{
    private const MODULES = [
        'image-preview' => [
            'component' => 'global.image-preview',
            'icon' => 'fa-regular fa-images',
            'status' => 'ready',
        ],
        'visits' => [
            'component' => null,
            'icon' => 'fa-solid fa-chart-line',
            'status' => 'ready',
        ],
        'notifications-ui' => [
            'component' => 'global.notifications-ui',
            'icon' => 'fa-regular fa-bell',
            'status' => 'ready',
        ],
        'maintenance-mode' => [
            'component' => null,
            'icon' => 'fa-solid fa-screwdriver-wrench',
            'status' => 'ready',
        ],
        'search-engine' => [
            'component' => null,
            'icon' => 'fa-solid fa-magnifying-glass',
            'status' => 'ready',
        ],
    ];

    public static function all(): array
    {
        return collect(self::MODULES)
            ->map(fn (array $definition, string $slug) => self::build($slug, $definition))
            ->values()
            ->all();
    }

    public static function find(string $slug): ?array
    {
        if (! array_key_exists($slug, self::MODULES)) {
            return null;
        }

        return self::build($slug, self::MODULES[$slug]);
    }

    private static function build(string $slug, array $definition): array
    {
        $documentation = ModuleDocumentationRepository::for($slug);

        return [
            'slug' => $slug,
            'component' => $definition['component'],
            'icon' => $definition['icon'],
            'status' => $definition['status'],
            'status_label' => trans("pages/modules.status.{$definition['status']}"),
            'name' => $documentation['name'] ?? str($slug)->headline()->toString(),
            'description' => $documentation['description'] ?? '',
            'summary' => $documentation['summary'] ?? [],
            'variations' => $documentation['variations'] ?? [],
            'configuration' => $documentation['configuration'] ?? [],
            'implementation' => $documentation['implementation'] ?? [],
            'methods' => $documentation['methods'] ?? [],
            'improvements' => $documentation['improvements'] ?? [],
            'notes' => $documentation['notes'] ?? [],
            'sections' => $documentation['sections'] ?? [],
            'url' => route('modules.show', $slug),
        ];
    }
}
