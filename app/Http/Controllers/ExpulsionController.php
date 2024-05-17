<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpulsionRequest;
use App\Models\Expulsion;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Inertia\Inertia;

class ExpulsionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Group::class, 'group');
    }

    public function index(Group $group, string $course_number)
    {
        $course = $this->getFormData($group, $course_number)['course'];
        $course->append('group_name');
        return Inertia::render('expulsion/IndexPage', compact('group', 'course'));
    }

    public function create(Group $group, string $course_number)
    {
        $course = $this->getFormData($group, $course_number)['course'];
        $course->append('group_name');
        return Inertia::render('expulsion/CreatePage', compact('group', 'course'));
    }

    public function store(ExpulsionRequest $request, Group $group, string $course_number)
    {
        $course = $group->findCourseByNumber($course_number);
        $validated = $request->validated();
        $course->expulsions()->create($validated);

        return to_route('groups.courses.expulsions.index', [
            'group' => $group->id,
            'course' => $course->number,
        ]);
    }

    public function edit(Group $group, string $course_number, Expulsion $expulsion)
    {
        $course = $this->getFormData($group, $course_number)['course'];
        $course->append('group_name');
        return Inertia::render('expulsion/EditPage', compact('group', 'course', 'expulsion'));
    }

    public function update(ExpulsionRequest $request, Group $group, string $course_number, Expulsion $expulsion)
    {
        $course = $group->findCourseByNumber($course_number);
        $expulsion->update($request->validated());
        return to_route('groups.courses.expulsions.index', [
            'group' => $group->id,
            'course' => $course->number,
        ]);
    }

    public function destroy(Group $group, string $course_number, Expulsion $expulsion)
    {
        $course = $group->findCourseByNumber($course_number);
        $expulsion->delete();
        return to_route('groups.courses.expulsions.index', [
            'group' => $group->id,
            'course' => $course->number,
        ]);
    }

    protected function getFormData(Group $group, string|int $course_number): array
    {
        $course = $group->findCourseByNumber($course_number);

        $group->load([
            'students' => fn(HasMany $query) => $query->select(['id', 'surname', 'name', 'patronymic', 'group_id']),
        ]);

        $group->students->each(fn(Student $student) => $student->append('initials'));

        return compact('course');
    }
}
