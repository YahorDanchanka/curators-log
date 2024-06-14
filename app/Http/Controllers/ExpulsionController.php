<?php

namespace App\Http\Controllers;

use App\Helpers\PhpWordPurifier;
use App\Http\Requests\ExpulsionRequest;
use App\Models\Expulsion;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use PhpOffice\PhpWord\TemplateProcessor;

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
        return Inertia::render('expulsion/IndexPage', [...compact('group', 'course'), 'printing' => true]);
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

    public function print(Group $group, string $course_number)
    {
        Gate::authorize('view', $group);

        $course = $group->findCourseByNumber($course_number);
        $templateProcessor = new TemplateProcessor(resource_path('documents/expulsions.docx'));

        $templateProcessor->setValue('course', $course_number);
        $templateProcessor->cloneRowAndSetValues(
            'initials',
            $course->expulsions
                ->map(
                    fn(Expulsion $expulsion) => PhpWordPurifier::purify([
                        'initials' => $expulsion->student->initials,
                        'date' => Carbon::parse($expulsion->date)->format('d.m.Y'),
                        'is_initiator_student' => $expulsion->initiator === 'student' ? '+' : '',
                        'is_initiator_college' => $expulsion->initiator === 'college' ? '+' : '',
                        'reason' => $expulsion->reason,
                    ])
                )
                ->toArray()
        );

        return response()->streamDownload(
            fn() => $templateProcessor->saveAs('php://output'),
            "Отчисления за период обучения {$course->group_name}.docx"
        );
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
