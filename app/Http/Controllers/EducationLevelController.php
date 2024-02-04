<?php

namespace App\Http\Controllers;

use App\Http\Requests\EducationLevelRequest;
use App\Models\Characteristic;
use App\Models\CharacteristicStudent;
use App\Models\EducationLevel;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EducationLevelController extends Controller
{
    public function index(Group $group, string $courseNumber)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $group->load([
            'students' => fn(HasMany $query) => $query->select(['id', 'surname', 'name', 'patronymic', 'group_id']),
        ]);
        $group->students->each(fn(Student $student) => $student->append('initials'));
        $characteristics = Characteristic::where('type', 'education-level')->get();
        $educationLevels = EducationLevel::with('characteristicStudent')
            ->whereRelation('characteristicStudent', 'course_id', $course->id)
            ->get();
        return Inertia::render('education-level/IndexPage', [
            ...compact('group', 'course', 'characteristics', 'educationLevels'),
            'saving' => true,
        ]);
    }

    public function sync(EducationLevelRequest $request, Group $group, string $courseNumber)
    {
        $course = $group->findCourseByNumber($courseNumber);
        $validated = $request->validated();

        DB::transaction(function () use ($course, $validated) {
            CharacteristicStudent::where('course_id', $course->id)
                ->whereRelation('characteristic', 'type', 'education-level')
                ->delete();

            foreach ($validated['data'] as $row) {
                /** @var CharacteristicStudent $pivotRow */
                $pivotRow = CharacteristicStudent::create([
                    ...$row['characteristic_student'],
                    'course_id' => $course->id,
                ]);

                EducationLevel::create([...$row, 'characteristic_student_id' => $pivotRow->id]);
            }
        });

        return to_route('groups.courses.education-level.index', [
            'group' => $group->id,
            'course_number' => $courseNumber,
        ]);
    }
}
