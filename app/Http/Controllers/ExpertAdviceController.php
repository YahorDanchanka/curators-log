<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpertAdviceRequest;
use App\Models\ExpertAdvice;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ExpertAdviceController extends Controller
{
    public function create(Group $group, string $studentNumber)
    {
        Gate::authorize('expertAdvice', Student::class);
        $student = $group->findStudentByNumber($studentNumber);
        return Inertia::render('expert-advice/CreatePage', compact('group', 'studentNumber', 'student'));
    }

    public function store(ExpertAdviceRequest $request, Group $group, string $studentNumber)
    {
        Gate::authorize('expertAdvice', Student::class);
        $student = $group->findStudentByNumber($studentNumber);
        $student->expertAdvice()->create($request->validated());
        return to_route('groups.students.show', ['group' => $group->id, 'student' => $studentNumber]);
    }

    public function edit(Group $group, string $studentNumber, ExpertAdvice $expertAdvice)
    {
        Gate::authorize('expertAdvice', Student::class);
        $student = $group->findStudentByNumber($studentNumber);
        return Inertia::render('expert-advice/EditPage', compact('group', 'studentNumber', 'student', 'expertAdvice'));
    }

    public function update(
        ExpertAdviceRequest $request,
        Group $group,
        string $studentNumber,
        ExpertAdvice $expertAdvice
    ) {
        Gate::authorize('expertAdvice', Student::class);
        $expertAdvice->update($request->validated());
        return to_route('groups.students.show', ['group' => $group->id, 'student' => $studentNumber]);
    }

    public function destroy(Group $group, string $studentNumber, ExpertAdvice $expertAdvice)
    {
        Gate::authorize('expertAdvice', Student::class);
        $expertAdvice->delete();
        return to_route('groups.students.show', ['group' => $group->id, 'student' => $studentNumber]);
    }
}
