<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Search\SearchEngine;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    private const RESULTS_PER_PAGE = 8;

    public function __invoke(Request $request, SearchEngine $searchEngine): View
    {
        $term = trim((string) $request->query('q', ''));
        $results = $this->resultsFor($term, $searchEngine);
        $resultsCount = $results->count();

        return view('web.search.index', [
            'term' => $term,
            'results' => $this->paginateResults($results, $request),
            'resultsCount' => $resultsCount,
        ]);
    }

    private function resultsFor(string $term, SearchEngine $searchEngine): Collection
    {
        return $term === ''
            ? $searchEngine->scope('web')->suggestions(8)
            : $searchEngine->scope('web')->search($term, 60);
    }

    private function paginateResults(Collection $results, Request $request): LengthAwarePaginator
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        return new LengthAwarePaginator(
            items: $results->forPage($currentPage, self::RESULTS_PER_PAGE)->values(),
            total: $results->count(),
            perPage: self::RESULTS_PER_PAGE,
            currentPage: $currentPage,
            options: [
                'path' => $request->url(),
                'query' => $request->query(),
            ],
        );
    }
}
