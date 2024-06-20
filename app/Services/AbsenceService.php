<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Database\Eloquent\Builder;

class AbsenceService
{
    public function find(Course $course, string $month): Builder
    {
        return $course
            ->absences()
            ->whereMonth('date', $month)
            ->doesntHave('student.expulsion');
    }
}
