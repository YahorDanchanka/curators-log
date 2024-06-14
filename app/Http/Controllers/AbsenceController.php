<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class AbsenceController extends Controller
{
    public function index(Group $group, string $courseNumber, string $month)
    {
        Gate::authorize('view', $group);

        $course = $group->findCourseByNumber($courseNumber);
        $course->append('group_name');

        $absences = $course
            ->absences()
            ->whereMonth('date', $month)
            ->doesntHave('student.expulsion')
            ->get();

        $group->load(['students' => fn(HasMany $query) => $query->doesntHave('expulsion')]);
        $group->students->each(fn(Student $student) => $student->append('initials'));

        return Inertia::render('absence/IndexPage', [
            ...compact('group', 'course', 'month', 'absences'),
            'saving' => true,
            'printing' => true,
        ]);
    }

    public function sync(Request $request, Group $group, string $courseNumber, string $month)
    {
        Gate::authorize('update', $group);
        $course = $group->findCourseByNumber($courseNumber);

        $validated = $request->validate([
            'rows' => 'array',
            'rows.*.date' => 'required|date',
            'rows.*.reasonable_count' => 'required|numeric',
            'rows.*.unreasonable_count' => 'required|numeric',
            'rows.*.student_id' => 'required|numeric|exists:App\Models\Student,id',
        ]);

        DB::transaction(function () use ($course, $validated, $month) {
            $course
                ->absences()
                ->whereMonth('date', $month)
                ->doesntHave('student.expulsion')
                ->delete();
            Absence::insert($validated['rows'] ?? []);
        });

        return to_route('groups.courses.absences.index', [
            'group' => $group->id,
            'course_number' => $courseNumber,
            'month' => $month,
        ]);
    }
}
