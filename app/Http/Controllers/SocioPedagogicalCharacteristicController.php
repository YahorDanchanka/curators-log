<?php

namespace App\Http\Controllers;

use App\Exports\SocioPedagogicalCharacteristicExport;
use App\Http\Requests\SocioPedagogicalCharacteristicRequest;
use App\Models\Characteristic;
use App\Models\CharacteristicStudent;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class SocioPedagogicalCharacteristicController extends Controller
{
    public function index(Group $group, string $course_number)
    {
        $course = $group->findCourseByNumber($course_number);
        $course->append('group_name');

        $group->load([
            'students' => fn(HasMany $query) => $query
                ->select(['id', 'surname', 'name', 'patronymic', 'birthday', 'group_id'])
                ->with([
                    'characteristics' => fn(BelongsToMany $query) => $query
                        ->where('type', 'socio-pedagogical')
                        ->where('course_id', $course->id),
                    'expulsion',
                ])
                ->doesntHave('expulsion')
                ->orWhereRelation('expulsion.course', 'number', '>=', $course_number),
        ]);

        $group->students->each(fn(Student $student) => $student->append(['initials', 'is_nonresident', 'is_dorm']));
        $characteristics = $this->getCharacteristics();

        return Inertia::render('socio-pedagogical-characteristic/IndexPage', [
            ...compact('group', 'course', 'characteristics'),
            'saving' => true,
            'printing' => true,
        ]);
    }

    public function sync(SocioPedagogicalCharacteristicRequest $request, Group $group, string $course_number)
    {
        $course = $group->findCourseByNumber($course_number);
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
            'course_number' => $course->number,
        ]);
    }

    public function print(Group $group, string $course_number)
    {
        $course = $group->findCourseByNumber($course_number);
        return Excel::download(
            new SocioPedagogicalCharacteristicExport(
                $group
                    ->students()
                    ->with([
                        'characteristics' => fn(BelongsToMany $query) => $query
                            ->where('type', 'socio-pedagogical')
                            ->where('course_id', $course->id),
                    ])
                    ->get(),
                $this->getCharacteristics()
            ),
            "Социально-педагогическая характеристика учебной группы № {$course->groupName}.xlsx"
        );
    }

    protected function getCharacteristics(): Collection
    {
        return Characteristic::select(['id', 'name'])
            ->where('type', 'socio-pedagogical')
            ->get();
    }
}
