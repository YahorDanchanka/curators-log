<?php

namespace App\Services\Analytics\Strategies;

use App\Models\Course;
use App\Models\Student;
use App\Services\Analytics\AnalyticsStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class DormitoryStudentsStrategy implements AnalyticsStrategyInterface
{
    public function query(?Course $course = null): Builder
    {
        $condition = ['study_address_id' => 1];
        $group = $course ? $course->group : null;
        return $group
            ? $group
                ->students()
                ->getQuery()
                ->where($condition)
            : Student::where($condition);
    }
}
