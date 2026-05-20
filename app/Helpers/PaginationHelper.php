<?php

namespace App\Helpers;

class PaginationHelper
{
    public static function build($paginator, $eachSide = null)
    {
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();

        // If you don't want to cycle manually, use the onEachSide function of the paginator.
        $eachSide = $eachSide ?? $paginator->onEachSide;

        // First pages
        $startRange = range(1, min($eachSide, $last));

        // Latest pages
        $endRange = range(max($last - $eachSide + 1, 1), $last);

        // Pages around the current page
        $middleStart = max($current - $eachSide, 1);
        $middleEnd = min($current + $eachSide, $last);
        $middleRange = range($middleStart, $middleEnd);

        // Unify and sort the page numbers
        $pages = array_unique(array_merge($startRange, $middleRange, $endRange));
        sort($pages);

        return $pages;
    }
}
