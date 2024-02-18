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
        $group = $course ? $course->group : null;
        return $group
            ? $group
                ->students()
                ->getQuery()
                ->whereRelation('studyAddress', 'type', 'Город')
                ->whereRelation('studyAddress', 'residence', 'Гомель')
                ->whereRelation('studyAddress', 'street', 'Речицкая 4')
                ->whereRelation('studyAddress', 'region_id', 40)
                ->whereRelation('studyAddress', 'district_id', 44)
            : Student::whereRelation('studyAddress', 'type', 'Город')
                ->whereRelation('studyAddress', 'residence', 'Гомель')
                ->whereRelation('studyAddress', 'street', 'Речицкая 4')
                ->whereRelation('studyAddress', 'region_id', 40)
                ->whereRelation('studyAddress', 'district_id', 44);
    }
}
