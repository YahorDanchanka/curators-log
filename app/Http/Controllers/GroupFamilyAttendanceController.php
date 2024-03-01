<?php

namespace App\Http\Controllers;

use App\Exports\FamilyAttendanceExport;
use App\Http\Requests\GroupFamilyAttendanceRequest;
use App\Models\FamilyAttendance;
use App\Models\FamilyAttendanceRow;
use App\Models\Group;
use App\Models\Relative;
use App\Models\Student;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class GroupFamilyAttendanceController extends Controller
{
    public function index(Group $group)
    {
        $group->load([
            'courses',
            'students' => fn(HasMany $query) => $query->select(['id', 'surname', 'name', 'patronymic', 'group_id']),
            'students.relatives',
        ]);

        $group->append('name');

        $group->students->each(function (Student $student) {
            $student->append(['initials', 'adult_relatives']);
            $student->relatives->each(fn(Relative $relative) => $relative->append('initials'));
        });

        $familyAttendance = FamilyAttendance::whereRelation('student', 'group_id', $group->id)->get();
        $familyAttendanceRows = FamilyAttendanceRow::orderBy('date')
            ->whereRelation('familyAttendance.student', 'group_id', $group->id)
            ->get();

        return Inertia::render('family-attendance/IndexPage', [
            ...compact('group', 'familyAttendance', 'familyAttendanceRows'),
            'saving' => true,
            'printing' => true,
        ]);
    }

    public function sync(GroupFamilyAttendanceRequest $request, Group $group)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($group, $validated) {
            $data = $validated['data'] ?? [];
            FamilyAttendance::whereRelation('student', 'group_id', $group->id)->delete();

            foreach ($data as $familyAttendanceAttributes) {
                $familyAttendanceRows = $familyAttendanceAttributes['rows'] ?? [];
                $familyAttendance = FamilyAttendance::create($familyAttendanceAttributes);
                $familyAttendance->rows()->createMany($familyAttendanceRows);
            }
        });

        return to_route('groups.family-attendances.index', ['group' => $group->id]);
    }

    public function print(Group $group)
    {
        return (new FamilyAttendanceExport($group))->download(
            'Учет посещаемости родителями (другими законными представителями) проводимых мероприятий.xlsx'
        );
    }
}
