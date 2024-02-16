<?php

namespace App\Services\Analytics\Strategies;

use App\Enums\CharacteristicId;
use App\Models\Course;
use App\Models\Student;
use App\Services\Analytics\AnalyticsStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class LeadershipStudentsStrategy implements AnalyticsStrategyInterface
{
    public function query(?Course $course = null): Builder
    {
        $group = $course ? $course->group : null;

        $characteristicIds = [
            CharacteristicId::LEADER_ID,
            CharacteristicId::DEPUTY_LEADER_ID,
            CharacteristicId::BRSM_SECRETARY_ID,
            CharacteristicId::UNION_ORGANIZER_ID,
            CharacteristicId::EDUCATIONAL_SECTOR_ID,
            CharacteristicId::INFORMATION_IDEOLOGICAL_SECTOR_ID,
            CharacteristicId::SPORT_SECTOR_ID,
            CharacteristicId::LABOR_SECTOR_ID,
            CharacteristicId::CULTURAL_MASS_SECTOR_ID,
            CharacteristicId::LAW_SECTOR_ID,
            CharacteristicId::EDITORIAL_SECTOR_ID,
        ];

        return $group
            ? $group
                ->students()
                ->getQuery()
                ->whereRelation(
                    'characteristics',
                    fn(Builder $query) => $query->whereIn('characteristic_id', $characteristicIds)
                )
            : Student::whereRelation(
                'characteristics',
                fn(Builder $query) => $query->whereIn('characteristic_id', $characteristicIds)
            );
    }
}
