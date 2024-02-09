<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupStudentRequest;
use App\Models\Group;
use App\Models\Relative;
use App\Models\Student;
use App\Services\GroupStudentService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Inertia\Inertia;

class GroupStudentController extends Controller
{
    public function index(Group $group)
    {
        $group->append('name');
        $group->load(['students.address', 'students.studyAddress']);

        $group->students->each(function (Student $student) {
            $student->append('age');
            $student->address?->append('address');
            $student->studyAddress?->append('address');
        });

        return Inertia::render('group-student/IndexPage', compact('group'));
    }

    public function create(Group $group)
    {
        $group->append('name');
        return Inertia::render('group-student/CreatePage', compact('group'));
    }

    public function store(Group $group, GroupStudentRequest $request, GroupStudentService $groupStudentService)
    {
        $groupStudentService->create($group, $request);
        return to_route('groups.students.index', ['group' => $group->id]);
    }

    public function show(Group $group, string $studentNumber)
    {
        $student = $group->findStudentByNumber($studentNumber);
        $student->load([
            'address',
            'studyAddress',
            'passport',
            'relatives.address',
            'achievements' => fn(HasMany $query) => $query->orderBy('date'),
            'asocialBehavior' => fn(HasMany $query) => $query->orderBy('date'),
            'expertAdvice',
            'individualWork',
        ]);
        $student->append([
            'father',
            'mother',
            'minor_relatives',
            'student_individual_work',
            'relative_individual_work',
        ]);

        $student->relatives->each(function (Relative $relative) {
            $relative->append('full_name');
            $relative->address?->append('address');
        });

        $student->address?->append('address');
        $student->studyAddress?->append('address');
        $student->passport?->append('passport');
        return Inertia::render('group-student/ShowPage', compact('group', 'student', 'studentNumber'));
    }

    public function edit(Group $group, string $studentNumber)
    {
        $student = $group->findStudentByNumber($studentNumber);
        $student->load(['address', 'studyAddress', 'passport']);
        $student->append('initials');
        return Inertia::render('group-student/EditPage', compact('group', 'studentNumber', 'student'));
    }

    public function update(
        Group $group,
        Student $student,
        GroupStudentRequest $request,
        GroupStudentService $groupStudentService
    ) {
        $groupStudentService->update($student, $request);
        return to_route('groups.students.index', ['group' => $group->id]);
    }

    public function destroy(Group $group, Student $student, GroupStudentService $groupStudentService)
    {
        $groupStudentService->delete($student);
        return to_route('groups.students.index', ['group' => $group->id]);
    }
}
