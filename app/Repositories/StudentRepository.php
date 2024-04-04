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
                    CharacteristicId::GROUP_UNION_MEMBER_ID->value
                ])
            )
        );
    }
}
