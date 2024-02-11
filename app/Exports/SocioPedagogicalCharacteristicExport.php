<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class SocioPedagogicalCharacteristicExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnWidths,
    WithStyles,
    WithDefaultStyles
{
    protected int $rowNumber = 1;
    protected array $characteristicBoundaries;
    protected array $tableBoundaries;

    public function __construct(protected Collection $students, protected Collection $characteristics)
    {
        $this->characteristicBoundaries = [
            'D1',
            Coordinate::stringFromColumnIndex($this->characteristics->count() + 5) . '1',
        ];

        $this->tableBoundaries = [
            'A1',
            Coordinate::stringFromColumnIndex($this->characteristics->count() + 5) . $this->students->count() + 2,
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
            'Дата рождения',
            ...$this->characteristics
                ->pluck('name')
                ->values()
                ->toArray(),
            'Иногородние учащиеся',
            'Учащиеся, проживающие в общежитии',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 4.9,
            'B' => 22.22,
            'C' => 10.5,
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
        $startCharacteristicColumn = 'D1';
        $endCharacteristicColumn = Coordinate::stringFromColumnIndex($this->characteristics->count() + 5);

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
        $sheet->duplicateStyle($sheet->getStyle('D1'), "D1:{$endCharacteristicColumn}1");

        $sheet->mergeCells([1, $this->students->count() + 2, 3, $this->students->count() + 2]);

        $sheet->getStyle("{$this->tableBoundaries[0]}:{$this->tableBoundaries[1]}")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => Color::COLOR_BLACK]],
            ],
        ]);
    }

    public function map($student): array
    {
        $this->rowNumber++;

        return $student instanceof Student
            ? [
                $this->rowNumber - 1,
                $student->initials,
                $student->birthday ? Carbon::parse($student->birthday)->format('d.m.Y') : '',
                ...$this->characteristics
                    ->pluck('id')
                    ->map(
                        fn(int $characteristicId) => $student->characteristics->contains('id', $characteristicId)
                            ? '+'
                            : ''
                    )
                    ->values()
                    ->toArray(),
                $student->is_nonresident ? '+' : '',
                $student->is_dorm ? '+' : '',
            ]
            : [
                'Всего',
                '',
                '',
                ...$this->characteristics
                    ->map(
                        fn($characteristic, $index) => Coordinate::stringFromColumnIndex($index + 4) .
                            '2:' .
                            Coordinate::stringFromColumnIndex($index + 4) .
                            $this->students->count() +
                            1
                    )
                    ->map(fn($value) => "=COUNTIF($value, \"+\")")
                    ->values()
                    ->toArray(),
                '=COUNTIF(' .
                Coordinate::stringFromColumnIndex(18 + 4) .
                '2:' .
                Coordinate::stringFromColumnIndex(18 + 4) .
                $this->students->count() +
                1 .
                ',"+")',
                '=COUNTIF(' .
                Coordinate::stringFromColumnIndex(19 + 4) .
                '2:' .
                Coordinate::stringFromColumnIndex(19 + 4) .
                $this->students->count() +
                1 .
                ',"+")',
            ];
    }
}
