<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupStudentRequest;
use App\Models\AsocialBehavior;
use App\Models\ExpertAdvice;
use App\Models\Group;
use App\Models\IndividualWork;
use App\Models\Relative;
use App\Models\Student;
use App\Models\StudentAchievement;
use App\Services\GroupStudentService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Inertia\Inertia;

class GroupStudentController extends Controller
{
    public function index(Group $group)
    {
        $group->append('name');
        $group->load(['students.address', 'students.studyAddress', 'students.expulsion']);

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
            'individualWork' => fn(HasMany $query) => $query->orderBy('date'),
        ]);

        $group->append('name');
        $student->append([
            'father',
            'mother',
            'minor_relatives',
            'student_individual_work',
            'relative_individual_work',
            'full_name',
        ]);

        $student->relatives->each(function (Relative $relative) {
            $relative->append('full_name');
            $relative->address?->append('address');
        });

        $student->address?->append('address');
        $student->studyAddress?->append('address');
        $student->passport?->append('passport');
        return Inertia::render('group-student/ShowPage', [
            ...compact('group', 'student', 'studentNumber'),
            'printing' => true,
        ]);
    }

    public function edit(Group $group, string $studentNumber)
    {
        $student = $group->findStudentByNumber($studentNumber);
        $student->load(['address', 'studyAddress', 'passport']);
        $group->append('name');
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

    public function print(Group $group, string $studentNumber)
    {
        $student = $group->findStudentByNumber($studentNumber);
        $templateProcessor = new TemplateProcessor(resource_path('documents/student-card.docx'));

        $templateProcessor->setValues([
            ...$student->getAttributes(),
            'birthday' => $student->birthday ? Carbon::parse($student->birthday)->format('d.m.Y') : '',
            'passport' => $student->passport?->passport,
            'address' => $student->address?->address,
            'study_address' => $student->study_address?->address,
            'mother' => $student->mother?->full_name,
            'father' => $student->father?->full_name,
            'family_other' => $student->minor_relatives
                ->map(
                    fn(Relative $relative) => implode(', ', [
                        $relative->full_name,
                        Carbon::parse($relative->birthday)->format('d.m.Y'),
                        $relative->educational_institution,
                    ])
                )
                ->join(PHP_EOL),
        ]);

        $templateProcessor->setImageValue(
            'photo',
            $student->image_url
                ? storage_path('app/public/' . $student->getRawOriginal('image_url'))
                : public_path('images/avatar.png')
        );

        $templateProcessor->cloneRowAndSetValues(
            'encouragement_date',
            $student->achievements
                ->map(
                    fn(StudentAchievement $achievement) => [
                        'encouragement_date' => Carbon::parse($achievement->date)->format('d.m.Y'),
                        'encouragement_reason' => $achievement->reason,
                        'encouragement_form' => $achievement->prize,
                    ]
                )
                ->values()
                ->toArray()
        );

        $templateProcessor->cloneRowAndSetValues(
            'ab_date',
            $student->asocialBehavior
                ->map(
                    fn(AsocialBehavior $associateBehavior) => [
                        'ab_date' => Carbon::parse($associateBehavior->date)->format('d.m.Y'),
                        'ab_action' => $associateBehavior->action,
                        'ab_sanctions' => $associateBehavior->sanctions,
                        'ab_result' => $associateBehavior->result,
                    ]
                )
                ->values()
                ->toArray()
        );

        $templateProcessor->cloneRowAndSetValues(
            'ea_advice',
            $student->expertAdvice
                ->map(
                    fn(ExpertAdvice $expertAdvice) => [
                        'ea_advice' => $expertAdvice->content,
                        'ea_result' => $expertAdvice->result,
                    ]
                )
                ->values()
                ->toArray()
        );

        $templateProcessor->cloneRowAndSetValues(
            'iw_date',
            $student->individualWork
                ->where('type', 'relative')
                ->map(
                    fn(IndividualWork $individualWork) => [
                        'iw_date' => Carbon::parse($individualWork->date)->format('d.m.Y'),
                        'iw_content' => $individualWork->content,
                        'iw_result' => $individualWork->result,
                    ]
                )
                ->values()
                ->toArray()
        );

        $templateProcessor->cloneRowAndSetValues(
            'iws_date',
            $student->individualWork
                ->where('type', 'student')
                ->map(
                    fn(IndividualWork $individualWork) => [
                        'iws_date' => Carbon::parse($individualWork->date)->format('d.m.Y'),
                        'iws_content' => $individualWork->content,
                        'iws_result' => $individualWork->result,
                    ]
                )
                ->values()
                ->toArray()
        );

        return response()->streamDownload(
            fn() => $templateProcessor->saveAs('php://output'),
            "Карта персонифицированного учета {$student->initials}.docx"
        );
    }
}
