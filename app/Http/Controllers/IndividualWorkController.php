<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndividualWorkRequest;
use App\Models\Group;
use App\Models\IndividualWork;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class IndividualWorkController extends Controller
{
    public function create(Request $request, Group $group, string $studentNumber)
    {
        Gate::authorize('individualWorks', Student::class);
        $type = $request->get('type', 'relative');
        $student = $group->findStudentByNumber($studentNumber);
        return Inertia::render('individual-work/CreatePage', compact('group', 'studentNumber', 'student', 'type'));
    }

    public function store(IndividualWorkRequest $request, Group $group, string $studentNumber)
    {
        Gate::authorize('individualWorks', Student::class);
        $student = $group->findStudentByNumber($studentNumber);
        $student->individualWork()->create($request->validated());
        return to_route('groups.students.show', ['group' => $group->id, 'student' => $studentNumber]);
    }

    public function edit(Group $group, string $studentNumber, IndividualWork $individualWork)
    {
        Gate::authorize('individualWorks', Student::class);
        $student = $group->findStudentByNumber($studentNumber);
        return Inertia::render(
            'individual-work/EditPage',
            compact('group', 'studentNumber', 'student', 'individualWork')
        );
    }

    public function update(
        IndividualWorkRequest $request,
        Group $group,
        string $studentNumber,
        IndividualWork $individualWork
    ) {
        Gate::authorize('individualWorks', Student::class);
        $individualWork->update($request->validated());
        return to_route('groups.students.show', ['group' => $group->id, 'student' => $studentNumber]);
    }

    public function destroy(Group $group, string $studentNumber, IndividualWork $individualWork)
    {
        Gate::authorize('individualWorks', Student::class);
        $individualWork->delete();
        return to_route('groups.students.show', ['group' => $group->id, 'student' => $studentNumber]);
    }
}
