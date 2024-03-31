<?php

namespace App\Services\Analytics\Strategies;

use App\Models\Course;
use App\Models\Student;
use App\Services\Analytics\AnalyticsStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class OPFRStudentsStrategy implements AnalyticsStrategyInterface
{
    public function query(?Course $course = null): Builder
    {
        $group = $course ? $course->group : null;
        return $group
            ? $group
                ->students()
                ->getQuery()
                ->whereHas(
                    'characteristics',
                    fn(Builder $query) => $query->where('characteristic_id', 12)->where('course_id', $course->id)
                )
            : Student::whereRelation('characteristics', 'characteristic_id', 12);
    }
}
