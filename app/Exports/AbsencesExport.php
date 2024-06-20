<?php

namespace App\Exports;

use App\Models\Course;
use App\Services\AbsenceService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class AbsencesExport implements FromView, WithStyles, ShouldAutoSize, WithDefaultStyles, WithProperties
{
    protected Collection $absences;
    protected Carbon $date;

    public function __construct(protected Course $course, protected string $month)
    {
        /** @var AbsenceService $absenceService */
        $absenceService = app()->make(AbsenceService::class);
        $this->absences = $absenceService->find($course, $month)->get();
        $this->date = $this->course->getTargetDate((int) $month);
    }

    public function properties(): array
    {
        return [
            'creator' => config('app.author'),
            'lastModifiedBy' => config('app.author'),
        ];
    }

    public function view(): View
    {
        return view('exports.absences', [
            'absences' => $this->absences,
            'date' => $this->date,
            'students' => $this->course->group
                ->students()
                ->doesntHave('expulsion')
                ->get(),
            'course' => $this->course,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setFitToWidth(1);

        $headerCell = $sheet->getStyle('A1');
        $headerCell->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $headerCell->getFont()->setBold(true);

        $sheet->mergeCells('A1:' . Coordinate::stringFromColumnIndex($this->date->daysInMonth * 2 + 4) . '1');
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'font' => [
                'name' => 'Times New Roman',
                'size' => 12,
            ],
        ];
    }
}
