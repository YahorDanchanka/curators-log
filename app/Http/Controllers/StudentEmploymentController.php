<?php

namespace App\Http\Controllers;

use App\Enums\CharacteristicId;
use App\Http\Requests\StudentEmploymentRequest;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StudentEmploymentController extends Controller
{
    public function index(Group $group, string $course_number)
    {
        $course = $group->findCourseByNumber($course_number);

        $group->load([
            'students' => fn(HasMany $query) => $query->select('id', 'surname', 'name', 'patronymic', 'group_id'),
            'students.characteristics' => fn(BelongsToMany $query) => $query
                ->whereIn('characteristics.id', [
                    CharacteristicId::BRSM_ID,
                    CharacteristicId::LEADER_ID,
                    CharacteristicId::DEPUTY_LEADER_ID,
                    CharacteristicId::BRSM_SECRETARY_ID,
                    CharacteristicId::UNION_ORGANIZER_ID,
                    CharacteristicId::EDUCATIONAL_SECTOR_ID,
                    CharacteristicId::INFORMATION_IDEOLOGICAL_SECTOR_ID,
                    CharacteristicId::SPORT_SECTOR_ID,
                    CharacteristicId::LABOR_SECTOR_ID,
                    CharacteristicId::CULTURAL_MASS_SECTOR_ID,
                    CharacteristicId::LAW_SECTOR_ID,
                    CharacteristicId::EDITORIAL_SECTOR_ID,
                ])
                ->wherePivot('course_id', $course_number),
            'students.employments' => fn(HasMany $query) => $query->where('course_id', $course->id),
        ]);

        $group->students->each(fn(Student $student) => $student->append('initials', 'full_name'));

        return Inertia::render('student-employment/IndexPage', [...compact('group', 'course'), 'saving' => true]);
    }

    public function sync(StudentEmploymentRequest $request, Group $group, string $course_number)
    {
        $course = $group->findCourseByNumber($course_number);
        $validated = $request->validated();

        DB::transaction(function () use ($course, $validated) {
            $course->studentEmployment()->delete();

            foreach ($validated['data'] as $row) {
                if (!($row['first_semester'] ?? null) && !($row['second_semester'] ?? null)) {
                    continue;
                }

                $course->studentEmployment()->create($row);
            }
        });

        return to_route('groups.courses.student-employment.sync', [
            'group' => $group->id,
            'course_number' => $course->number,
        ]);
    }
}
