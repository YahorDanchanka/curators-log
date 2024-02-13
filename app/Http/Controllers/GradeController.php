<?php

namespace App\Http\Controllers;

use App\Classes\GradeCalculator;
use App\Models\GradeReport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GradeController extends Controller
{
    public function sync(Request $request, GradeReport $gradeReport)
    {
        $validated = $request->validate([
            'subjects' => 'array',
            'subjects.*.name' => 'required',
            'subjects.*.rows.*.grades' => 'required|array',
            'subjects.*.rows.*.grades.*.type' => ['required', Rule::in('default', 'primary', 'course')],
            'subjects.*.rows.*.grades.*.value' => 'nullable',
        ]);

        if ($gradeReport->grade) {
            $gradeReport->grade()->update(['body' => json_encode($validated)]);
        } else {
            $gradeReport->grade()->create(['body' => json_encode($validated)]);
        }

        return response()->json(200);
    }

    public function summary(GradeReport $gradeReport)
    {
        $body = $gradeReport->grade?->body;
        $calculator = $body ? new GradeCalculator(json_decode($body, true)['subjects'] ?? []) : [];
        $subjects = [];

        foreach ($calculator->subjects as $subject) {
            $newSubject = ['name' => $subject->name, 'rows' => []];

            foreach ($calculator->getStudentIds() as $studentId) {
                $newSubject['rows'][$studentId] = $subject->getStudentGrade($studentId);
            }

            $subjects[] = $newSubject;
        }

        return response()->json($subjects);
    }
}
