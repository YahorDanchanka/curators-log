<?php

namespace App\Http\Controllers;

use App\Exports\SocioPedagogicalCharacteristicExport;
use App\Http\Requests\SocioPedagogicalCharacteristicRequest;
use App\Models\Characteristic;
use App\Models\CharacteristicStudent;
use App\Models\Group;
use App\Models\Student;
use App\Services\Analytics\AnalyticsService;
use App\Services\Analytics\Strategies\AdultStudentsStrategy;
use App\Services\Analytics\Strategies\BrsmStudentsStrategy;
use App\Services\Analytics\Strategies\BudgetStudentsStrategy;
use App\Services\Analytics\Strategies\DisabledStudentsStrategy;
use App\Services\Analytics\Strategies\DormitoryStudentsStrategy;
use App\Services\Analytics\Strategies\FemaleStudentsStrategy;
use App\Services\Analytics\Strategies\ForeignStudentsStrategy;
use App\Services\Analytics\Strategies\HobbyStudentsStrategy;
use App\Services\Analytics\Strategies\LargeFamiliesStrategy;
use App\Services\Analytics\Strategies\LeadershipStudentsStrategy;
use App\Services\Analytics\Strategies\MaleStudentsStrategy;
use App\Services\Analytics\Strategies\MinorStudentsStrategy;
use App\Services\Analytics\Strategies\NonresidentStudentsStrategy;
use App\Services\Analytics\Strategies\OffBudgetStudentsStrategy;
use App\Services\Analytics\Strategies\OneParentFamiliesStrategy;
use App\Services\Analytics\Strategies\OPFRStudentsStrategy;
use App\Services\Analytics\Strategies\OrphanAdultStudentsStrategy;
use App\Services\Analytics\Strategies\OrphanStudentsStrategy;
use App\Services\Analytics\Strategies\SdsFamiliesStrategy;
use App\Services\Analytics\Strategies\StudyUnionStudentsStrategy;
use App\Services\Analytics\Strategies\TotalStudentsStrategy;
use App\Services\Analytics\Strategies\WithoutParentalSupportAdultStudentsStrategy;
use App\Services\Analytics\Strategies\WithoutParentalSupportStudentsStrategy;
use App\Services\StudentCharacteristicService;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\TemplateProcessor;

class SocioPedagogicalCharacteristicController extends Controller
{
    public function index(Group $group, string $course_number)
    {
        $course = $group->findCourseByNumber($course_number);
        $course->append('group_name');

        $group->load([
            'students' => fn(HasMany $query) => $query
                ->select([
                    'id',
                    'surname',
                    'name',
                    'patronymic',
                    'birthday',
                    'group_id',
                    'address_id',
                    'study_address_id',
                ])
                ->with([
                    'characteristics' => fn(BelongsToMany $query) => $query
                        ->where('type', 'socio-pedagogical')
                        ->where('course_id', $course->id),
                    'address',
                    'studyAddress',
                    'expulsion',
                ])
                ->doesntHave('expulsion'),
        ]);

        $group->students->each(fn(Student $student) => $student->append(['initials', 'is_nonresident', 'is_dorm']));
        $characteristics = $this->getCharacteristics();

        return Inertia::render('socio-pedagogical-characteristic/IndexPage', [
            ...compact('group', 'course', 'characteristics'),
            'saving' => true,
            'printing' => true,
            'loading' => $course->number > 1,
        ]);
    }

    public function sync(SocioPedagogicalCharacteristicRequest $request, Group $group, string $course_number)
    {
        $course = $group->findCourseByNumber($course_number);
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $group, $course) {
            /** Remove earlier characteristics */
            CharacteristicStudent::where('course_id', $course->id)
                ->whereRelation('characteristic', 'type', 'socio-pedagogical')
                ->whereRelation('student', 'group_id', $group->id)
                ->delete();
            /** Push new data */
            CharacteristicStudent::insert(
                array_map(fn($row) => [...$row, 'course_id' => $course->id], $validated['data'])
            );
        });

        return to_route('groups.courses.socio-pedagogical-characteristic.index', [
            'group' => $group->id,
            'course_number' => $course->number,
        ]);
    }

    public function print(Group $group, string $course_number)
    {
        $course = $group->findCourseByNumber($course_number);
        return Excel::download(
            new SocioPedagogicalCharacteristicExport(
                $group
                    ->students()
                    ->doesntHave('expulsion')
                    ->with([
                        'characteristics' => fn(BelongsToMany $query) => $query
                            ->where('type', 'socio-pedagogical')
                            ->where('course_id', $course->id),
                    ])
                    ->get(),
                $this->getCharacteristics()
            ),
            "Социально-педагогическая характеристика учебной группы № {$course->groupName}.xlsx"
        );
    }

    public function printWord(Group $group, string $course_number, AnalyticsService $analyticsService)
    {
        $course = $group->findCourseByNumber($course_number);
        $templateProcessor = new TemplateProcessor(resource_path('documents/social-pedagogical-characteristics.docx'));

        $hobbyStudentCount = $analyticsService->count(new HobbyStudentsStrategy(), $course);

        $templateProcessor->setValues([
            'group' => $course->groupName,
            'specialty' => $group->specialty->name,
            'fillDate' => date('d.m.Y') . ' г.',
            'initials' => $course->curator->initials,
            '0' => $analyticsService->count(new TotalStudentsStrategy(), $course),
            '1' => $analyticsService->count(new BudgetStudentsStrategy(), $course),
            '2' => $analyticsService->count(new OffBudgetStudentsStrategy(), $course),
            '3' => $analyticsService->count(new MaleStudentsStrategy(), $course),
            '4' => $analyticsService->count(new FemaleStudentsStrategy(), $course),
            '5' => $analyticsService->count(new MinorStudentsStrategy(), $course),
            '6' => $analyticsService->count(new AdultStudentsStrategy(), $course),
            '7' => $analyticsService->count(new NonresidentStudentsStrategy(), $course),
            '8' => $analyticsService->count(new DormitoryStudentsStrategy(), $course),
            '9' => $analyticsService->count(new ForeignStudentsStrategy(), $course),
            '10' => $analyticsService->count(new OneParentFamiliesStrategy(), $course),
            '11' => $analyticsService->count(new LargeFamiliesStrategy(), $course),
            '12' => '',
            '13' => '',
            '14' => '',
            '15' => '',
            '16' => '',
            '17' => '',
            '18' => '',
            '19' => '',
            '20' => '',
            '21' => $analyticsService->count(new SdsFamiliesStrategy(), $course),
            '22' => '',
            '23' => $analyticsService->count(new OrphanStudentsStrategy(), $course),
            '24' => $analyticsService->count(new OrphanAdultStudentsStrategy(), $course),
            '25' => $analyticsService->count(new WithoutParentalSupportStudentsStrategy(), $course),
            '26' => $analyticsService->count(new WithoutParentalSupportAdultStudentsStrategy(), $course),
            '27' => $analyticsService->count(new BrsmStudentsStrategy(), $course),
            '28' => $analyticsService->count(new StudyUnionStudentsStrategy(), $course),
            '29' => '',
            '30' => $analyticsService->count(new DisabledStudentsStrategy(), $course),
            '31' => $analyticsService->count(new OPFRStudentsStrategy(), $course),
            '32' => $hobbyStudentCount,
            '33' => $group->students()->count() - $hobbyStudentCount,
        ]);

        return response()->streamDownload(
            fn() => $templateProcessor->saveAs('php://output'),
            "Социально-педагогическая характеристика {$course->groupName}.docx"
        );
    }

    public function loadPrevCourse(
        Group $group,
        string $courseNumber,
        StudentCharacteristicService $studentCharacteristicService
    ) {
        $course = $group->findCourseByNumber($courseNumber);
        $prevCourse = $group->findPrevCourse($courseNumber);

        try {
            $studentCharacteristicService->copyCharacteristicsByCourse(
                $prevCourse,
                $course,
                fn(Builder $query) => $query->where('type', 'socio-pedagogical')
            );
        } catch (UniqueConstraintViolationException $exception) {
        }

        return to_route('groups.courses.socio-pedagogical-characteristic.index', [
            'group' => $group->id,
            'course_number' => $course->number,
        ]);
    }

    protected function getCharacteristics(): Collection
    {
        return Characteristic::select(['id', 'name'])
            ->where('type', 'socio-pedagogical')
            ->get();
    }
}
