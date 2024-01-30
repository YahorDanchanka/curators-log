<?php

namespace App\Http\Controllers;

use App\Http\Requests\RelativeRequest;
use App\Models\Address;
use App\Models\Group;
use App\Models\Relative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StudentRelativeController extends Controller
{
    public function index(Group $group, string $studentNumber)
    {
        $student = $group->findStudentByNumber($studentNumber);
        $student->load('relatives');
        $student->append(['initials', 'adult_relatives', 'minor_relatives']);
        $student->relatives->each(fn(Relative $relative) => $relative->address?->append('address'));
        return Inertia::render('student-relative/IndexPage', compact('group', 'student', 'studentNumber'));
    }

    public function create(Request $request, Group $group, string $studentNumber)
    {
        $student = $group->findStudentByNumber($studentNumber);
        $type = $request->get('type', 'adult');
        $student->append(['initials']);
        return Inertia::render('student-relative/CreatePage', compact('group', 'student', 'studentNumber', 'type'));
    }

    public function store(RelativeRequest $request, Group $group, string $studentNumber)
    {
        $student = $group->findStudentByNumber($studentNumber);
        $validated = $request->validated();

        DB::transaction(function () use ($student, $validated) {
            $addressId = isset($validated['address']) ? Address::create($validated['address'])->id : null;
            $relative = Relative::create([...$validated, 'address_id' => $addressId]);
            $student->relatives()->attach($relative->id, ['type' => $validated['type'] ?? null]);
        });

        return to_route('groups.students.relatives.index', ['group' => $group->id, 'student' => $studentNumber]);
    }

    public function edit(Request $request, Group $group, string $studentNumber, $relativeId)
    {
        $student = $group->findStudentByNumber($studentNumber);
        $relative = $student->relatives()->findOrFail($relativeId);
        $relative->load('address');
        $relative->append('initials');
        $type = $request->get('type', 'adult');
        return Inertia::render(
            'student-relative/EditPage',
            compact('group', 'student', 'studentNumber', 'relative', 'type')
        );
    }

    public function update(RelativeRequest $request, Group $group, string $studentNumber, $relativeId)
    {
        $student = $group->findStudentByNumber($studentNumber);
        $relative = $student->relatives()->findOrFail($relativeId);
        $validated = $request->validated();

        DB::transaction(function () use ($relative, $student, $validated) {
            $relative->update($validated);

            if (isset($validated['address'])) {
                $relative->address()->update($validated['address']);
            }

            if (isset($validated['type'])) {
                $student->relatives()->updateExistingPivot($relative->id, ['type' => $validated['type']]);
            }
        });

        return to_route('groups.students.relatives.index', ['group' => $group->id, 'student' => $studentNumber]);
    }

    public function destroy(Group $group, string $studentNumber, $relativeId)
    {
        $student = $group->findStudentByNumber($studentNumber);
        $relative = $student->relatives()->findOrFail($relativeId);

        DB::transaction(function () use ($student, $relative) {
            $student->relatives()->detach($relative->id);
            $relative->address()->delete();
            $relative->delete();
        });

        return to_route('groups.students.relatives.index', ['group' => $group->id, 'student' => $studentNumber]);
    }
}
