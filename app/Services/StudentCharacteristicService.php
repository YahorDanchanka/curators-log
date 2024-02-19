<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

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

    public function copyCharacteristicsByCourse(Course $from, Course $to, callable $function)
    {
        $query = $function(
            DB::table('characteristic_student')
                ->join('characteristics', 'characteristics.id', '=', 'characteristic_student.characteristic_id')
                ->select(DB::raw("student_id, characteristic_id, {$to->id}"))
                ->where('course_id', $from->id)
        );

        DB::table('characteristic_student')->insertUsing(['student_id', 'characteristic_id', 'course_id'], $query);
    }
}
