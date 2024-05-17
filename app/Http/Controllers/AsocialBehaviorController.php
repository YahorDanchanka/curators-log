<?php

namespace App\Http\Controllers;

use App\Http\Requests\AsocialBehaviorRequest;
use App\Models\AsocialBehavior;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class AsocialBehaviorController extends Controller
{
    public function create(Group $group, string $studentNumber)
    {
        Gate::authorize('asocialBehaviors', Student::class);
        $student = $group->findStudentByNumber($studentNumber);
        return Inertia::render('asocial-behavior/CreatePage', compact('group', 'studentNumber', 'student'));
    }

    public function store(AsocialBehaviorRequest $request, Group $group, string $studentNumber)
    {
        Gate::authorize('asocialBehaviors', Student::class);
        $student = $group->findStudentByNumber($studentNumber);
        $student->asocialBehavior()->create($request->validated());
        return to_route('groups.students.show', ['group' => $group->id, 'student' => $studentNumber]);
    }

    public function edit(Group $group, string $studentNumber, AsocialBehavior $asocialBehavior)
    {
        Gate::authorize('asocialBehaviors', Student::class);
        $student = $group->findStudentByNumber($studentNumber);
        return Inertia::render(
            'asocial-behavior/EditPage',
            compact('group', 'studentNumber', 'student', 'asocialBehavior')
        );
    }

    public function update(
        AsocialBehaviorRequest $request,
        Group $group,
        string $studentNumber,
        AsocialBehavior $asocialBehavior
    ) {
        Gate::authorize('asocialBehaviors', Student::class);
        $asocialBehavior->update($request->validated());
        return to_route('groups.students.show', ['group' => $group->id, 'student' => $studentNumber]);
    }

    public function destroy(Group $group, string $studentNumber, AsocialBehavior $asocialBehavior)
    {
        Gate::authorize('asocialBehaviors', Student::class);
        $asocialBehavior->delete();
        return to_route('groups.students.show', ['group' => $group->id, 'student' => $studentNumber]);
    }
}
