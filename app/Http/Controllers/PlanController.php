<?php

namespace App\Http\Controllers;

use App\Enums\PlanSectionEnum;
use App\Http\Requests\PlanRequest;
use App\Models\Group;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PhpOffice\PhpWord\TemplateProcessor;

class PlanController extends Controller
{
    public function index(Group $group, string $courseNumber, string $month)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $course->load(['plans' => fn(HasMany $query) => $query->where('month', $month)]);
        $course->append('group_name');
        return Inertia::render('plan/IndexPage', [
            ...compact('group', 'course', 'month'),
            'saving' => true,
            'printing' => true,
        ]);
    }

    public function sync(PlanRequest $request, Group $group, string $courseNumber, string $month)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $validated = $request->validated();

        DB::transaction(function () use ($course, $validated, $month) {
            $course
                ->plans()
                ->where('month', $month)
                ->delete();

            foreach ($validated['data'] as $row) {
                $course->plans()->create([...$row, 'month' => $month]);
            }
        });

        return to_route('groups.courses.plans.index', [
            'group' => $group->id,
            'course_number' => $courseNumber,
            'month' => $month,
        ]);
    }

    public function print(Group $group, string $courseNumber, string $month)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $plans = $course
            ->plans()
            ->where('month', $month)
            ->get();

        $numericMonth = (int) $month;
        $date = Carbon::createFromFormat(
            'Y-m-d',
            $numericMonth >= 9 && $numericMonth <= 12 ? $course->start_education : $course->end_education
        );
        $date->setMonth($numericMonth);

        $templateProcessor = new TemplateProcessor(resource_path('documents/plan.docx'));

        $templateProcessor->setValues([
            'course' => $course->number,
            'month' => $date->monthName,
            'year' => $date->year,
            'group' => $course->group_name,
        ]);

        $planMapFunction = fn($postfix = '') => fn(Plan $plan) => [
            "date$postfix" => $plan->date,
            "content$postfix" => strip_tags($plan->content),
            "done$postfix" => $plan->done ? 'Выполнено' : 'Не выполнено',
        ];

        foreach (PlanSectionEnum::cases() as $index => $case) {
            $templateProcessor->cloneRowAndSetValues(
                'date' . ($index === 0 ? '' : $index),
                $plans
                    ->where('section', $case->value)
                    ->map($planMapFunction($index === 0 ? '' : $index))
                    ->values()
                    ->toArray()
            );
        }

        return response()->streamDownload(
            fn() => $templateProcessor->saveAs('php://output'),
            "План воспитательной и идеологической работы учебной группы {$course->group_name} на {$date->monthName} {$date->year} г.docx"
        );
    }
}
