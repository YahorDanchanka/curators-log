<?php

namespace App\Classes;

use App\Dtos\GradeDTO;
use App\Dtos\GradeRowDTO;
use App\Dtos\GradeSubjectDTO;

class GradeCalculator
{
    /** @var GradeSubjectDTO[] */
    public array $subjects;

    public function __construct(array $subjects)
    {
        $this->subjects = $this->transform($subjects);
    }

    public function transform(array $subjects): array
    {
        return array_map(function ($subject) {
            $rows = array_map(function ($row) {
                $grades = array_map(function ($grade) {
                    return new GradeDTO($grade['type'], $grade['value']);
                }, $row['grades'] ?? []);

                return new GradeRowDTO($grades);
            }, $subject['rows'] ?? []);

            return new GradeSubjectDTO($subject['name'], $rows);
        }, $subjects);
    }

    /**
     * Get student grades from all subjects
     * If student id doesn't exist, return empty array
     *
     * @param $studentId
     * @return GradeDTO[]
     */
    public function getStudentGrades($studentId): array
    {
        return collect($this->subjects)
            ->pluck("rows.$studentId.grades")
            ->collapse()
            ->filter()
            ->toArray();
    }

    public function getStudentGrade($studentId): float|int|false
    {
        return collect(
            array_filter(
                array_map(fn(GradeSubjectDTO $subject) => $subject->getStudentGrade($studentId), $this->subjects),
                fn($gradeValue) => $gradeValue === 0 || !!$gradeValue
            )
        )->avg() ?? false;
    }

    public function getStudentIds(): array
    {
        return array_keys(
            collect($this->subjects)
                ->pluck('rows')
                ->first(null, [])
        );
    }

    public function getAvgGrade(): float|int|false
    {
        return collect($this->getStudentIds())
            ->map(fn($studentId) => $this->getStudentGrade($studentId))
            ->filter(fn($gradeValue) => $gradeValue === 0 || !!$gradeValue)
            ->avg() ?? false;
    }
}
