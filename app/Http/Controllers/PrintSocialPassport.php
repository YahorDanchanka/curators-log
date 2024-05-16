<?php

namespace App\Http\Controllers;

use App\Enums\CharacteristicId;
use App\Helpers\PhpWordPurifier;
use App\Models\Group;
use App\Models\Relative;
use App\Models\Student;
use App\Services\Analytics\AnalyticsService;
use App\Services\Analytics\Strategies\FemaleStudentsStrategy;
use App\Services\Analytics\Strategies\MaleStudentsStrategy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use PhpOffice\PhpWord\TemplateProcessor;

class PrintSocialPassport extends Controller
{
    public function __invoke(Group $group, string $courseNumber, AnalyticsService $analyticsService)
    {
        Gate::authorize('view', $group);

        $course = $group->findCourseByNumber($courseNumber);
        $templateProcessor = new TemplateProcessor(resource_path('documents/social-passport.docx'));

        $students = $group
            ->students()
            ->select([
                'id',
                'surname',
                'name',
                'patronymic',
                'birthday',
                'address_id',
                'study_address_id',
                'passport_id',
            ])
            ->with([
                'address',
                'studyAddress',
                'relatives',
                'passport',
                'characteristics' => fn(BelongsToMany $query) => $query->wherePivot('course_id', $course->id),
            ])
            ->doesntHave('expulsion')
            ->get();

        $templateProcessor->setValues([
            'group' => $course->groupName,
            'start_year' => Carbon::parse($course->start_education)->format('Y'),
            'end_year' => Carbon::parse($course->end_education)->format('Y'),
            'specialty' => $group->specialty->name,
            'curator' => $course->curator->initials,
            'male_count' => $analyticsService->count(new MaleStudentsStrategy(), $course),
            'female_count' => $analyticsService->count(new FemaleStudentsStrategy(), $course),
        ]);

        $lineSeparator = '<w:br />';

        $rows = $students->map(
            fn(Student $student, int $index) => [
                'n' => $index + 1,
                'student' => $student->full_name,
                'birthday' => $student->birthday ? Carbon::parse($student->birthday)->format('d.m.Y') : '',
                'address' => implode(
                    ', ',
                    array_filter([$student->address?->address, $student->relatives->first()?->phone])
                ),
                'study_address' => $student->studyAddress?->address ?? $student->address?->address,
                'passport' => implode(
                    ', ',
                    array_filter([
                        $student->passport?->id_number,
                        $student->passport?->series . $student->passport?->number,
                        $student->passport?->district_department,
                        $student->passport?->issue_date
                            ? Carbon::parse($student->passport->issue_date)->format('d.m.Y')
                            : null,
                    ])
                ),
                'father' => $this->printAdultRelative($student, 'father'),
                'mother' => $this->printAdultRelative($student, 'mother'),
                'minor_relatives' => $student->minor_relatives
                    ->map(
                        fn(Relative $relative) => implode(
                            ', ',
                            array_filter([
                                $relative->full_name,
                                $relative->birthday ? Carbon::parse($relative->birthday)->format('d.m.Y') : null,
                                $relative->educational_institution,
                            ])
                        )
                    )
                    ->join($lineSeparator),
                'family_characteristic' => implode(
                    $lineSeparator,
                    array_filter([
                        $student->characteristics->contains('id', 9) ? 'МН' : null,
                        $student->characteristics->contains('id', 10) ? 'Н' : null,
                        $student->characteristics->contains('id', 14) ? 'РН' : null,
                        $student->characteristics->contains('id', 11) ? 'Ч' : null,
                        $student->characteristics->contains('id', CharacteristicId::STUDENT_HAS_CHILDREN->value)
                            ? 'Д'
                            : null,
                    ])
                ),
                'student_characteristic' => implode(
                    $lineSeparator,
                    array_filter([
                        $student->characteristics->contains('id', 7) || $student->characteristics->contains('id', 8)
                            ? 'ГО'
                            : null,
                        $student->characteristics->contains('id', 15) ? 'СОП' : null,
                        $student->characteristics->contains('id', 17) ? 'ИПУ' : null,
                        $student->characteristics->contains('id', 13) ? 'И' : null,
                        $student->characteristics->contains('id', CharacteristicId::BRSM_ID->value) ? 'БРСМ' : null,
                    ])
                ),
            ]
        );

        $templateProcessor->cloneRowAndSetValues('n', PhpWordPurifier::purify($rows->values()->toArray()));

        return response()->streamDownload(
            fn() => $templateProcessor->saveAs('php://output'),
            "Социальный паспорт группы {$course->groupName}.docx"
        );
    }

    protected function printAdultRelative(Student $student, string $type): string
    {
        return implode(
            ', ',
            array_filter([
                $student->$type?->pivot->type === 'мать' || $student->$type?->pivot->type === 'отец'
                    ? null
                    : $student->$type?->pivot->type,
                $student->$type?->full_name,
                $student->$type?->job,
                $student->$type?->position,
                $student->$type?->phone,
            ])
        );
    }
}
