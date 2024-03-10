<?php

namespace App\Http\Controllers;

use App\Enums\CharacteristicId;
use App\Http\Requests\StudentEmploymentRequest;
use App\Models\Group;
use App\Models\Student;
use App\Repositories\StudentRepository;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PhpOffice\PhpWord\TemplateProcessor;

class StudentEmploymentController extends Controller
{
    public function index(Group $group, string $course_number)
    {
        $course = $group->findCourseByNumber($course_number);
        $course->append('group_name');

        $group->load([
            'students' => fn(HasMany $query) => $query->select('id', 'surname', 'name', 'patronymic', 'group_id'),
            'students.characteristics' => fn(BelongsToMany $query) => $query
                ->whereIn('characteristics.id', [
                    CharacteristicId::BRSM_ID,
                    CharacteristicId::LEADER_ID,
                    CharacteristicId::DEPUTY_LEADER_ID,
                    CharacteristicId::BRSM_SECRETARY_ID,
                    CharacteristicId::UNION_ORGANIZER_ID,
                    CharacteristicId::EDUCATIONAL_SECTOR_ID,
                    CharacteristicId::INFORMATION_IDEOLOGICAL_SECTOR_ID,
                    CharacteristicId::SPORT_SECTOR_ID,
                    CharacteristicId::LABOR_SECTOR_ID,
                    CharacteristicId::CULTURAL_MASS_SECTOR_ID,
                    CharacteristicId::LAW_SECTOR_ID,
                    CharacteristicId::EDITORIAL_SECTOR_ID,
                    CharacteristicId::GROUP_UNION_MEMBER_ID
                ])
                ->wherePivot('course_id', $course->id),
            'students.employments' => fn(HasMany $query) => $query->where('course_id', $course->id),
        ]);

        $group->students->each(fn(Student $student) => $student->append('initials', 'full_name'));

        return Inertia::render('student-employment/IndexPage', [
            ...compact('group', 'course'),
            'saving' => true,
            'printing' => true,
        ]);
    }

    public function sync(StudentEmploymentRequest $request, Group $group, string $course_number)
    {
        $course = $group->findCourseByNumber($course_number);
        $validated = $request->validated();

        DB::transaction(function () use ($course, $validated) {
            $course->studentEmployment()->delete();

            foreach ($validated['data'] as $row) {
                if (!($row['first_semester'] ?? null) && !($row['second_semester'] ?? null)) {
                    continue;
                }

                $course->studentEmployment()->create($row);
            }
        });

        return to_route('groups.courses.student-employment.sync', [
            'group' => $group->id,
            'course_number' => $course->number,
        ]);
    }

    public function print(Group $group, string $course_number)
    {
        $course = $group->findCourseByNumber($course_number);

        $group->load([
            'students' => fn(HasMany $query) => $query->select('id', 'surname', 'name', 'patronymic', 'group_id'),
            'students.characteristics' => fn(BelongsToMany $query) => $query
                ->whereIn('characteristics.id', [
                    CharacteristicId::BRSM_ID,
                    CharacteristicId::LEADER_ID,
                    CharacteristicId::DEPUTY_LEADER_ID,
                    CharacteristicId::BRSM_SECRETARY_ID,
                    CharacteristicId::UNION_ORGANIZER_ID,
                    CharacteristicId::EDUCATIONAL_SECTOR_ID,
                    CharacteristicId::INFORMATION_IDEOLOGICAL_SECTOR_ID,
                    CharacteristicId::SPORT_SECTOR_ID,
                    CharacteristicId::LABOR_SECTOR_ID,
                    CharacteristicId::CULTURAL_MASS_SECTOR_ID,
                    CharacteristicId::LAW_SECTOR_ID,
                    CharacteristicId::EDITORIAL_SECTOR_ID,
                ])
                ->wherePivot('course_id', $course_number),
            'students.employments' => fn(HasMany $query) => $query->where('course_id', $course->id),
        ]);

        $studentRepository = new StudentRepository($group->students);
        $brsmStudents = $studentRepository->getBrsmStudents();
        $leadershipStudents = $studentRepository->getLeadershipStudents();

        $studentEmploymentRows = $group->students
            ->map(fn($student) => $student->employments->values()->get(0))
            ->filter();

        $tableData = collect([]);

        for ($i = 0; $i < max($brsmStudents->count(), $leadershipStudents->count()); $i++) {
            $brsmStudent = $brsmStudents->values()->get($i);
            $leadershipStudent = $leadershipStudents->values()->get($i);
            $studentEmploymentRow = $leadershipStudent?->id
                ? $studentEmploymentRows->where('student_id', $leadershipStudent->id)->first()
                : null;

            $tableData->add([
                'i' => $i + 1,
                'initials1' => $brsmStudent?->initials ?? '',
                'initials2' => $leadershipStudent?->initials ?? '',
                'employment1' => $studentEmploymentRow?->first_semester,
                'employment2' => $studentEmploymentRow?->second_semester,
            ]);
        }

        $templateProcessor = new TemplateProcessor(resource_path('documents/student-employment.docx'));

        $templateProcessor->setValues([
            'course' => $course_number,
            'l1' => $brsmStudents->count() . ' ',
            'l2' => $leadershipStudents->count() . ' ',
            'l3' => $tableData->whereNotNull('employment1')->count() . ' ',
            'l4' => $tableData->whereNotNull('employment2')->count() . ' ',
        ]);

        $templateProcessor->cloneRowAndSetValues('i', $tableData->values()->toArray());

        return response()->streamDownload(
            fn() => $templateProcessor->saveAs('php://output'),
            'Занятось учащихся общественно полезной деятельностью.docx'
        );
    }
}
