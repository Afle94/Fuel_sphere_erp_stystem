<?php

namespace App\Exports;

use App\Exports\Concerns\AppliesReportHeading;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OutstandingDebtorsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithCustomStartCell, ShouldAutoSize
{
    use AppliesReportHeading;

    private array $theme;

    public function __construct(
        private Collection $rows,
        private string $asOnDate,
        private string $totalBalanceLabel,
        array $theme = []
    ) {
        $this->theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'accent' => '#f59e0b',
            'bgEnd' => '#eef5f3',
        ], $theme);
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function title(): string
    {
        return 'Outstanding Debtors';
    }

    public function headings(): array
    {
        return ['Particulars', 'Balance', 'Location', 'Mobile'];
    }

    public function map($row): array
    {
        return [
            $row->particulars,
            $row->balance_label,
            $row->location,
            $row->mobile,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $primary = strtoupper(ltrim($this->theme['primary'], '#'));
        $primaryDark = strtoupper(ltrim($this->theme['primaryDark'], '#'));
        $bgEnd = strtoupper(ltrim($this->theme['bgEnd'], '#'));

        $this->applyReportHeading($sheet, $highestColumn, $highestRow);
        $sheet->setCellValue('A3', 'As On: ' . \Carbon\Carbon::parse($this->asOnDate)->format('d M Y') . ' | Total Balance: ' . $this->totalBalanceLabel);
        $sheet->mergeCells("A3:{$highestColumn}3");
        $sheet->getStyle("A3:{$highestColumn}3")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => $primaryDark]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgEnd]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->getStyle("A4:{$highestColumn}4")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $primary]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->getStyle("A4:{$highestColumn}{$highestRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => $primaryDark]]],
            'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
        ]);

        if ($highestRow >= 5) {
            $sheet->getStyle("B5:B{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            for ($row = 5; $row <= $highestRow; $row++) {
                if ($row % 2 === 0) {
                    $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB($bgEnd);
                }
            }
        }

        $totalRow = $highestRow + 1;
        $sheet->setCellValue("A{$totalRow}", 'Total');
        $sheet->setCellValue("B{$totalRow}", $this->totalBalanceLabel);
        $sheet->getStyle("A{$totalRow}:{$highestColumn}{$totalRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgEnd]],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => $primaryDark]]],
        ]);
        $sheet->getStyle("B{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->freezePane('A5');

        return [];
    }
}
