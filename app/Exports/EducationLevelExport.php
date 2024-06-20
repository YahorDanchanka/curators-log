<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class EducationLevelExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnWidths,
    WithStyles,
    WithDefaultStyles,
    WithProperties
{
    protected int $rowNumber = 1;
    protected array $characteristicBoundaries;
    protected array $tableBoundaries;

    public function __construct(
        protected Collection $students,
        protected Collection $characteristics,
        protected Collection $educationLevels
    ) {
        $this->characteristicBoundaries = [
            'C1',
            Coordinate::stringFromColumnIndex($this->characteristics->count() + 2) . '1',
        ];

        $this->tableBoundaries = [
            'A1',
            Coordinate::stringFromColumnIndex($this->characteristics->count() + 3) . $this->students->count() + 2,
        ];
    }

    public function properties(): array
    {
        return [
            'creator' => config('app.author'),
            'lastModifiedBy' => config('app.author'),
        ];
    }

    public function collection()
    {
        return collect()
            ->merge($this->students)
            ->add('total');
    }

    public function headings(): array
    {
        return [
            '№ п/п',
            'Фамилия, имя, отчество учащегося',
            ...$this->characteristics
                ->pluck('name')
                ->values()
                ->toArray(),
            'Итоговая оценка учащегося',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 4.9,
            'B' => 22.22,
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return $defaultStyle
            ->getFont()
            ->setName('Times New Roman')
            ->setSize(12);
    }

    public function styles(Worksheet $sheet)
    {
        $startCharacteristicColumn = 'C1';
        $endCharacteristicColumn = Coordinate::stringFromColumnIndex($this->characteristics->count() + 3);

        $sheet
            ->getStyle('A1')
            ->getAlignment()
            ->setWrapText(true)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->duplicateStyle($sheet->getStyle('A1'), "A1:{$endCharacteristicColumn}1");

        $sheet
            ->getStyle($startCharacteristicColumn)
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_BOTTOM)
            ->setTextRotation(90);
        $sheet->duplicateStyle($sheet->getStyle('C1'), "D1:{$endCharacteristicColumn}1");

        $sheet->mergeCells([1, $this->students->count() + 2, 2, $this->students->count() + 2]);

        $sheet->getStyle("{$this->tableBoundaries[0]}:{$this->tableBoundaries[1]}")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => Color::COLOR_BLACK]],
            ],
        ]);
    }

    public function map($student): array
    {
        $this->rowNumber++;

        $endCharacteristicColumn = Coordinate::stringFromColumnIndex($this->characteristics->count() + 2);
        $avgRange = $this->tableBoundaries[1][0] . '2:' . $this->tableBoundaries[1][0] . $this->students->count() + 1;

        return $student instanceof Student
            ? [
                $this->rowNumber - 1,
                $student->initials,
                ...$this->characteristics
                    ->pluck('id')
                    ->map(function (int $characteristicId) use ($student) {
                        $educationLevel = $this->educationLevels
                            ->where('characteristicStudent.student_id', $student->id)
                            ->where('characteristicStudent.characteristic_id', $characteristicId)
                            ->first();

                        return $educationLevel?->level ?? '';
                    })
                    ->values()
                    ->toArray(),
                "=IFERROR(AVERAGE(C{$this->rowNumber}:{$endCharacteristicColumn}{$this->rowNumber}), \"\")",
            ]
            : [
                'Итоговая оценка по группе',
                '',
                ...$this->characteristics
                    ->map(
                        fn($characteristic, $index) => Coordinate::stringFromColumnIndex($index + 3) .
                            '2:' .
                            Coordinate::stringFromColumnIndex($index + 3) .
                            $this->students->count() +
                            1
                    )
                    ->map(fn($value) => "=IFERROR(AVERAGE($value), \"\")")
                    ->values()
                    ->toArray(),
                "=IFERROR(AVERAGE($avgRange), \"\")",
            ];
    }
}
