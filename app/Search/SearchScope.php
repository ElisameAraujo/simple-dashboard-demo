<?php

namespace App\Search;

use App\Search\Exceptions\InvalidSearchConfigurationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SearchScope
{
    public function __construct(
        private readonly string $scope,
        private readonly ?array $config,
    ) {}

    public function search(string $term, ?int $limit = null, ?string $group = null): Collection
    {
        $this->validateOrFail($group);

        $term = trim($term);

        if ($term === '') {
            return $this->suggestions($limit, $group);
        }

        if (Str::length($term) < $this->minChars()) {
            return collect();
        }

        return $this->staticResults($term, $group)
            ->filter(fn(SearchResult $result): bool => $result->score > 0)
            ->sortByDesc('score')
            ->take($limit ?? $this->limit())
            ->values();
    }

    public function preview(string $term = '', ?int $limit = null, ?string $group = null): Collection
    {
        return trim($term) === ''
            ? $this->suggestions($limit, $group)
            : $this->search($term, $limit, $group);
    }

    public function suggestions(?int $limit = null, ?string $group = null): Collection
    {
        $this->validateOrFail($group);

        return $this->staticResults('', $group)
            ->sortByDesc('score')
            ->take($limit ?? $this->limit())
            ->values();
    }

    public function validateOrFail(?string $activeGroup = null): void
    {
        if ($this->config === null) {
            throw InvalidSearchConfigurationException::missingScope($this->scope);
        }

        foreach ($this->groupDefinitions() as $key => $definition) {
            $fullKey = $this->groupKey($key);

            if (! isset($definition['label']) && ! isset($definition['label_key'])) {
                throw InvalidSearchConfigurationException::missingGroupLabel($fullKey);
            }

            if (isset($definition['order']) && ! is_numeric($definition['order'])) {
                throw InvalidSearchConfigurationException::invalidGroupOrder($fullKey);
            }
        }

        if ($activeGroup !== null && ! isset($this->groupDefinitions()[$activeGroup])) {
            throw InvalidSearchConfigurationException::invalidGroup($this->scope, $activeGroup);
        }

        foreach ($this->staticDefinitions() as $key => $definition) {
            $fullKey = $this->staticKey($key);

            if (! isset($definition['title']) && ! isset($definition['title_key'])) {
                throw InvalidSearchConfigurationException::missingStaticTitle($fullKey);
            }

            if (! isset($definition['group'])) {
                throw InvalidSearchConfigurationException::missingStaticGroup($fullKey);
            }

            if (! isset($this->groupDefinitions()[$definition['group']])) {
                throw InvalidSearchConfigurationException::invalidGroup($fullKey, $definition['group']);
            }

            if (! isset($definition['route']) && ! isset($definition['url'])) {
                throw InvalidSearchConfigurationException::missingStaticDestination($fullKey);
            }

            if (isset($definition['route']) && ! Route::has($definition['route'])) {
                throw InvalidSearchConfigurationException::invalidRoute($fullKey, $definition['route']);
            }

            if (isset($definition['keywords']) && ! is_array($definition['keywords'])) {
                throw InvalidSearchConfigurationException::invalidKeywords($fullKey, 'keywords');
            }

            if (isset($definition['keywords_key']) && ! is_array(trans($definition['keywords_key']))) {
                throw InvalidSearchConfigurationException::invalidKeywords($fullKey, 'keywords_key');
            }

            if (isset($definition['weight']) && (! is_numeric($definition['weight']) || (int) $definition['weight'] <= 0)) {
                throw InvalidSearchConfigurationException::invalidWeight($fullKey);
            }
        }
    }

    private function staticResults(string $term, ?string $group = null): Collection
    {
        return collect($this->staticDefinitions())
            ->when($group !== null, fn(Collection $definitions): Collection => $definitions
                ->filter(fn(array $definition): bool => ($definition['group'] ?? null) === $group))
            ->map(fn(array $definition, string $key): SearchResult => $this->resultFromStatic($key, $definition, $term))
            ->values();
    }

    private function resultFromStatic(string $key, array $definition, string $term): SearchResult
    {
        $title = $this->text($definition, 'title');
        $summary = $this->text($definition, 'summary');
        $group = $definition['group'];
        $groupDefinition = $this->groupDefinitions()[$group];
        $groupLabel = $this->groupLabel($groupDefinition);
        $badge = $this->text($definition, 'badge');
        $keywords = $this->keywords($definition);
        $route = $definition['route'] ?? null;

        return new SearchResult(
            key: $this->staticKey($key),
            scope: $this->scope,
            source: 'statics',
            type: $definition['type'] ?? 'static',
            title: $title,
            summary: $summary,
            url: $definition['url'] ?? route($route, $definition['route_parameters'] ?? []),
            route: $route,
            icon: $definition['icon'] ?? null,
            image: $definition['image'] ?? null,
            badge: $badge,
            group: $group,
            groupLabel: $groupLabel,
            groupIcon: $groupDefinition['icon'] ?? null,
            groupOrder: (int) ($groupDefinition['order'] ?? 100),
            score: $this->scoreStatic($term, [
                'title' => $title,
                'summary' => $summary,
                'group' => $groupLabel,
                'badge' => $badge,
                'keywords' => implode(' ', $keywords),
            ], (int) ($definition['weight'] ?? 100)),
            metadata: [
                'keywords' => $keywords,
            ],
        );
    }

    private function scoreStatic(string $term, array $fields, int $weight): int
    {
        if ($term === '') {
            return $weight;
        }

        $score = 0;
        $normalizedTerm = $this->normalize($term);
        $tokens = collect(explode(' ', $normalizedTerm))
            ->filter(fn(string $token): bool => Str::length($token) >= 2)
            ->unique()
            ->values();

        foreach ($this->staticFieldWeights() as $field => $fieldWeight) {
            $value = $this->normalize((string) ($fields[$field] ?? ''));

            if ($value === '') {
                continue;
            }

            if ($value === $normalizedTerm) {
                $score += $fieldWeight;
                continue;
            }

            if (str_starts_with($value, $normalizedTerm)) {
                $score += (int) round($fieldWeight * 0.8);
            }

            if (str_contains($value, $normalizedTerm)) {
                $score += (int) round($fieldWeight * 0.6);
            }

            foreach ($tokens as $token) {
                if (str_contains($value, $token)) {
                    $score += (int) round($fieldWeight * 0.2);
                }
            }
        }

        return $score > 0
            ? $score + $weight
            : 0;
    }

    private function text(array $definition, string $key): ?string
    {
        if (isset($definition["{$key}_key"])) {
            return (string) trans($definition["{$key}_key"]);
        }

        return isset($definition[$key])
            ? (string) $definition[$key]
            : null;
    }

    private function keywords(array $definition): array
    {
        $keywords = $definition['keywords'] ?? [];

        if (isset($definition['keywords_key'])) {
            $keywords = trans($definition['keywords_key']);
        }

        return collect($keywords)
            ->filter(fn(mixed $keyword): bool => filled($keyword))
            ->map(fn(mixed $keyword): string => (string) $keyword)
            ->values()
            ->all();
    }

    private function groupDefinitions(): array
    {
        return $this->config['groups'] ?? [];
    }

    private function groupLabel(array $definition): string
    {
        if (isset($definition['label_key'])) {
            return (string) trans($definition['label_key']);
        }

        return (string) $definition['label'];
    }

    private function staticDefinitions(): array
    {
        return $this->config['statics'] ?? [];
    }

    private function staticFieldWeights(): array
    {
        return config('search.defaults.static_field_weights', []);
    }

    private function minChars(): int
    {
        return (int) ($this->config['min_chars'] ?? config('search.defaults.min_chars', 2));
    }

    private function limit(): int
    {
        return (int) ($this->config['limit'] ?? config('search.defaults.limit', 12));
    }

    private function staticKey(string $key): string
    {
        return "{$this->scope}.statics.{$key}";
    }

    private function groupKey(string $key): string
    {
        return "{$this->scope}.groups.{$key}";
    }

    private function normalize(string $value): string
    {
        return Str::of($value)
            ->ascii()
            ->lower()
            ->squish()
            ->toString();
    }
}
