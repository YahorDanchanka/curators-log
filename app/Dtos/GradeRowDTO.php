<?php

namespace App\Dtos;

class GradeRowDTO
{
    /**
     * @param GradeDTO[] $grades
     */
    public function __construct(public array $grades)
    {
    }

    public function getGrade(): float|int|false
    {
        try {
            return ($this->getAvgByType('default') + $this->getAvgByType('primary') + $this->getAvgByType('course')) /
                ((int) $this->hasGrades('default') +
                    (int) $this->hasGrades('primary') +
                    (int) $this->hasGrades('course'));
        } catch (\DivisionByZeroError $error) {
        }

        return false;
    }

    /**
     * @return GradeDTO[]
     */
    public function getFilteredGrades(): array
    {
        return array_filter($this->grades, fn(GradeDTO $grade) => $grade->value === 0 || !!$grade->value);
    }

    public function hasGrades(string $type): bool
    {
        return collect($this->getFilteredGrades())->some(fn(GradeDTO $gradeDTO) => $gradeDTO->type === $type);
    }

    /**
     * @return GradeDTO[]
     */
    public function getGradesByType(string $type): array
    {
        return array_filter($this->getFilteredGrades(), fn(GradeDTO $gradeDTO) => $gradeDTO->type === $type);
    }

    public function getAvgByType(string $type): float|int
    {
        return collect($this->getGradesByType($type))->avg(fn(GradeDTO $gradeDTO) => $gradeDTO->value) ?: 0;
    }
}
