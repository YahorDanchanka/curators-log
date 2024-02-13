<?php

namespace App\Services;

use App\Enums\CharacteristicId;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class GroupCompositionService
{
    public function getCharacteristicIds(): array
    {
        return [
            CharacteristicId::EDUCATIONAL_SECTOR_ID,
            CharacteristicId::INFORMATION_IDEOLOGICAL_SECTOR_ID,
            CharacteristicId::SPORT_SECTOR_ID,
            CharacteristicId::LABOR_SECTOR_ID,
            CharacteristicId::CULTURAL_MASS_SECTOR_ID,
            CharacteristicId::LAW_SECTOR_ID,
            CharacteristicId::EDITORIAL_SECTOR_ID,
        ];
    }

    public static function getCharacteristicIds1(): array
    {
        return [
            CharacteristicId::EDUCATIONAL_SECTOR_ID->value,
            CharacteristicId::INFORMATION_IDEOLOGICAL_SECTOR_ID->value,
            CharacteristicId::SPORT_SECTOR_ID->value,
            CharacteristicId::LABOR_SECTOR_ID->value,
            CharacteristicId::CULTURAL_MASS_SECTOR_ID->value,
            CharacteristicId::LAW_SECTOR_ID->value,
            CharacteristicId::EDITORIAL_SECTOR_ID->value,
        ];
    }

    public function save(Course $course, array $groupComposition)
    {
        DB::transaction(function () use ($course, $groupComposition) {
            $course
                ->characteristics()
                ->whereIn('characteristic_id', $this->getCharacteristicIds())
                ->delete();

            $course->characteristics()->createMany($groupComposition);
        });
    }
}
