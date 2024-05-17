<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentAchievementRequest;
use App\Models\Group;
use App\Models\Student;
use App\Models\StudentAchievement;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class StudentAchievementController extends Controller
{
    public function create(Group $group, string $studentNumber)
    {
        Gate::authorize('achievements', Student::class);
        $student = $group->findStudentByNumber($studentNumber);
        return Inertia::render('student-achievement/CreatePage', compact('group', 'studentNumber', 'student'));
    }

    public function store(StudentAchievementRequest $request, Group $group, string $studentNumber)
    {
        Gate::authorize('achievements', Student::class);
        $student = $group->findStudentByNumber($studentNumber);
        $student->achievements()->create($request->validated());
        return to_route('groups.students.show', ['group' => $group->id, 'student' => $studentNumber]);
    }

    public function edit(Group $group, string $studentNumber, StudentAchievement $achievement)
    {
        Gate::authorize('achievements', Student::class);
        return Inertia::render('student-achievement/EditPage', compact('group', 'studentNumber', 'achievement'));
    }

    public function update(
        StudentAchievementRequest $request,
        Group $group,
        string $studentNumber,
        StudentAchievement $achievement
    ) {
        Gate::authorize('achievements', Student::class);
        $achievement->update($request->validated());
        return to_route('groups.students.show', ['group' => $group->id, 'student' => $studentNumber]);
    }

    public function destroy(Group $group, string $studentNumber, StudentAchievement $achievement)
    {
        Gate::authorize('achievements', Student::class);
        $achievement->delete();
        return to_route('groups.students.show', ['group' => $group->id, 'student' => $studentNumber]);
    }
}
