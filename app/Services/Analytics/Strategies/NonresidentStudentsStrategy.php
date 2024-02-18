<?php

namespace App\Services\Analytics\Strategies;

use App\Models\Course;
use App\Models\Student;
use App\Services\Analytics\AnalyticsStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class NonresidentStudentsStrategy implements AnalyticsStrategyInterface
{
    public function query(?Course $course = null): Builder
    {
        $group = $course ? $course->group : null;
        return $group
            ? $group
                ->students()
                ->getQuery()
                ->whereRelation('address', 'type', 'Город')
                ->whereRelation('address', 'residence', 'Гомель')
                ->whereRelation('address', 'region_id', 40)
                ->whereRelation('address', 'district_id', 44)
            : Student::whereRelation('address', 'type', 'Город')
                ->whereRelation('address', 'residence', 'Гомель')
                ->whereRelation('address', 'region_id', 40)
                ->whereRelation('address', 'district_id', 44);
    }
}
