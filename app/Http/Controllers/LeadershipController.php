<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadershipRequest;
use App\Models\Characteristic;
use App\Models\Group;
use App\Models\Student;
use App\Services\GroupCompositionService;
use App\Services\LeadershipService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Inertia\Inertia;

class LeadershipController extends Controller
{
    public function index(Group $group, string $course_number)
    {
        $course = $group->findCourseByNumber($course_number);
        $group->load([
            'students' => fn(HasMany $query) => $query
                ->select(['id', 'surname', 'name', 'patronymic', 'group_id'])
                ->with([
                    'characteristics' => fn(BelongsToMany $query) => $query
                        ->wherePivot('course_id', $course->id)
                        ->where(
                            fn(Builder $query) => $query
                                ->where('type', 'leadership')
                                ->orWhere('type', 'group-composition')
                        ),
                ]),
        ]);
        $group->students->each(fn(Student $student) => $student->append('full_name'));

        $groupCompositionCharacteristics = Characteristic::select(['id', 'name'])
            ->where('type', 'group-composition')
            ->get();

        return Inertia::render('course/LeadershipPage', [
            ...compact('group', 'course', 'groupCompositionCharacteristics'),
            'saving' => true,
        ]);
    }

    public function sync(
        LeadershipRequest $request,
        Group $group,
        string $course_number,
        LeadershipService $leadershipService,
        GroupCompositionService $groupCompositionService
    ) {
        $course = $group->findCourseByNumber($course_number);
        $validated = $request->validated();

        $leadershipService->setLeader($course, $validated['leader_id'] ?? null);
        $leadershipService->setDeputyLeader($course, $validated['deputy_leader_id'] ?? null);
        $leadershipService->setBrsmSecretary($course, $validated['brsm_secretary_id'] ?? null);
        $leadershipService->setUnionOrganizer($course, $validated['union_organizer_id'] ?? null);
        $groupCompositionService->save($course, $validated['group_composition']);

        return to_route('groups.courses.leadership.index', [
            'group' => $group->id,
            'course_number' => $course->number,
        ]);
    }
}
