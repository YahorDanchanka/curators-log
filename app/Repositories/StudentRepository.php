<?php

namespace App\Repositories;

use App\Enums\CharacteristicId;
use Illuminate\Support\Collection;

class StudentRepository
{
    public function __construct(protected Collection $students)
    {
    }

    public function getBrsmStudents(): Collection
    {
        return $this->students->filter(
            fn($student) => $student->characteristics->contains(
                fn($characteristic) => $characteristic->id === CharacteristicId::BRSM_ID->value
            )
        );
    }

    public function getLeadershipStudents(): Collection
    {
        return $this->students->filter(
            fn($student) => $student->characteristics->contains(
                fn($characteristic) => in_array($characteristic->id, [
                    CharacteristicId::LEADER_ID->value,
                    CharacteristicId::DEPUTY_LEADER_ID->value,
                    CharacteristicId::BRSM_SECRETARY_ID->value,
                    CharacteristicId::UNION_ORGANIZER_ID->value,
                    CharacteristicId::EDUCATIONAL_SECTOR_ID->value,
                    CharacteristicId::INFORMATION_IDEOLOGICAL_SECTOR_ID->value,
                    CharacteristicId::SPORT_SECTOR_ID->value,
                    CharacteristicId::LABOR_SECTOR_ID->value,
                    CharacteristicId::CULTURAL_MASS_SECTOR_ID->value,
                    CharacteristicId::LAW_SECTOR_ID->value,
                    CharacteristicId::EDITORIAL_SECTOR_ID->value,
                ])
            )
        );
    }
}
