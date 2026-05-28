<?php

namespace App\Livewire\Admin\Search;

use App\Search\SearchEngine;
use Illuminate\Support\Collection;
use Livewire\Component;

class SearchSpotlight extends Component
{
    public string $term = '';

    public ?string $activeGroup = null;

    public bool $isOpen = false;

    public function open(): void
    {
        $this->isOpen = true;
    }

    public function close(): void
    {
        $this->isOpen = false;
        $this->term = '';
        $this->activeGroup = null;
    }

    public function updatedTerm(): void
    {
        $this->activeGroup = null;
    }

    public function selectGroup(?string $group = null): void
    {
        $this->activeGroup = $this->activeGroup === $group ? null : $group;
    }

    public function render()
    {
        $results = $this->results($this->activeGroup);

        return view('livewire.admin.search.search-spotlight', [
            'results' => $results,
            'groups' => $this->groupFilters($results),
            'minimumLength' => (int) config('search.scopes.admin.min_chars', 2),
        ]);
    }

    private function results(?string $group = null): Collection
    {
        return app(SearchEngine::class)
            ->scope('admin')
            ->preview($this->term, group: $group);
    }

    private function groupFilters(Collection $results): Collection
    {
        return $results
            ->filter(fn($result): bool => filled($result->group))
            ->groupBy(fn($result): string => $result->group)
            ->map(fn(Collection $items, string $group): array => [
                'key' => $group,
                'label' => $items->first()->groupLabel,
                'icon' => $items->first()->groupIcon,
                'order' => $items->first()->groupOrder,
                'count' => $items->count(),
                'active' => $this->activeGroup === $group,
            ])
            ->sortBy([
                ['order', 'asc'],
                ['label', 'asc'],
            ])
            ->values();
    }
}
