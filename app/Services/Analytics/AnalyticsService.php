<?php

namespace App\Services\Analytics;

use App\Models\Course;
use Illuminate\Support\Collection;

class AnalyticsService
{
    public function get(AnalyticsStrategyInterface $strategy, ?Course $course = null): Collection
    {
        return $strategy->query($course)->get();
    }

    public function count(AnalyticsStrategyInterface $strategy, ?Course $course = null): int
    {
        return $strategy->query($course)->count();
    }
}
