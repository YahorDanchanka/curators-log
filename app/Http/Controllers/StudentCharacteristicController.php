<?php

namespace App\Http\Controllers;

use App\Models\Characteristic;
use App\Models\Course;
use App\Models\Student;
use App\Services\StudentCharacteristicService;

class StudentCharacteristicController extends Controller
{
    public function attach(
        Course $course,
        Student $student,
        Characteristic $characteristic,
        StudentCharacteristicService $studentCharacteristicService
    ) {
        $studentCharacteristicService->attach($student, $characteristic->id, $course->id);
    }

    public function detach(
        Course $course,
        Student $student,
        Characteristic $characteristic,
        StudentCharacteristicService $studentCharacteristicService
    ) {
        $studentCharacteristicService->detach($student, $characteristic->id, $course->id);
    }
}
