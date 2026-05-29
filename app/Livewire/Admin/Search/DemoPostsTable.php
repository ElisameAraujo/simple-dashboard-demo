<?php

namespace App\Livewire\Admin\Search;

use App\Models\Demo\SearchPost;
use App\Search\SearchEngine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class DemoPostsTable extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public string $orderBy = 'relevance';

    public string $orderDirection = 'desc';

    public function updating(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.admin.search.demo-posts-table', [
            'posts' => $this->posts(),
            'statuses' => $this->statuses(),
            'orderFields' => $this->orderFields(),
        ]);
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'status', 'orderBy', 'orderDirection');
        $this->orderBy = 'relevance';
        $this->orderDirection = 'desc';
        $this->resetPage();
    }

    private function posts()
    {
        return $this->filteredPostsQuery()
            ->paginate(6)
            ->onEachSide(1);
    }

    private function filteredPostsQuery(): Builder
    {
        $query = SearchPost::query()
            ->when(
                $this->status !== '',
                fn (Builder $query): Builder => $query->where('status', $this->status)
            );

        $query = app(SearchEngine::class)
            ->livewireTable('demo_posts')
            ->apply($query, $this->search);

        return $this->applyOrdering($query);
    }

    private function applyOrdering(Builder $query): Builder
    {
        if ($this->orderBy === 'relevance' && trim($this->search) !== '') {
            return $query;
        }

        $query->reorder();

        return match ($this->orderBy) {
            'title' => $query->orderBy('title', $this->orderDirection),
            'status' => $query->orderBy('status', $this->orderDirection),
            default => $query->orderBy('published_at', $this->orderDirection),
        };
    }

    private function statuses(): array
    {
        return [
            'published' => __('components/search-engine.livewire.statuses.published'),
            'draft' => __('components/search-engine.livewire.statuses.draft'),
        ];
    }

    private function orderFields(): array
    {
        return [
            'relevance' => __('components/search-engine.livewire.order.relevance'),
            'published_at' => __('components/search-engine.livewire.order.published_at'),
            'title' => __('components/search-engine.livewire.order.title'),
            'status' => __('components/search-engine.livewire.order.status'),
        ];
    }
}
