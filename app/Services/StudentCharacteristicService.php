<?php

namespace App\Services;

use App\Models\Student;

class StudentCharacteristicService
{
    public function attach(Student $student, $characteristicId, $courseId): bool
    {
        if ($this->isAttached($student, $characteristicId, $courseId)) {
            return false;
        }

        $student->characteristics()->attach($characteristicId, ['course_id' => $courseId]);
        return true;
    }

    public function detach(Student $student, $characteristicId, $courseId): bool
    {
        if (!$this->isAttached($student, $characteristicId, $courseId)) {
            return false;
        }

        $student
            ->characteristics()
            ->wherePivot('course_id', $courseId)
            ->detach($characteristicId);

        return true;
    }

    public function isAttached(Student $student, $characteristicId, $courseId): bool
    {
        return $student
            ->characteristics()
            ->where('characteristic_id', $characteristicId)
            ->wherePivot('course_id', $courseId)
            ->exists();
    }
}
