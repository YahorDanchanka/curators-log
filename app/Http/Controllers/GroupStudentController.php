<?php

namespace App\Http\Controllers;

use App\Helpers\PhpWordPurifier;
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
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\TemplateProcessor;
use Inertia\Inertia;

class GroupStudentController extends Controller
{
    public function index(Group $group)
    {
        Gate::authorize('viewAny', Group::class);

        $group->append('name');
        $group->load(['students.address', 'students.studyAddress', 'students.expulsion']);

        $group->students->each(function (Student $student) {
            $student->append('age', 'can');
            $student->address?->append('address');
            $student->studyAddress?->append('address');
            $student->passport?->append('passport');
        });

        return Inertia::render('group-student/IndexPage', [
            ...compact('group'),
            'printing' => true,
            'columns' => $this->getColumnAllowList(),
        ]);
    }

    public function create(Group $group)
    {
        Gate::authorize('create', Student::class);
        $group->append('name');
        return Inertia::render('group-student/CreatePage', compact('group'));
    }

    public function store(Group $group, GroupStudentRequest $request, GroupStudentService $groupStudentService)
    {
        Gate::authorize('update', $group);
        $groupStudentService->create($group, $request);
        return to_route('groups.students.index', ['group' => $group->id]);
    }

    public function show(Group $group, string $studentNumber)
    {
        $student = $group->findStudentByNumber($studentNumber);
        Gate::authorize('view', $student);

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
        Gate::authorize('update', $group);
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
        Gate::authorize('update', $group);
        $groupStudentService->update($student, $request);
        return to_route('groups.students.index', ['group' => $group->id]);
    }

    public function destroy(Group $group, Student $student, GroupStudentService $groupStudentService)
    {
        Gate::authorize('delete', $group);
        $groupStudentService->delete($student);
        return to_route('groups.students.index', ['group' => $group->id]);
    }

    public function print(Group $group, string $studentNumber)
    {
        $student = $group->findStudentByNumber($studentNumber);
        Gate::authorize('view', $student);
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

    public function printStudentList(Request $request, Group $group)
    {
        Gate::authorize('viewAny', Group::class);

        $validated = $request->validate([
            'columns' => 'required|array',
            'columns.*' => Rule::in(
                $this->getColumnAllowList()
                    ->pluck('value')
                    ->toArray()
            ),
        ]);

        $columns = $validated['columns'] ?? [];

        $students = $group->students
            ->map(
                fn(Student $student, $index) => PhpWordPurifier::purify(
                    Arr::only(
                        [
                            ...$student->getAttributes(),
                            'initials' => $student->initials,
                            'address' => $student->address?->address,
                            'study_address' => $student->study_address?->address,
                            'number' => $index + 1,
                            'age' => $student->age,
                            'passport' => $student->passport?->passport,
                        ],
                        $validated['columns']
                    )
                )
            )
            ->toArray();

        $phpWord = new PhpWord();
        $fontStyles = ['name' => 'Times New Roman', 'size' => 12];

        $section = $phpWord->addSection(['orientation' => 'landscape']);

        $section->addText(
            "Список учащихся учебной группы {$group->name}",
            [...$fontStyles, 'bold' => true],
            ['alignment' => Jc::CENTER]
        );

        $table = $section->addTable(['width' => 100 * 50, 'unit' => 'pct', 'borderSize' => 6]);
        $table->addRow();

        foreach ($columns as $column) {
            $foundColumn = $this->getColumnAllowList()
                ->where('value', $column)
                ->first();

            if (!$foundColumn) {
                continue;
            }

            $table->addCell()->addText($foundColumn['label'], [...$fontStyles, 'bold' => true]);
        }

        foreach ($students as $student) {
            $table->addRow();

            foreach ($columns as $column) {
                $foundColumn = $this->getColumnAllowList()
                    ->where('value', $column)
                    ->first();

                if (!$foundColumn || !isset($student[$column])) {
                    continue;
                }

                $table->addCell()->addText($student[$column], $fontStyles);
            }
        }

        return response()->streamDownload(
            fn() => $phpWord->save('php://output'),
            "Список учащихся {$group->name}.docx"
        );
    }

    protected function getColumnAllowList(): \Illuminate\Support\Collection
    {
        return collect([
            ['value' => 'number', 'label' => '№'],
            ['value' => 'initials', 'label' => 'ФИО'],
            ['value' => 'sex', 'label' => 'Пол'],
            ['value' => 'birthday', 'label' => 'Дата рождения'],
            ['value' => 'age', 'label' => 'Возраст'],
            ['value' => 'citizenship', 'label' => 'Гражданство'],
            ['value' => 'home_phone', 'label' => 'Домашний телефон'],
            ['value' => 'phone', 'label' => 'Телефон'],
            ['value' => 'educational_institution', 'label' => 'Учреждение образования'],
            ['value' => 'social_conditions', 'label' => 'Социальные условия'],
            ['value' => 'hobbies', 'label' => 'Увлечения'],
            ['value' => 'other_details', 'label' => 'Другая информация'],
            ['value' => 'medical_certificate_date', 'label' => 'Дата справки'],
            ['value' => 'health', 'label' => 'Группа здоровья'],
            ['value' => 'apprenticeship', 'label' => 'Основа'],
            ['value' => 'address', 'label' => 'Домашний адрес'],
            ['value' => 'study_address', 'label' => 'Место проживания в период обучения'],
            ['value' => 'passport', 'label' => 'Паспортные данные'],
        ]);
    }
}
