<?php

namespace App\Dtos;

class GradeSubjectDTO
{
    /**
     * @param string $name
     * @param GradeRowDTO[] $rows
     */
    public function __construct(public string $name, public array $rows = [])
    {
    }

    public function getStudentGrade($studentId): float|int|false
    {
        if (isset($this->rows[$studentId])) {
            return $this->rows[$studentId]->getGrade();
        }

        return false;
    }

    public function getColumnCount(): int
    {
        return count(array_values($this->rows)[0]->grades ?? []);
    }
}
