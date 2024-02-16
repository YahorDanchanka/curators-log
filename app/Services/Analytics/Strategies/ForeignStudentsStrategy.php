<?php

namespace App\Services\Analytics\Strategies;

use App\Models\Course;
use App\Models\Student;
use App\Services\Analytics\AnalyticsStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class ForeignStudentsStrategy implements AnalyticsStrategyInterface
{
    public function query(?Course $course = null): Builder
    {
        $condition = [['citizenship', '!=', 'Республика Беларусь']];
        $group = $course ? $course->group : null;
        return $group
            ? $group
                ->students()
                ->getQuery()
                ->where($condition)
            : Student::where($condition);
    }
}
