<?php

namespace App\Services\Analytics;

use App\Models\Course;
use Illuminate\Database\Eloquent\Builder;

interface AnalyticsStrategyInterface
{
    public function query(?Course $course = null): Builder;
}
