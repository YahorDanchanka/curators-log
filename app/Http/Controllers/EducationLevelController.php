<?php

namespace App\Http\Controllers;

use App\Exports\EducationLevelExport;
use App\Http\Requests\EducationLevelRequest;
use App\Models\Characteristic;
use App\Models\CharacteristicStudent;
use App\Models\EducationLevel;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class EducationLevelController extends Controller
{
    public function index(Group $group, string $courseNumber)
    {
        Gate::authorize('show', $group);

        $course = $group->findCourseByNumber($courseNumber);
        $course->append('group_name');
        $group->load([
            'students' => fn(HasMany $query) => $query
                ->select(['id', 'surname', 'name', 'patronymic', 'group_id'])
                ->doesntHave('expulsion'),
        ]);
        $group->students->each(fn(Student $student) => $student->append('initials'));
        $characteristics = $this->getCharacteristics();
        $educationLevels = $this->getEducationLevels($course->id);
        return Inertia::render('education-level/IndexPage', [
            ...compact('group', 'course', 'characteristics', 'educationLevels'),
            'saving' => true,
            'printing' => true,
            'loading' => $course->number > 1,
        ]);
    }

    public function sync(EducationLevelRequest $request, Group $group, string $courseNumber)
    {
        Gate::authorize('update', $group);
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

    public function print(Group $group, string $courseNumber)
    {
        Gate::authorize('show', $group);
        $course = $group->findCourseByNumber($courseNumber);
        return Excel::download(
            new EducationLevelExport(
                $group
                    ->students()
                    ->doesntHave('expulsion')
                    ->get(),
                $this->getCharacteristics(),
                $this->getEducationLevels($course->id)
            ),
            'Результаты изучения уровня воспитанности учащихся.xlsx'
        );
    }

    public function loadPrevCourse(Group $group, string $courseNumber)
    {
        Gate::authorize('update', $group);
        $course = $group->findCourseByNumber($courseNumber);
        $prevCourse = $group->findPrevCourse($courseNumber);

        EducationLevel::whereRelation('characteristicStudent', 'course_id', $prevCourse->id)->each(function (
            EducationLevel $educationLevel
        ) use ($course) {
            try {
                DB::transaction(function () use ($educationLevel, $course) {
                    $characteristicStudentRow = $educationLevel->characteristicStudent->replicate();
                    $characteristicStudentRow->course_id = $course->id;
                    $characteristicStudentRow->save();

                    $newEducationLevel = $educationLevel->replicate();
                    $newEducationLevel->characteristic_student_id = $characteristicStudentRow->id;
                    $newEducationLevel->save();
                });
            } catch (UniqueConstraintViolationException $exception) {
            }
        });

        return to_route('groups.courses.education-level.index', [
            'group' => $group->id,
            'course_number' => $course->number,
        ]);
    }

    protected function getCharacteristics(): Collection
    {
        return Characteristic::where('type', 'education-level')->get();
    }

    protected function getEducationLevels($courseId): Collection
    {
        return EducationLevel::with('characteristicStudent')
            ->whereHas('characteristicStudent', function (Builder $query) use ($courseId) {
                $query->where('course_id', $courseId)->doesntHave('student.expulsion');
            })
            ->get();
    }
}
