<?php

namespace App\Services;

use App\Enums\CharacteristicId;
use App\Models\Course;

class LeadershipService
{
    public function setLeader(Course $course, $studentId = null)
    {
        $this->set($course, CharacteristicId::LEADER_ID, $studentId);
    }

    public function setDeputyLeader(Course $course, $studentId = null)
    {
        $this->set($course, CharacteristicId::DEPUTY_LEADER_ID, $studentId);
    }

    public function setBrsmSecretary(Course $course, $studentId = null)
    {
        $this->set($course, CharacteristicId::BRSM_SECRETARY_ID, $studentId);
    }

    public function setUnionOrganizer(Course $course, $studentId = null)
    {
        $this->set($course, CharacteristicId::UNION_ORGANIZER_ID, $studentId);
    }

    protected function set(Course $course, CharacteristicId $characteristicId, $studentId = null)
    {
        $query = $course->characteristics()->where('characteristic_id', $characteristicId->value);

        if ($studentId) {
            $query->updateOrCreate(
                [],
                [
                    'student_id' => $studentId,
                    'characteristic_id' => $characteristicId->value,
                    'course_id' => $course->id,
                ]
            );
        } else {
            $query->delete();
        }
    }
}
