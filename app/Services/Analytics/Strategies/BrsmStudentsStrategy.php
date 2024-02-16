<?php

namespace App\Services\Analytics\Strategies;

use App\Models\Course;
use App\Models\Student;
use App\Services\Analytics\AnalyticsStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class BrsmStudentsStrategy implements AnalyticsStrategyInterface
{
    public function query(?Course $course = null): Builder
    {
        $group = $course ? $course->group : null;
        return $group
            ? $group
                ->students()
                ->getQuery()
                ->whereRelation('characteristics', 'characteristic_id', 30)
            : Student::whereRelation('characteristics', 'characteristic_id', 30);
    }
}
