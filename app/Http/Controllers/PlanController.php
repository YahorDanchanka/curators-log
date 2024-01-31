<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanRequest;
use App\Models\Group;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PlanController extends Controller
{
    public function index(Group $group, string $courseNumber, string $month)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $course->load(['plans' => fn(HasMany $query) => $query->where('month', $month)]);
        $course->append('group_name');
        return Inertia::render('plan/IndexPage', [...compact('group', 'course', 'month'), 'saving' => true]);
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
}
