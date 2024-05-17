<?php

namespace App\Http\Controllers;

use App\Models\Characteristic;
use Inertia\Inertia;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Http\Requests\SocioPedagogicalCharacteristicRequest;
use App\Models\CharacteristicStudent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class OtherCharacteristicController extends Controller
{
    public function index(Group $group, string $courseNumber)
    {
        Gate::authorize('view', $group);

        $course = $group->findCourseByNumber($courseNumber);
        $course->append('group_name');

        $group->load([
            'students' => fn(HasMany $query) => $query
                ->select(['id', 'surname', 'name', 'patronymic', 'group_id'])
                ->with([
                    'characteristics' => fn($query) => $query
                        ->where('type', 'other-characteristic')
                        ->where('course_id', $course->id),
                ]),
        ]);

        $group->students->each(fn(Student $student) => $student->append('initials'));

        $characteristics = Characteristic::select(['id', 'name'])
            ->where('type', 'other-characteristic')
            ->get();

        return Inertia::render('other-characteristic/IndexPage', [
            ...compact('group', 'course', 'characteristics'),
            'saving' => true,
        ]);
    }

    public function sync(SocioPedagogicalCharacteristicRequest $request, Group $group, string $courseNumber)
    {
        Gate::authorize('update', $group);
        $course = $group->findCourseByNumber($courseNumber);
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $group, $course) {
            /** Remove earlier characteristics */
            CharacteristicStudent::where('course_id', $course->id)
                ->whereRelation('characteristic', 'type', 'other-characteristic')
                ->whereRelation('student', 'group_id', $group->id)
                ->delete();
            /** Push new data */
            CharacteristicStudent::insert(
                array_map(fn($row) => [...$row, 'course_id' => $course->id], $validated['data'])
            );
        });

        return to_route('groups.courses.other-characteristic.index', [
            'group' => $group->id,
            'course_number' => $course->number,
        ]);
    }
}
