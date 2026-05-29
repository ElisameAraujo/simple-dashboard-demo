<?php

namespace App\Search;

class SearchEngine
{
    public function scope(string $scope): SearchScope
    {
        return new SearchScope($scope, config("search.scopes.{$scope}"));
    }

    public function livewireTable(string $table): SearchLivewireTable
    {
        return new SearchLivewireTable($table, config("search.livewire_tables.{$table}"));
    }
}
