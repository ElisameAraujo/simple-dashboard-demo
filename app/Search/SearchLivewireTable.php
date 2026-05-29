<?php

namespace App\Search;

use App\Search\Exceptions\InvalidSearchConfigurationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SearchLivewireTable
{
    private const TERM_MODES = [
        'all',
        'any',
    ];

    private const MATCH_MODES = [
        'partial',
        'prefix',
        'exact',
    ];

    public function __construct(
        private readonly string $table,
        private readonly ?array $config,
    ) {}

    public function apply(Builder $query, ?string $term): Builder
    {
        $this->validateOrFail($query);

        $term = trim((string) $term);

        if ($term === '' || Str::length($term) < $this->minChars()) {
            return $query;
        }

        $tokens = $this->tokens($term);

        if ($tokens === []) {
            return $query;
        }

        $query->where(function (Builder $searchQuery) use ($term, $tokens): void {
            $this->applyTextSearch($searchQuery, $term, $tokens);
        });

        if ($this->usesRelevanceOrder()) {
            $query->orderByRaw($this->relevanceSql($term, $tokens), $this->relevanceBindings($term, $tokens));
        }

        return $query;
    }

    public function validateOrFail(?Builder $query = null): void
    {
        $fullKey = $this->configKey();

        if ($this->config === null) {
            throw InvalidSearchConfigurationException::missingLivewireTable($fullKey);
        }

        if (! isset($this->config['model'])) {
            throw InvalidSearchConfigurationException::missingModelClass($fullKey);
        }

        if (! is_a($this->config['model'], Model::class, true)) {
            throw InvalidSearchConfigurationException::invalidModelClass($fullKey, (string) $this->config['model']);
        }

        if ($query !== null && $query->getModel()::class !== $this->config['model']) {
            throw InvalidSearchConfigurationException::invalidLivewireTableBuilder($fullKey, $query->getModel()::class);
        }

        if (! isset($this->config['searchable_fields']) || ! is_array($this->config['searchable_fields']) || $this->config['searchable_fields'] === []) {
            throw InvalidSearchConfigurationException::invalidModelFields($fullKey, 'searchable_fields');
        }

        $modelClass = $this->config['model'];
        $model = new $modelClass;
        $databaseTable = $model->getTable();

        if (! Schema::hasTable($databaseTable)) {
            throw InvalidSearchConfigurationException::missingModelTable($fullKey, $databaseTable);
        }

        foreach ($this->fieldsToValidate() as $field) {
            if (! Schema::hasColumn($databaseTable, $field)) {
                throw InvalidSearchConfigurationException::unknownModelField($fullKey, $field, $databaseTable);
            }
        }

        foreach (($this->config['fields_weight'] ?? []) as $field => $weight) {
            if (! in_array($field, $this->searchableFields(), true) || ! is_numeric($weight) || (int) $weight <= 0) {
                throw InvalidSearchConfigurationException::invalidFieldWeight($fullKey, (string) $field);
            }
        }

        if (! in_array($this->termMode(), self::TERM_MODES, true)) {
            throw InvalidSearchConfigurationException::invalidLivewireTableOption($fullKey, 'term_mode');
        }

        if (! in_array($this->matchMode(), self::MATCH_MODES, true)) {
            throw InvalidSearchConfigurationException::invalidLivewireTableOption($fullKey, 'match_mode');
        }
    }

    private function applyTextSearch(Builder $query, string $term, array $tokens): void
    {
        if ($this->termMode() === 'all') {
            foreach ($tokens as $token) {
                $query->where(function (Builder $tokenQuery) use ($token): void {
                    foreach ($this->searchableFields() as $field) {
                        $tokenQuery->orWhere($this->qualifiedField($field), $this->operator(), $this->matchValue($token));
                    }
                });
            }
        } else {
            $query->where(function (Builder $tokenQuery) use ($tokens): void {
                foreach ($tokens as $token) {
                    foreach ($this->searchableFields() as $field) {
                        $tokenQuery->orWhere($this->qualifiedField($field), $this->operator(), $this->matchValue($token));
                    }
                }
            });
        }

        foreach ($this->fuzzyFields() as $field) {
            $query->orWhereRaw("SOUNDEX({$this->qualifiedField($field)}) = SOUNDEX(?)", [$term]);
        }
    }

    private function relevanceSql(string $term, array $tokens): string
    {
        $parts = [];

        foreach ($this->fieldWeights() as $field => $weight) {
            $column = $this->qualifiedField($field);
            $parts[] = "CASE WHEN {$column} LIKE ? THEN {$weight} ELSE 0 END";
            $parts[] = "CASE WHEN {$column} LIKE ? THEN ".((int) round($weight * 0.65)).' ELSE 0 END';

            foreach ($tokens as $token) {
                $parts[] = "CASE WHEN {$column} LIKE ? THEN ".((int) round($weight * 0.25)).' ELSE 0 END';
            }
        }

        return '('.implode(' + ', $parts).') DESC';
    }

    private function relevanceBindings(string $term, array $tokens): array
    {
        $bindings = [];

        foreach ($this->fieldWeights() as $field => $weight) {
            $bindings[] = $term.'%';
            $bindings[] = '%'.$term.'%';

            foreach ($tokens as $token) {
                $bindings[] = '%'.$token.'%';
            }
        }

        return $bindings;
    }

    private function tokens(string $term): array
    {
        return Str::of($term)
            ->squish()
            ->explode(' ')
            ->map(fn (string $token): string => trim($token))
            ->filter(fn (string $token): bool => Str::length($token) >= 2)
            ->unique()
            ->values()
            ->all();
    }

    private function fieldsToValidate(): array
    {
        return collect($this->searchableFields())
            ->merge(array_keys($this->config['fields_weight'] ?? []))
            ->merge($this->fuzzyFields())
            ->filter(fn (mixed $field): bool => is_string($field) && $field !== '')
            ->unique()
            ->values()
            ->all();
    }

    private function searchableFields(): array
    {
        return $this->config['searchable_fields'] ?? [];
    }

    private function fieldWeights(): array
    {
        if (($this->config['fields_weight'] ?? []) !== []) {
            return array_map('intval', $this->config['fields_weight']);
        }

        return collect($this->searchableFields())
            ->mapWithKeys(fn (string $field): array => [$field => (int) config('search.defaults.model_field_weight', 50)])
            ->all();
    }

    private function fuzzyFields(): array
    {
        if (! (bool) data_get($this->config, 'fuzzy.enabled', false)) {
            return [];
        }

        return data_get($this->config, 'fuzzy.fields', []);
    }

    private function minChars(): int
    {
        return (int) ($this->config['min_chars'] ?? config('search.defaults.min_chars', 2));
    }

    private function termMode(): string
    {
        return (string) ($this->config['term_mode'] ?? 'all');
    }

    private function matchMode(): string
    {
        return (string) ($this->config['match_mode'] ?? 'partial');
    }

    private function operator(): string
    {
        return $this->matchMode() === 'exact' ? '=' : 'like';
    }

    private function matchValue(string $token): string
    {
        return match ($this->matchMode()) {
            'exact' => $token,
            'prefix' => $token.'%',
            default => '%'.$token.'%',
        };
    }

    private function usesRelevanceOrder(): bool
    {
        return (bool) ($this->config['relevance_order'] ?? true);
    }

    private function qualifiedField(string $field): string
    {
        $modelClass = $this->config['model'];
        $model = new $modelClass;

        return $model->qualifyColumn($field);
    }

    private function configKey(): string
    {
        return "livewire_tables.{$this->table}";
    }
}
