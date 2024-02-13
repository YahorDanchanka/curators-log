<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadershipRequest;
use App\Models\Characteristic;
use App\Models\Group;
use App\Models\Student;
use App\Services\GroupCompositionService;
use App\Services\LeadershipService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Inertia\Inertia;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;

class LeadershipController extends Controller
{
    public function index(Group $group, string $course_number)
    {
        $course = $group->findCourseByNumber($course_number);
        $group->load([
            'students' => fn(HasMany $query) => $query
                ->select(['id', 'surname', 'name', 'patronymic', 'group_id'])
                ->with([
                    'characteristics' => fn(BelongsToMany $query) => $query
                        ->wherePivot('course_id', $course->id)
                        ->where(
                            fn(Builder $query) => $query
                                ->where('type', 'leadership')
                                ->orWhere('type', 'group-composition')
                        ),
                ]),
        ]);
        $group->students->each(fn(Student $student) => $student->append('full_name'));

        $groupCompositionCharacteristics = Characteristic::select(['id', 'name'])
            ->where('type', 'group-composition')
            ->get();

        return Inertia::render('course/LeadershipPage', [
            ...compact('group', 'course', 'groupCompositionCharacteristics'),
            'saving' => true,
            'printing' => true,
        ]);
    }

    public function sync(
        LeadershipRequest $request,
        Group $group,
        string $course_number,
        LeadershipService $leadershipService,
        GroupCompositionService $groupCompositionService
    ) {
        $course = $group->findCourseByNumber($course_number);
        $validated = $request->validated();

        $leadershipService->setLeader($course, $validated['leader_id'] ?? null);
        $leadershipService->setDeputyLeader($course, $validated['deputy_leader_id'] ?? null);
        $leadershipService->setBrsmSecretary($course, $validated['brsm_secretary_id'] ?? null);
        $leadershipService->setUnionOrganizer($course, $validated['union_organizer_id'] ?? null);
        $groupCompositionService->save($course, $validated['group_composition']);

        return to_route('groups.courses.leadership.index', [
            'group' => $group->id,
            'course_number' => $course->number,
        ]);
    }

    public function print(
        Group $group,
        string $course_number,
        LeadershipService $leadershipService,
        GroupCompositionService $groupCompositionService
    ) {
        $course = $group->findCourseByNumber($course_number);

        $templateProcessor = new TemplateProcessor(resource_path('documents/group-asset.docx'));

        $templateProcessor->setValues([
            'course' => $course_number,
            'leader' => $leadershipService->getLeader($course)?->fullName,
            'deputy_leader' => $leadershipService->getDeputyLeader($course)?->fullName,
            'brsm_secretary' => $leadershipService->getBrsmSecretary($course)?->fullName,
            'union_organizer' => $leadershipService->getUnionOrganizer($course)?->fullName,
        ]);

        $cellHCentered = ['alignment' => Jc::CENTER];
        $cellVCentered = ['valign' => 'center'];
        $cellCentered = [...$cellHCentered, ...$cellVCentered];

        $table = new Table([
            'borderSize' => 1,
            'alignment' => JcTable::CENTER,
        ]);

        $cellWidths = [Converter::cmToTwip(4.68), Converter::cmToTwip(1.52), Converter::cmToTwip(12.26)];

        $headerRow = $table->addRow();
        $headerRow->addCell($cellWidths[0])->addText('Сектор', null, [...$cellCentered, 'indent' => null]);
        $headerRow->addCell($cellWidths[1])->addText('№п/п', null, $cellCentered);
        $headerRow->addCell($cellWidths[2])->addText('Фамилия, имя, отчество', null, $cellCentered);

        $compositions = Characteristic::select(['id', 'name'])
            ->with('students')
            ->where('type', 'group-composition')
            ->get();

        foreach ($compositions as $composition) {
            $students = $composition->students->values();

            $studentIndex = 0;

            do {
                $student = $students[$studentIndex] ?? null;

                $bodyRow = $table->addRow();
                $bodyRow
                    ->addCell($cellWidths[0], ['vMerge' => $studentIndex === 0 ? 'restart' : 'continue'])
                    ->addText($composition->name);
                $bodyRow->addCell($cellWidths[1])->addText($student ? $studentIndex + 1 : null, null, $cellCentered);
                $bodyRow->addCell($cellWidths[2])->addText($student?->fullName);

                $studentIndex++;
            } while ($studentIndex < count($students));
        }

        $templateProcessor->setComplexBlock('table', $table);

        return response()->streamDownload(
            fn() => $templateProcessor->saveAs('php://output'),
            "Актив учебной группы {$course->groupName}.docx"
        );
    }
}
