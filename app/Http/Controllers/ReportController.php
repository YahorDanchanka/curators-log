<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\Group;
use App\Models\Plan;
use App\Models\Report;
use App\Tables\ReportTable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PhpOffice\PhpWord\Element\TextBreak;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\TemplateProcessor;

class ReportController extends Controller
{
    public function index(Group $group, string $courseNumber)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $course->append('group_name');

        $groupedReports = $course->reports
            ->sortBy(function (Report $report) use ($course) {
                $numericMonth = (int) $report->month;
                $date = Carbon::createFromFormat(
                    'Y-m-d',
                    $numericMonth >= 9 && $numericMonth <= 12 ? $course->start_education : $course->end_education
                );
                $date->setMonth($numericMonth);
                return $date->timestamp;
            })
            ->groupBy('month')
            ->keyBy(fn(Collection $reports, mixed $month) => $month . 'a');

        return Inertia::render('report/IndexPage', [
            ...compact('group', 'course', 'groupedReports'),
            'printing' => true,
        ]);
    }

    public function show(Group $group, string $courseNumber, string $month)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $course->load(['reports' => fn(HasMany $query) => $query->where('month', $month)]);
        $course->append('group_name');
        return Inertia::render('report/ShowPage', [
            ...compact('group', 'course', 'month'),
            'saving' => true,
            'printing' => true,
        ]);
    }

    public function printSingle(Group $group, string $courseNumber, string $month)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $course->load(['reports' => fn(HasMany $query) => $query->where('month', $month)]);

        $templateProcessor = new TemplateProcessor(resource_path('documents/report.docx'));
        $templateProcessor->setValues(['course' => $courseNumber]);
        $templateProcessor->cloneBlock('tables', 1, true, true);

        $numericMonth = (int) $month;
        $date = Carbon::createFromFormat(
            'Y-m-d',
            $numericMonth >= 9 && $numericMonth <= 12 ? $course->start_education : $course->end_education
        );
        $date->setMonth($numericMonth);

        $templateProcessor->setComplexBlock(
            'table#1',
            (new ReportTable(
                $course->reports,
                $date,
                Converter::cmToTwip(2.24),
                Converter::cmToTwip(11.75),
                Converter::cmToTwip(2.25)
            ))->getTable()
        );

        $templateProcessor->setValue(
            'hours#1',
            round(
                $course->reports->map(fn($report) => $report->hours_per_week + $report->hours_saturday ?? 0)->sum(),
                2
            )
        );

        $templateProcessor->setComplexBlock('table_after#1', new TextBreak());

        return response()->streamDownload(
            fn() => $templateProcessor->saveAs('php://output'),
            "Отчет {$course->groupName} за {$date->monthName}.docx"
        );
    }

    public function print(Group $group, string $courseNumber)
    {
        $course = $group->findCourseByNumber($courseNumber);

        $templateProcessor = new TemplateProcessor(resource_path('documents/report.docx'));
        $templateProcessor->setValues(['course' => $courseNumber]);

        $groupedReports = $course->reports
            ->sortBy(function (Report $report) use ($course) {
                $numericMonth = (int) $report->month;
                $date = Carbon::createFromFormat(
                    'Y-m-d',
                    $numericMonth >= 9 && $numericMonth <= 12 ? $course->start_education : $course->end_education
                );
                $date->setMonth($numericMonth);
                return $date->timestamp;
            })
            ->groupBy('month');

        $templateProcessor->cloneBlock('tables', $groupedReports->count(), true, true);

        $groupedReports->each(function (Collection $reports, string $month) use (
            $course,
            $templateProcessor,
            $groupedReports
        ) {
            $index = $groupedReports->keys()->search($month);
            $numericMonth = (int) $month;
            $date = Carbon::createFromFormat(
                'Y-m-d',
                $numericMonth >= 9 && $numericMonth <= 12 ? $course->start_education : $course->end_education
            );
            $date->setMonth($numericMonth);

            $templateProcessor->setComplexBlock(
                'table#' . ($index + 1),
                (new ReportTable(
                    $reports,
                    $date,
                    Converter::cmToTwip(2.24),
                    Converter::cmToTwip(11.75),
                    Converter::cmToTwip(2.25)
                ))->getTable()
            );

            $templateProcessor->setValue(
                'hours#' . ($index + 1),
                round($reports->map(fn($report) => $report->hours_per_week + $report->hours_saturday ?? 0)->sum(), 2)
            );

            $templateProcessor->setComplexBlock('table_after#' . ($index + 1), new TextBreak());
        });

        return response()->streamDownload(
            fn() => $templateProcessor->saveAs('php://output'),
            "Отчет {$course->groupName}.docx"
        );
    }

    public function loadPlan(Group $group, string $courseNumber, string $month)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $plans = $course
            ->plans()
            ->where('month', $month)
            ->where('done', 1)
            ->get();

        $course->reports()->createMany(
            $plans
                ->map(
                    fn(Plan $plan) => [
                        'content' => $plan->content,
                        'hours_per_week' => 1,
                        'hours_saturday' => null,
                        'month' => $month,
                        'week' => $plan->week,
                    ]
                )
                ->values()
                ->toArray()
        );

        return to_route('groups.courses.reports.show', [
            'group' => $group->id,
            'course_number' => $courseNumber,
            'month' => $month,
        ]);
    }

    public function sync(ReportRequest $request, Group $group, string $courseNumber, string $month)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $validated = $request->validated();

        DB::transaction(function () use ($course, $month, $validated) {
            $course
                ->reports()
                ->where('month', $month)
                ->delete();
            $course->reports()->createMany($validated['data']);
        });

        return to_route('groups.courses.reports.show', [
            'group' => $group->id,
            'course_number' => $courseNumber,
            'month' => $month,
        ]);
    }
}
