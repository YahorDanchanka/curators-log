<?php

namespace App\Http\Controllers;

use App\Http\Requests\AsocialBehaviorRequest;
use App\Models\AsocialBehavior;
use App\Models\Group;
use Inertia\Inertia;

class AsocialBehaviorController extends Controller
{
    public function create(Group $group, string $studentNumber)
    {
        $student = $group->findStudentByNumber($studentNumber);
        return Inertia::render('asocial-behavior/CreatePage', compact('group', 'studentNumber', 'student'));
    }

    public function store(AsocialBehaviorRequest $request, Group $group, string $studentNumber)
    {
        $student = $group->findStudentByNumber($studentNumber);
        $student->asocialBehavior()->create($request->validated());
        return to_route('groups.students.show', ['group' => $group->id, 'student' => $studentNumber]);
    }

    public function edit(Group $group, string $studentNumber, AsocialBehavior $asocialBehavior)
    {
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
        $asocialBehavior->update($request->validated());
        return to_route('groups.students.show', ['group' => $group->id, 'student' => $studentNumber]);
    }

    public function destroy(Group $group, string $studentNumber, AsocialBehavior $asocialBehavior)
    {
        $asocialBehavior->delete();
        return to_route('groups.students.show', ['group' => $group->id, 'student' => $studentNumber]);
    }
}
