<?php

namespace App\Search;

use App\Search\Exceptions\InvalidSearchConfigurationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SearchScope
{
    private const CONSTRAINT_OPERATORS = [
        '=',
        '!=',
        '<>',
        '>',
        '>=',
        '<',
        '<=',
        'like',
        'not_like',
        'in',
        'not_in',
        'null',
        'not_null',
    ];

    private const ACTION_CLICK_VALUES = [
        'edit',
        'visit',
    ];

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
            ->merge($this->modelResults($term, $group))
            ->filter(fn (SearchResult $result): bool => $result->score > 0)
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
            ->merge($this->modelResults('', $group))
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

        foreach ($this->modelDefinitions() as $key => $definition) {
            $fullKey = $this->modelKey($key);

            if (! isset($definition['model'])) {
                throw InvalidSearchConfigurationException::missingModelClass($fullKey);
            }

            if (! is_a($definition['model'], Model::class, true)) {
                throw InvalidSearchConfigurationException::invalidModelClass($fullKey, (string) $definition['model']);
            }

            if (! isset($definition['group'])) {
                throw InvalidSearchConfigurationException::missingModelGroup($fullKey);
            }

            if (! isset($this->groupDefinitions()[$definition['group']])) {
                throw InvalidSearchConfigurationException::invalidGroup($fullKey, $definition['group']);
            }

            if (isset($definition['route']) && ! Route::has($definition['route'])) {
                throw InvalidSearchConfigurationException::invalidRoute($fullKey, $definition['route']);
            }

            foreach (['searchable_fields', 'select_fields'] as $field) {
                if (! isset($definition[$field]) || ! is_array($definition[$field]) || $definition[$field] === []) {
                    throw InvalidSearchConfigurationException::invalidModelFields($fullKey, $field);
                }
            }

            if (! isset($definition['title_field'])) {
                throw InvalidSearchConfigurationException::missingModelField($fullKey, 'title_field');
            }

            if (isset($definition['weight']) && (! is_numeric($definition['weight']) || (int) $definition['weight'] <= 0)) {
                throw InvalidSearchConfigurationException::invalidWeight($fullKey);
            }

            $modelClass = $definition['model'];
            $model = new $modelClass;
            $table = $model->getTable();

            if (! Schema::hasTable($table)) {
                throw InvalidSearchConfigurationException::missingModelTable($fullKey, $table);
            }

            foreach ($this->modelFields($definition, $model) as $field) {
                if (! Schema::hasColumn($table, $field)) {
                    throw InvalidSearchConfigurationException::unknownModelField($fullKey, $field, $table);
                }
            }

            foreach (($definition['fields_weight'] ?? []) as $field => $weight) {
                if (! in_array($field, $definition['searchable_fields'], true) || ! is_numeric($weight) || (int) $weight <= 0) {
                    throw InvalidSearchConfigurationException::invalidFieldWeight($fullKey, (string) $field);
                }
            }

            foreach (($definition['constraints'] ?? []) as $constraint) {
                $this->validateConstraint($fullKey, $constraint);
            }
        }

        foreach ($this->actionDefinitions() as $modelKey => $definition) {
            $fullKey = $this->actionKey($modelKey);

            if (! isset($this->modelDefinitions()[$modelKey])) {
                throw InvalidSearchConfigurationException::invalidActionModel($fullKey);
            }

            if (! isset($definition['items']) || ! is_array($definition['items']) || $definition['items'] === []) {
                throw InvalidSearchConfigurationException::invalidActionItems($fullKey);
            }

            if (isset($definition['click']) && $definition['click'] !== null) {
                if (
                    ! is_string($definition['click'])
                    || ! in_array($definition['click'], self::ACTION_CLICK_VALUES, true)
                    || ! isset($definition['items'][$definition['click']])
                ) {
                    throw InvalidSearchConfigurationException::invalidActionClick($fullKey, (string) $definition['click']);
                }
            }

            $modelDefinition = $this->modelDefinitions()[$modelKey];
            $modelClass = $modelDefinition['model'];
            $model = new $modelClass;
            $table = $model->getTable();

            foreach ($definition['items'] as $action => $actionDefinition) {
                $actionKey = "{$fullKey}.items.{$action}";

                if (! isset($actionDefinition['label']) && ! isset($actionDefinition['label_key'])) {
                    throw InvalidSearchConfigurationException::missingActionLabel($actionKey);
                }

                if (
                    ! isset($actionDefinition['route']['name'])
                    || ! isset($actionDefinition['route']['parameters'])
                    || ! is_array($actionDefinition['route']['parameters'])
                ) {
                    throw InvalidSearchConfigurationException::invalidActionRoute($actionKey);
                }

                if (! Route::has($actionDefinition['route']['name'])) {
                    throw InvalidSearchConfigurationException::invalidRoute($actionKey, $actionDefinition['route']['name']);
                }

                foreach ($actionDefinition['route']['parameters'] as $field) {
                    if (! Schema::hasColumn($table, $field)) {
                        throw InvalidSearchConfigurationException::unknownModelField($actionKey, $field, $table);
                    }
                }

                foreach (($actionDefinition['visible_when'] ?? []) as $constraint) {
                    $this->validateConstraint($actionKey, $constraint);

                    if (! Schema::hasColumn($table, $constraint['field'])) {
                        throw InvalidSearchConfigurationException::unknownModelField($actionKey, $constraint['field'], $table);
                    }
                }
            }
        }
    }

    private function staticResults(string $term, ?string $group = null): Collection
    {
        return collect($this->staticDefinitions())
            ->when($group !== null, fn (Collection $definitions): Collection => $definitions
                ->filter(fn (array $definition): bool => ($definition['group'] ?? null) === $group))
            ->map(fn (array $definition, string $key): SearchResult => $this->resultFromStatic($key, $definition, $term))
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

    private function modelResults(string $term, ?string $group = null): Collection
    {
        return collect($this->modelDefinitions())
            ->when($group !== null, fn (Collection $definitions): Collection => $definitions
                ->filter(fn (array $definition): bool => ($definition['group'] ?? null) === $group))
            ->flatMap(fn (array $definition, string $key): Collection => $this->resultsFromModel($key, $definition, $term))
            ->values();
    }

    private function resultsFromModel(string $key, array $definition, string $term): Collection
    {
        if ($term === '' && ! (bool) ($definition['suggestions'] ?? false)) {
            return collect();
        }

        /** @var class-string<Model> $modelClass */
        $modelClass = $definition['model'];
        $model = new $modelClass;
        $query = $modelClass::query()->select($this->selectedModelFields($definition, $model));

        foreach (($definition['constraints'] ?? []) as $constraint) {
            $this->applyConstraint($query, $constraint);
        }

        if ($term !== '') {
            $normalizedTerm = $this->normalize($term);
            $tokens = collect(explode(' ', $normalizedTerm))
                ->filter(fn (string $token): bool => Str::length($token) >= 2)
                ->unique()
                ->values();

            $query->where(function ($query) use ($definition, $term, $tokens): void {
                foreach ($definition['searchable_fields'] as $field) {
                    $query->orWhere($field, 'like', "%{$term}%");

                    foreach ($tokens as $token) {
                        $query->orWhere($field, 'like', "%{$token}%");
                    }
                }
            });
        }

        foreach (($definition['order_by'] ?? []) as $field => $direction) {
            $query->orderBy($field, $direction);
        }

        return $query
            ->limit((int) ($definition['candidate_limit'] ?? 50))
            ->get()
            ->map(fn (Model $item): SearchResult => $this->resultFromModel($key, $definition, $item, $term));
    }

    private function resultFromModel(string $key, array $definition, Model $item, string $term): SearchResult
    {
        $group = $definition['group'];
        $groupDefinition = $this->groupDefinitions()[$group];
        $groupLabel = $this->groupLabel($groupDefinition);
        $badge = $this->modelText($item, $definition, 'badge');
        $clickAction = $this->clickAction($key, $item);
        $actions = $this->visibleActions($key, $item, $clickAction);

        return new SearchResult(
            key: $this->modelKey($key).".{$item->getKey()}",
            scope: $this->scope,
            source: 'models',
            type: $definition['type'] ?? 'model',
            title: (string) $item->getAttribute($definition['title_field']),
            summary: $this->modelText($item, $definition, 'summary'),
            url: $clickAction !== null
                ? $clickAction['url']
                : $this->modelUrl($definition, $item),
            route: $definition['route'] ?? null,
            icon: $definition['icon'] ?? null,
            image: $this->modelText($item, $definition, 'image'),
            badge: $badge,
            group: $group,
            groupLabel: $groupLabel,
            groupIcon: $groupDefinition['icon'] ?? null,
            groupOrder: (int) ($groupDefinition['order'] ?? 100),
            clickAction: $clickAction['key'] ?? null,
            actions: $actions,
            score: $this->scoreModel($term, $definition, $item),
            metadata: [
                'model' => $definition['model'],
                'id' => $item->getKey(),
                'source_key' => $this->modelKey($key),
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
            ->filter(fn (string $token): bool => Str::length($token) >= 2)
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

    private function scoreModel(string $term, array $definition, Model $item): int
    {
        $baseWeight = (int) ($definition['weight'] ?? 100);

        if ($term === '') {
            return $baseWeight;
        }

        $score = 0;
        $normalizedTerm = $this->normalize($term);
        $tokens = collect(explode(' ', $normalizedTerm))
            ->filter(fn (string $token): bool => Str::length($token) >= 2)
            ->unique()
            ->values();

        foreach ($this->modelFieldWeights($definition) as $field => $fieldWeight) {
            $value = $this->normalize((string) $item->getAttribute($field));

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
            ? $score + $baseWeight
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
            ->filter(fn (mixed $keyword): bool => filled($keyword))
            ->map(fn (mixed $keyword): string => (string) $keyword)
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

    private function modelDefinitions(): array
    {
        return $this->config['models'] ?? [];
    }

    private function actionDefinitions(): array
    {
        return $this->config['actions'] ?? [];
    }

    private function staticFieldWeights(): array
    {
        return config('search.defaults.static_field_weights', []);
    }

    private function modelFieldWeights(array $definition): array
    {
        if (isset($definition['fields_weight']) && $definition['fields_weight'] !== []) {
            return array_map('intval', $definition['fields_weight']);
        }

        return collect($definition['searchable_fields'])
            ->mapWithKeys(fn (string $field): array => [$field => (int) config('search.defaults.model_field_weight', 50)])
            ->all();
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

    private function modelKey(string $key): string
    {
        return "{$this->scope}.models.{$key}";
    }

    private function actionKey(string $key): string
    {
        return "{$this->scope}.actions.{$key}";
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

    private function modelText(Model $item, array $definition, string $key): ?string
    {
        if (isset($definition["{$key}_field"])) {
            return (string) $item->getAttribute($definition["{$key}_field"]);
        }

        if (isset($definition["{$key}_key"])) {
            return (string) trans($definition["{$key}_key"]);
        }

        return isset($definition[$key])
            ? (string) $definition[$key]
            : null;
    }

    private function modelUrl(array $definition, Model $item): ?string
    {
        if (isset($definition['url'])) {
            return (string) $definition['url'];
        }

        if (! isset($definition['route'])) {
            return null;
        }

        $parameters = $definition['route_parameters'] ?? [];

        foreach (($definition['route_fields'] ?? []) as $parameter => $field) {
            $parameters[$parameter] = $item->getAttribute($field);
        }

        return route($definition['route'], $parameters);
    }

    private function selectedModelFields(array $definition, Model $model): array
    {
        return collect([$model->getKeyName()])
            ->merge($this->modelFields($definition, $model))
            ->unique()
            ->values()
            ->all();
    }

    private function modelFields(array $definition, Model $model): array
    {
        return collect($definition['select_fields'] ?? [])
            ->merge($definition['searchable_fields'] ?? [])
            ->merge([
                $model->getKeyName(),
                $definition['title_field'] ?? null,
                $definition['summary_field'] ?? null,
                $definition['image_field'] ?? null,
                $definition['badge_field'] ?? null,
            ])
            ->merge(array_values($definition['route_fields'] ?? []))
            ->merge(collect($definition['constraints'] ?? [])->pluck('field'))
            ->merge(array_keys($definition['order_by'] ?? []))
            ->merge($this->actionFieldsForModelDefinition($definition))
            ->filter(fn (mixed $field): bool => is_string($field) && $field !== '')
            ->unique()
            ->values()
            ->all();
    }

    private function validateConstraint(string $fullKey, mixed $constraint): void
    {
        if (! is_array($constraint)) {
            throw InvalidSearchConfigurationException::invalidConstraint($fullKey);
        }

        $operator = strtolower((string) ($constraint['operator'] ?? ''));

        if (
            ! isset($constraint['field'])
            || ! in_array($operator, self::CONSTRAINT_OPERATORS, true)
            || (! in_array($operator, ['null', 'not_null'], true) && ! array_key_exists('value', $constraint))
        ) {
            throw InvalidSearchConfigurationException::invalidConstraint($fullKey);
        }

        if (in_array($operator, ['in', 'not_in'], true) && ! is_array($constraint['value'])) {
            throw InvalidSearchConfigurationException::invalidConstraint($fullKey);
        }
    }

    private function applyConstraint($query, array $constraint): void
    {
        $operator = strtolower((string) $constraint['operator']);
        $field = $constraint['field'];

        match ($operator) {
            'null' => $query->whereNull($field),
            'not_null' => $query->whereNotNull($field),
            'in' => $query->whereIn($field, $constraint['value']),
            'not_in' => $query->whereNotIn($field, $constraint['value']),
            'not_like' => $query->where($field, 'not like', $constraint['value']),
            default => $query->where($field, $operator, $constraint['value']),
        };
    }

    private function clickAction(string $modelKey, Model $item): ?array
    {
        $definition = $this->actionDefinitions()[$modelKey] ?? null;
        $click = $definition['click'] ?? null;

        if ($definition === null || $click === null) {
            return null;
        }

        $action = $definition['items'][$click] ?? null;

        if ($action === null || ! $this->actionIsVisible($action, $item)) {
            return null;
        }

        return $this->resolvedAction($click, $action, $item);
    }

    private function visibleActions(string $modelKey, Model $item, ?array $clickAction = null): array
    {
        $definition = $this->actionDefinitions()[$modelKey] ?? null;

        if ($definition === null || ! (bool) ($definition['show'] ?? false)) {
            return [];
        }

        return collect($definition['items'])
            ->reject(fn (array $action, string $key): bool => $clickAction !== null && $key === $clickAction['key'])
            ->filter(fn (array $action): bool => $this->actionIsVisible($action, $item))
            ->map(fn (array $action, string $key): array => $this->resolvedAction($key, $action, $item))
            ->values()
            ->all();
    }

    private function resolvedAction(string $key, array $definition, Model $item): array
    {
        return [
            'key' => $key,
            'label' => $this->text($definition, 'label'),
            'icon' => $definition['icon'] ?? null,
            'url' => $this->actionUrl($definition, $item),
        ];
    }

    private function actionUrl(array $definition, Model $item): string
    {
        $parameters = collect($definition['route']['parameters'])
            ->mapWithKeys(fn (string $field, string $parameter): array => [$parameter => $item->getAttribute($field)])
            ->all();

        return route($definition['route']['name'], $parameters);
    }

    private function actionIsVisible(array $definition, Model $item): bool
    {
        foreach (($definition['visible_when'] ?? []) as $constraint) {
            if (! $this->modelMatchesConstraint($item, $constraint)) {
                return false;
            }
        }

        return true;
    }

    private function modelMatchesConstraint(Model $item, array $constraint): bool
    {
        $value = $item->getAttribute($constraint['field']);
        $operator = strtolower((string) $constraint['operator']);

        return match ($operator) {
            '=' => $value == $constraint['value'],
            '!=', '<>' => $value != $constraint['value'],
            '>' => $value > $constraint['value'],
            '>=' => $value >= $constraint['value'],
            '<' => $value < $constraint['value'],
            '<=' => $value <= $constraint['value'],
            'like' => str_contains($this->normalize((string) $value), $this->normalize((string) $constraint['value'])),
            'not_like' => ! str_contains($this->normalize((string) $value), $this->normalize((string) $constraint['value'])),
            'in' => in_array($value, $constraint['value'], true),
            'not_in' => ! in_array($value, $constraint['value'], true),
            'null' => $value === null,
            'not_null' => $value !== null,
            default => false,
        };
    }

    private function actionFieldsForModelDefinition(array $definition): array
    {
        $modelKey = array_search($definition, $this->modelDefinitions(), true);

        if (! is_string($modelKey) || ! isset($this->actionDefinitions()[$modelKey])) {
            return [];
        }

        return collect($this->actionDefinitions()[$modelKey]['items'] ?? [])
            ->flatMap(fn (array $action): array => [
                ...array_values($action['route']['parameters'] ?? []),
                ...collect($action['visible_when'] ?? [])->pluck('field')->all(),
            ])
            ->all();
    }
}
