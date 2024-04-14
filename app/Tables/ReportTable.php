<?php

namespace App\Tables;

use App\Helpers\PhpWordPurifier;
use App\Models\Report;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\SimpleType\Jc;

class ReportTable
{
    protected Table $table;

    public function __construct(
        protected Collection $reports,
        protected Carbon $date,
        protected float $weekCellWidth,
        protected float $contentCellWidth,
        protected float $hourCellWidth,
        protected $fStyles = [],
        protected $pStyles = []
    ) {
        $this->table = new Table([
            'borderColor' => '000000',
            'borderSize' => 6,
        ]);

        $this->fStyles = array_merge(
            [
                'size' => 12,
            ],
            $this->fStyles
        );

        $this->pStyles = array_merge(
            [
                'alignment' => Jc::CENTER,
                'indent' => 0,
            ],
            $this->pStyles
        );

        $this->build();
    }

    public function getTable(): Table
    {
        return $this->table;
    }

    protected function build()
    {
        $this->addHeader();
        $this->reports->each(function (Report $report, int $index) {
            $prevReport = $this->reports[$index - 1] ?? null;
            $isWeekSame = $prevReport && $prevReport->week === $report->week;
            $this->addRow($report, $isWeekSame ? 'continue' : 'restart');
        });
    }

    protected function addHeader(): void
    {
        $this->table->addRow();
        $this->table
            ->addCell($this->weekCellWidth, ['vMerge' => 'restart', 'valign' => 'center'])
            ->addText(
                'Неделя месяца<w:br/>' . $this->date->format('m') . ' ' . $this->date->year . ' г.',
                $this->fStyles,
                $this->pStyles
            );
        $this->table
            ->addCell($this->contentCellWidth, ['vMerge' => 'restart', 'valign' => 'center'])
            ->addText('Содержание деятельности', $this->fStyles, $this->pStyles);
        $this->table
            ->addCell($this->hourCellWidth * 2, ['gridSpan' => 2, 'valign' => 'center'])
            ->addText('Количество часов', $this->fStyles, $this->pStyles);

        $this->table->addRow();
        $this->table->addCell($this->weekCellWidth, ['vMerge' => 'continue', 'valign' => 'center']);
        $this->table->addCell($this->contentCellWidth, ['vMerge' => 'continue', 'valign' => 'center']);
        $this->table
            ->addCell($this->hourCellWidth, ['valign' => 'center'])
            ->addText('в течение недели', $this->fStyles, $this->pStyles);
        $this->table
            ->addCell($this->hourCellWidth, ['valign' => 'center'])
            ->addText('6-й день', $this->fStyles, $this->pStyles);
    }

    protected function addRow(Report $report, $vMerge = 'continue'): void
    {
        $this->table->addRow();

        $groupCell = $this->table->addCell($this->weekCellWidth, ['vMerge' => $vMerge, 'valign' => 'center']);

        if ($vMerge === 'restart') {
            $groupCell->addText($report->week, $this->fStyles, $this->pStyles);
        }

        $this->table
            ->addCell($this->contentCellWidth, ['valign' => 'center'])
            ->addText(PhpWordPurifier::purify($report->content), $this->fStyles, [
                ...$this->pStyles,
                'alignment' => Jc::START,
            ]);
        $this->table
            ->addCell(1000, ['valign' => 'center'])
            ->addText($report->hours_per_week, $this->fStyles, $this->pStyles);
        $this->table
            ->addCell(1000, ['valign' => 'center'])
            ->addText($report->hours_saturday, $this->fStyles, $this->pStyles);
    }
}
