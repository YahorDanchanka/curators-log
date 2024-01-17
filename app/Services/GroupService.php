<?php

namespace App\Services;

use App\Http\Requests\GroupRequest;
use App\Models\Course;
use App\Models\Group;
use Illuminate\Support\Facades\DB;

class GroupService
{
    public function create(GroupRequest $request): void
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $group = Group::create($validated);
            $group->courses()->createMany($validated['courses']);
        });
    }

    public function update(Group $group, GroupRequest $request): void
    {
        $validated = $request->validated();
        $requestCourses = collect($validated['courses']);

        DB::transaction(function () use ($validated, $group, $requestCourses) {
            $group->update($validated);

            foreach ($group->courses as $course) {
                $courseFromRequest = $requestCourses->first(
                    fn($courseAttrs) => $courseAttrs['number'] === $course->number
                );

                /** Удаляем курс, если его нет в запросе на сохранение */
                if (!$courseFromRequest) {
                    $course->delete();
                } else {
                    $course->update($courseFromRequest);
                }
            }

            /** Создание новых курсов из запроса */
            $requestCourses->each(function ($requestCourse) use ($group) {
                if (!$group->courses->contains(fn(Course $course) => $requestCourse['number'] === $course->number)) {
                    Course::create([...$requestCourse, 'group_id' => $group->id]);
                }
            });
        });
    }
}
