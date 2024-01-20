<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocioPedagogicalCharacteristicRequest;
use App\Models\Characteristic;
use App\Models\CharacteristicStudent;
use App\Models\Course;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SocioPedagogicalCharacteristicController extends Controller
{
    public function index(Group $group, Course $course)
    {
        $course->append('group_name');

        $group->load([
            'students' => fn(HasMany $query) => $query
                ->select(['id', 'surname', 'name', 'patronymic', 'birthday', 'group_id'])
                ->with(['characteristics' => fn(BelongsToMany $query) => $query->where('course_id', $course->id)]),
        ]);

        $group->students->each(fn(Student $student) => $student->append(['initials', 'is_nonresident', 'is_dorm']));

        $characteristics = Characteristic::select(['id', 'name'])
            ->where('type', 'socio-pedagogical')
            ->get();

        return Inertia::render('socio-pedagogical-characteristic/IndexPage', [
            ...compact('group', 'course', 'characteristics'),
            'saving' => true,
        ]);
    }

    public function sync(SocioPedagogicalCharacteristicRequest $request, Group $group, Course $course)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $group, $course) {
            /** Remove earlier characteristics */
            CharacteristicStudent::where('course_id', $course->id)
                ->whereRelation('characteristic', 'type', 'socio-pedagogical')
                ->whereRelation('student', 'group_id', $group->id)
                ->delete();
            /** Push new data */
            CharacteristicStudent::insert(
                array_map(fn($row) => [...$row, 'course_id' => $course->id], $validated['data'])
            );
        });

        return to_route('groups.courses.socio-pedagogical-characteristic.index', [
            'group' => $group->id,
            'course' => $course->id,
        ]);
    }
}
