<?php

namespace App\Http\Controllers;

use App\Classes\GradeCalculator;
use App\Http\Requests\GradeReportRequest;
use App\Models\Expulsion;
use App\Models\GradeReport;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GradeReportController extends Controller
{
    public function index(Group $group, string $courseNumber)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $course->append('group_name');

        $course->load([
            'gradeReports' => fn($query) => $query->orderBy('created_at', 'desc')->orderBy('id', 'desc'),
        ]);

        return Inertia::render('grade-report/IndexPage', compact('group', 'course'));
    }

    public function create(Group $group, string $courseNumber)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $course->append('group_name');
        return Inertia::render('grade-report/CreatePage', compact('group', 'course'));
    }

    public function store(GradeReportRequest $request, Group $group, string $courseNumber)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $course->gradeReports()->create($request->validated());
        return to_route('groups.courses.grade-reports.index', ['group' => $group->id, 'course' => $courseNumber]);
    }

    public function show(Group $group, string $courseNumber, GradeReport $gradeReport)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $course->append('group_name');

        $course->load([
            'gradeReports' => fn($query) => $query
                ->select(['id', 'name', 'course_id'])
                ->whereNot('id', $gradeReport->id)
                ->get(),
        ]);

        $group->load(['students' => fn(HasMany $query) => $query->doesntHave('expulsion')]);
        $gradeReport->load('grade');
        $group->students->each(fn(Student $student) => $student->append('initials'));

        return Inertia::render('grade-report/ShowPage', [
            ...compact('group', 'course', 'gradeReport'),
            'printing' => true,
        ]);
    }

    public function edit(Group $group, string $courseNumber, GradeReport $gradeReport)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $course->append('group_name');
        return Inertia::render('grade-report/EditPage', compact('group', 'course', 'gradeReport'));
    }

    public function update(GradeReportRequest $request, Group $group, string $courseNumber, GradeReport $gradeReport)
    {
        $gradeReport->update($request->validated());
        return to_route('groups.courses.grade-reports.index', ['group' => $group->id, 'course' => $courseNumber]);
    }

    public function destroy(Group $group, string $courseNumber, GradeReport $gradeReport)
    {
        $gradeReport->delete();
        return to_route('groups.courses.grade-reports.index', ['group' => $group->id, 'course' => $courseNumber]);
    }

    public function print(Group $group, string $courseNumber, GradeReport $gradeReport)
    {
        $course = $group->findCourseByNumber($courseNumber);

        if (!$gradeReport->grade) {
            abort(404);
        }

        $expulsionStudentIds = Expulsion::select('student_id')
            ->get()
            ->pluck('student_id');

        $body = json_decode($gradeReport->grade->body, true)['subjects'] ?? [];

        foreach ($body as $bodyIndex => $subject) {
            $rows = $subject['rows'] ?? [];

            foreach ($rows as $studentId => $row) {
                if ($expulsionStudentIds->contains($studentId) && isset($body[$bodyIndex]['rows'][$studentId])) {
                    unset($body[$bodyIndex]['rows'][$studentId]);
                }
            }
        }

        $calculator = new GradeCalculator($body);

        $students = Student::whereIn('id', $calculator->getStudentIds())
            ->select(['id', 'surname', 'name', 'patronymic'])
            ->orderBy('surname')
            ->orderBy('name')
            ->orderBy('patronymic')
            ->doesntHave('expulsion')
            ->get();

        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load(resource_path('documents/grade-report.xlsx'));

        $subjectCellStyles = $spreadsheet
            ->getActiveSheet()
            ->getStyle('C3')
            ->exportArray();

        $gradeCellStyles = $spreadsheet
            ->getActiveSheet()
            ->getStyle('C4')
            ->exportArray();

        /** Set info */
        $headerValue = $spreadsheet
            ->getActiveSheet()
            ->getCell('A1')
            ->getValue();

        $headerValue = str_replace(
            '{{time-range}}',
            (new Carbon($course->start_education))->year . '-' . (new Carbon($course->end_education))->year,
            $headerValue
        );

        $headerValue = str_replace('{{group}}', $course->groupName, $headerValue);

        $spreadsheet
            ->getActiveSheet()
            ->getCell('A1')
            ->setValue($headerValue);

        $curatorValue = $spreadsheet
            ->getActiveSheet()
            ->getCell('A7')
            ->getCalculatedValue();

        $spreadsheet
            ->getActiveSheet()
            ->getCell('A7')
            ->setValue(str_replace('{{curator}}', $course->curator->initials, $curatorValue));

        $spreadsheet
            ->getActiveSheet()
            ->getCell('D5')
            ->setValue($calculator->getAvgGrade() !== false ? $calculator->getAvgGrade() : '');

        /** Insert subjects and grade cell for every subject */
        foreach ($calculator->subjects as $subjectIndex => $subject) {
            $gradeCount = $subject->getColumnCount();
            /** If subject is last decrease a 1 because we have a demo column in template */
            $isSubjectLast = $subjectIndex === count($body) - 1;
            $columnCountToAdd = $isSubjectLast ? $gradeCount - 1 : $gradeCount;

            if ($columnCountToAdd > 0) {
                $spreadsheet->getActiveSheet()->insertNewColumnBeforeByIndex(3, $columnCountToAdd);
            }
        }

        /** Insert grade columns for every subject */
        $column = 3;
        foreach ($calculator->subjects as $subjectIndex => $subject) {
            $spreadsheet->getActiveSheet()->setCellValue([$column, 3], $subject->name);

            $gradeCount = $subject->getColumnCount();
            /** If subject is last decrease a 1 because we have a demo column in template */
            $isSubjectLast = $subjectIndex === count($body) - 1;
            $columnCountToAdd = $isSubjectLast ? $gradeCount - 1 : $gradeCount;

            if ($columnCountToAdd <= 0) {
                continue;
            }

            $spreadsheet
                ->getActiveSheet()
                ->mergeCells([$column, 3, $column + $gradeCount - 1, 3], Worksheet::MERGE_CELL_CONTENT_MERGE);

            $spreadsheet
                ->getActiveSheet()
                ->getStyle([$column, 3, $column + $gradeCount - 1, 3])
                ->applyFromArray($subjectCellStyles);

            $column += $gradeCount;
        }

        /** Merge header column */
        if ($column - 1 >= 3) {
            $spreadsheet->getActiveSheet()->mergeCells([3, 2, $column - 1, 2], Worksheet::MERGE_CELL_CONTENT_MERGE);
        }

        /** Insert students */
        $baseRow = 5;
        $students->each(function (Student $student, $rowIndex) use (
            $body,
            $spreadsheet,
            $baseRow,
            $gradeCellStyles,
            $calculator
        ) {
            $row = $baseRow + $rowIndex;
            $spreadsheet->getActiveSheet()->insertNewRowBefore($row);
            $spreadsheet
                ->getActiveSheet()
                ->setCellValue('A' . $row, $rowIndex + 1)
                ->setCellValue('B' . $row, $student->initials);

            $studentGrades = $calculator->getStudentGrades($student->id);

            foreach ($studentGrades as $gradeIndex => $grade) {
                $spreadsheet->getActiveSheet()->setCellValue([$gradeIndex + 3, $row], $grade->value);

                $spreadsheet
                    ->getActiveSheet()
                    ->getStyle([$gradeIndex + 3, $row])
                    ->applyFromArray(
                        $grade->type !== 'default'
                            ? [
                                ...$gradeCellStyles,
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => [
                                        'rgb' => $grade->type === 'course' ? 'fff176' : 'ff8a65',
                                    ],
                                ],
                            ]
                            : $gradeCellStyles
                    );
            }

            $spreadsheet
                ->getActiveSheet()
                ->setCellValue(
                    [count($studentGrades) + 3, $row],
                    $calculator->getStudentGrade($student->id) !== false
                        ? $calculator->getStudentGrade($student->id)
                        : ''
                );
        });

        /** Remove a demo row */
        if ($students->count() > 0) {
            $spreadsheet->getActiveSheet()->removeRow($baseRow - 1);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $gradeReport->name . '.xlsx');
    }
}
