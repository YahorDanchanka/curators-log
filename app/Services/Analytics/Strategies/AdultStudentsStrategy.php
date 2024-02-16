<?php

namespace App\Services\Analytics\Strategies;

use App\Models\Course;
use App\Models\Student;
use App\Services\Analytics\AnalyticsStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class AdultStudentsStrategy implements AnalyticsStrategyInterface
{
    public function query(?Course $course = null): Builder
    {
        $group = $course ? $course->group : null;
        return $group
            ? $group
                ->students()
                ->getQuery()
                ->whereDate('birthday', '<=', date('Y-m-d', strtotime('-18 years')))
            : Student::whereDate('birthday', '<=', date('Y-m-d', strtotime('-18 years')));
    }
}
