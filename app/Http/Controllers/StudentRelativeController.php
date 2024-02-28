<?php

namespace App\Http\Controllers;

use App\Http\Requests\RelativeRequest;
use App\Models\Address;
use App\Models\Group;
use App\Models\Relative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PhpOffice\PhpWord\TemplateProcessor;

class StudentRelativeController extends Controller
{
    public function index(Group $group, string $studentNumber)
    {
        $student = $group->findStudentByNumber($studentNumber);
        $group->append('name');
        $student->load('relatives');
        $student->append(['initials', 'adult_relatives', 'minor_relatives', 'full_name']);
        $student->relatives->each(fn(Relative $relative) => $relative->address?->append('address'));
        return Inertia::render('student-relative/IndexPage', [
            ...compact('group', 'student', 'studentNumber'),
            'printing' => true,
        ]);
    }

    public function create(Request $request, Group $group, string $studentNumber)
    {
        $student = $group->findStudentByNumber($studentNumber);
        $type = $request->get('type', 'adult');
        $group->append('name');
        $student->append(['initials', 'full_name']);
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
        $group->append('name');
        $student->append('full_name');
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

    public function print(Group $group, string $studentNumber)
    {
        $student = $group->findStudentByNumber($studentNumber);

        $templateProcessor = new TemplateProcessor(resource_path('documents/student-relatives.docx'));
        $templateProcessor->setValue('initials', $student->initials);

        $templateProcessor->cloneRowAndSetValues(
            'surname',
            $student->adult_relatives
                ->map(
                    fn(Relative $relative) => [
                        'surname' => $relative->surname,
                        'name' => $relative->name,
                        'patronymic' => $relative->patronymic,
                        'sex' => $relative->sex,
                        'type' => $relative->pivot->type,
                        'job' => $relative->job,
                        'position' => $relative->position,
                        'phone' => $relative->phone,
                        'address' => $relative->address->address,
                    ]
                )
                ->values()
                ->toArray()
        );

        $templateProcessor->cloneRowAndSetValues(
            'surname1',
            $student->minor_relatives
                ->map(
                    fn(Relative $relative) => [
                        'surname1' => $relative->surname,
                        'name1' => $relative->name,
                        'patronymic1' => $relative->patronymic,
                        'sex1' => $relative->sex,
                        'birthday1' => $relative->birthday,
                        'educational_institution1' => $relative->educational_institution,
                    ]
                )
                ->values()
                ->toArray()
        );

        return response()->streamDownload(
            fn() => $templateProcessor->saveAs('php://output'),
            "Родственники {$student->initials}.docx"
        );
    }
}
