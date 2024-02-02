<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\Group;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function show(Group $group, string $courseNumber, string $month)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $course->load(['reports' => fn(HasMany $query) => $query->where('month', $month)]);
        return Inertia::render('report/ShowPage', [...compact('group', 'course', 'month'), 'saving' => true]);
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
