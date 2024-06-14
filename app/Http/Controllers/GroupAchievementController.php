<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupAchievementRequest;
use App\Models\Group;
use App\Models\GroupAchievement;
use Inertia\Inertia;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Gate;

class GroupAchievementController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Group::class, 'group');
    }

    public function index(Group $group, string $courseNumber)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $course->append('group_name');
        $course->load('achievements');
        return Inertia::render('group-achievement/IndexPage', [...compact('group', 'course'), 'printing' => true]);
    }

    public function create(Group $group, string $courseNumber)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $course->append('group_name');
        return Inertia::render('group-achievement/CreatePage', compact('group', 'course'));
    }

    public function store(GroupAchievementRequest $request, Group $group, string $courseNumber)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $course->achievements()->create($request->all());
        return to_route('groups.courses.achievements.index', ['group' => $group->id, 'course' => $course->number]);
    }

    public function edit(Group $group, string $courseNumber, GroupAchievement $achievement)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $course->append('group_name');
        return Inertia::render('group-achievement/EditPage', compact('group', 'course', 'achievement'));
    }

    public function update(
        GroupAchievementRequest $request,
        Group $group,
        string $courseNumber,
        GroupAchievement $achievement
    ) {
        $course = $group->findCourseByNumber($courseNumber);
        $achievement->update($request->validated());
        return to_route('groups.courses.achievements.index', ['group' => $group->id, 'course' => $course->number]);
    }

    public function destroy(Group $group, string $courseNumber, GroupAchievement $achievement)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $achievement->delete();
        return to_route('groups.courses.achievements.index', ['group' => $group->id, 'course' => $course->number]);
    }

    public function print(Group $group, string $courseNumber)
    {
        Gate::authorize('view', $group);

        $course = $group->findCourseByNumber($courseNumber);

        $achievements = $course->achievements()->get();

        $firstSemesterAchievements = $achievements
            ->where('semester', '1')
            ->values()
            ->toArray();

        $secondSemesterAchievements = $achievements
            ->where('semester', '2')
            ->values()
            ->toArray();

        $rows = [];

        for ($i = 0; $i < max(count($firstSemesterAchievements), count($secondSemesterAchievements)); $i++) {
            $row = [];

            $row['first'] = $firstSemesterAchievements[$i]['content'] ?? '';
            $row['second'] = $secondSemesterAchievements[$i]['content'] ?? '';

            $rows[] = $row;
        }

        $templateProcessor = new TemplateProcessor(resource_path('documents/group-achievements.docx'));
        $templateProcessor->setValue('course', $course->number);
        $templateProcessor->cloneRowAndSetValues('first', $rows);

        return response()->streamDownload(
            fn() => $templateProcessor->saveAs('php://output'),
            "Достижения учебной группы {$course->groupName}.docx"
        );
    }
}
