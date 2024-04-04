<?php

namespace App\Exports;

use App\Models\Course;
use App\Models\FamilyAttendance;
use App\Models\FamilyAttendanceRow;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Database\Eloquent\Builder;

class FamilyAttendanceExport implements
    FromCollection,
    WithMapping,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithProperties
{
    use Exportable;

    protected $rowNumber = 0;
    protected Collection $columns;
    protected Collection $students;
    protected Collection $familyAttendances;

    public function __construct(protected Group $group)
    {
        $this->students = $group
            ->students()
            ->select(['id', 'surname', 'name', 'patronymic', 'group_id'])
            ->doesntHave('expulsion')
            ->get();

        $this->familyAttendances = FamilyAttendance::whereHas('student', function (Builder $query) use ($group) {
            $query->where('group_id', $group->id)->doesntHave('expulsion');
        })->get();
    }

    public function properties(): array
    {
        return [
            'creator' => config('app.author'),
            'lastModifiedBy' => config('app.author'),
            'title' => 'Учет посещаемости родителями (другими законными представителями) проводимых мероприятий',
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
        $groupedRowsByDate = FamilyAttendanceRow::orderBy('date')
            ->whereHas('familyAttendance.student', function (Builder $query) {
                $query->where('group_id', $this->group->id)->doesntHave('expulsion');
            })
            ->get()
            ->groupBy(fn(FamilyAttendanceRow $row) => "{$row->date}|{$row->course_id}");

        $columns = collect([]);

        $groupedRowsByDate->each(function (Collection $rows, $template) use ($columns) {
            [$date, $course_id] = explode('|', $template);

            $columns->add([
                'date' => $date,
                'course_id' => (int) $course_id,
            ]);
        });

        $this->group->courses->each(function (Course $course) use ($columns) {
            $columnsByCourseCount = $columns->where('course_id', $course->id)->count();

            if ($columnsByCourseCount < 5) {
                for ($i = 0; $i < 5 - $columnsByCourseCount; $i++) {
                    $columns[] = ['course_id' => $course->id];
                }
            }
        });
        $sortedColumns = $columns->sortBy('course_id');
        $this->columns = $sortedColumns;

        return [
            [
                '№ п/п',
                'ФИО учащегося',
                'ФИО родителей (законных представителей)',
                ...$this->group->courses
                    ->map(
                        fn(Course $course) => [
                            "{$course->number} курс обучения",
                            ...array_fill(0, $sortedColumns->where('course_id', $course->id)->count() - 1, ''),
                        ]
                    )
                    ->flatten()
                    ->values()
                    ->toArray(),
                'Примечания',
            ],
            [
                '',
                '',
                '',
                ...$sortedColumns
                    ->values()
                    ->map(
                        fn($attributes) => isset($attributes['date']) && $attributes['date']
                            ? Carbon::parse($attributes['date'])->format('d m')
                            : ' '
                    )
                    ->toArray(),
            ],
        ];
    }

    public function columnWidths(): array
    {
        $noteColumn = Coordinate::stringFromColumnIndex($this->columns->count() + 3 + 1);
        return [
            'A' => 5.44,
            'B' => 18.5,
            'C' => 18.5,
            $noteColumn => 18.5,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:A2');
        $sheet->mergeCells('B1:B2');
        $sheet->mergeCells('C1:C2');

        /** Merge course title column */
        $offset = 4;

        foreach ($this->group->courses as $index => $course) {
            $dateColumnCount = $this->columns->where('course_id', $course->id)->count();
            $startColumn = Coordinate::stringFromColumnIndex($offset);
            $endColumn = Coordinate::stringFromColumnIndex($offset + $dateColumnCount - 1);
            $sheet->mergeCells("{$startColumn}1:{$endColumn}1");
            $offset += $dateColumnCount;
        }

        /** Merge note column */
        $noteColumn = Coordinate::stringFromColumnIndex($this->columns->count() + 3 + 1);
        $sheet->mergeCells("{$noteColumn}1:{$noteColumn}2");

        $totalRowNumber = $this->students->count() + 3;
        $sheet->mergeCells("A{$totalRowNumber}:C{$totalRowNumber}");

        $sheet->getPageSetup()->setFitToWidth(1);

        /** Header styles */
        $sheet
            ->getStyle([1, 1, $this->columns->count() + 4, 2])
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);

        /** Date styles */
        for ($i = 4; $i <= $this->columns->count() + 3; $i++) {
            $sheet->getColumnDimensionByColumn($i)->setWidth(3.8);
        }

        /** Value styles */
        $sheet
            ->getStyle([4, 3, $this->columns->count() + 3, $this->students->count() + 2])
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        /** Table */
        $sheet->getStyle([1, 1, $this->columns->count() + 4, $this->students->count() + 3])->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => Color::COLOR_BLACK]],
            ],
        ]);
    }

    public function map($student): array
    {
        if ($student instanceof Student) {
            $familyAttendance = $this->familyAttendances->where('student_id', $student->id)->first();
            $rows = $familyAttendance?->rows ?? [];

            $values = $this->columns
                ->map(function ($columnValue) use ($rows) {
                    if (!isset($columnValue['date'])) {
                        return '';
                    }

                    $row = $rows
                        ->where('course_id', $columnValue['course_id'])
                        ->where('date', $columnValue['date'])
                        ->first();

                    return $row ? ($row->value ? '+' : '-') : '';
                })
                ->values()
                ->toArray();

            $this->rowNumber++;
            return [
                $this->rowNumber,
                $student->initials,
                $familyAttendance?->relative?->initials,
                ...$values,
                $familyAttendance?->note,
            ];
        }

        return [
            'Всего',
            '',
            '',
            ...collect([])
                ->pad($this->columns->count(), '')
                ->map(function ($item, $index) {
                    $columnLetter = Coordinate::stringFromColumnIndex($index + 4);
                    $rowEnd = $this->rowNumber + 2;
                    return "=COUNTIF({$columnLetter}3:{$columnLetter}{$rowEnd},\"+\")";
                })
                ->toArray(),
        ];
    }
}
