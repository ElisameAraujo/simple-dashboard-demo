<?php

namespace App\Livewire\Web\Search;

use App\Search\SearchEngine;
use Illuminate\View\View;
use Livewire\Component;

class SearchDropdown extends Component
{
    public string $term = '';

    public bool $isOpen = false;

    public function toggle(): void
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function close(): void
    {
        $this->isOpen = false;
    }

    public function updatedTerm(): void
    {
        $this->isOpen = true;
    }

    public function render(SearchEngine $searchEngine): View
    {
        $term = trim($this->term);

        return view('livewire.web.search.search-dropdown', [
            'results' => $term === ''
                ? $searchEngine->scope('web')->suggestions(6)
                : $searchEngine->scope('web')->search($term, 6),
            'term' => $term,
            'minimumLength' => (int) config('search.scopes.web.min_chars', 2),
        ]);
    }
}
