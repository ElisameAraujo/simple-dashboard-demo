<?php

namespace App\Search;

class SearchEngine
{
    public function scope(string $scope): SearchScope
    {
        return new SearchScope($scope, config("search.scopes.{$scope}"));
    }
}
