<?php

namespace App\Visits\Traits;

use App\Visits\Models\Visit;
use App\Visits\PendingVisit;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;

trait HasVisits
{
    public function visits()
    {
        return $this->morphMany(Visit::class, 'visitable');
    }

    public function visit(): PendingVisit
    {
        return new PendingVisit($this);
    }

    public function getVisitCountTotalAttribute(): int
    {
        if (array_key_exists('visit_count_total', $this->attributes)) {
            return (int) $this->attributes['visit_count_total'];
        }

        return $this->visits()->count();
    }

    public function scopeWithTotalVisitCount(Builder $query): Builder
    {
        return $query->withCount('visits as visit_count_total');
    }

    public function scopePopularAllTime(Builder $query): Builder
    {
        return $query
            ->withTotalVisitCount()
            ->orderByDesc('visit_count_total');
    }

    public function scopePopularToday(Builder $query): Builder
    {
        return $query->popularBetween(now()->startOfDay(), now()->endOfDay());
    }

    public function scopePopularThisWeek(Builder $query): Builder
    {
        return $query->popularBetween(now()->startOfWeek(), now()->endOfWeek());
    }

    public function scopePopularLastWeek(Builder $query): Builder
    {
        $lastWeek = now()->subWeek();

        return $query->popularBetween($lastWeek->copy()->startOfWeek(), $lastWeek->copy()->endOfWeek());
    }

    public function scopePopularThisMonth(Builder $query): Builder
    {
        return $query->popularBetween(now()->startOfMonth(), now()->endOfMonth());
    }

    public function scopePopularLastMonth(Builder $query): Builder
    {
        $lastMonth = now()->subMonthNoOverflow();

        return $query->popularBetween($lastMonth->copy()->startOfMonth(), $lastMonth->copy()->endOfMonth());
    }

    public function scopePopularThisYear(Builder $query): Builder
    {
        return $query->popularBetween(now()->startOfYear(), now()->endOfYear());
    }

    public function scopePopularLastYear(Builder $query): Builder
    {
        $lastYear = now()->subYearNoOverflow();

        return $query->popularBetween($lastYear->copy()->startOfYear(), $lastYear->copy()->endOfYear());
    }

    public function scopePopularLastDays(Builder $query, int $days): Builder
    {
        return $query->popularBetween(now()->subDays($days), now());
    }

    public function scopePopularBetween(Builder $query, CarbonInterface $from, CarbonInterface $to): Builder
    {
        return $query
            ->withCount([
                'visits as visit_count_total' => fn($query) => $query->whereBetween('visited_at', [$from, $to]),
            ])
            ->orderByDesc('visit_count_total');
    }
}
