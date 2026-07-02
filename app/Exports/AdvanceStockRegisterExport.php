<?php

namespace App\Exports;

use App\Exports\Concerns\AppliesReportHeading;
use Carbon\Carbon;
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

class AdvanceStockRegisterExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithCustomStartCell, ShouldAutoSize
{
    use AppliesReportHeading;

    private array $theme;

    public function __construct(
        private Collection $rows,
        private array $totals,
        private string $periodLabel,
        private string $selectedProduct,
        private string $search,
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
        return 'Advance Stock Register';
    }

    public function headings(): array
    {
        return [
            'Date',
            'Opening Stock',
            'Receipt',
            'Total Stock',
            'Sales By Meters',
            'Pump Test',
            'Net Sales By Meters',
            'Cumulative Sales',
            'Sales By Dip',
            'Variation Daily',
            'Variation Cumm',
        ];
    }

    public function map($row): array
    {
        return [
            Carbon::parse($row['date'])->format('d M Y'),
            $this->whole($row['opening_stock']),
            $this->whole($row['receipt']),
            $this->whole($row['total_stock']),
            $this->whole($row['sales_by_meters']),
            $this->whole($row['pump_test']),
            $this->whole($row['net_sales_by_meters']),
            $this->whole($row['cumulative_sales']),
            $row['sales_by_dip'] === null ? '-' : $this->whole($row['sales_by_dip']),
            $row['daily_variation'] === null ? '-' : $this->whole($row['daily_variation']),
            $this->whole($row['cumulative_variation']),
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
        $meta = 'Product: ' . $this->selectedProduct . ' | Period: ' . $this->periodLabel;

        if ($this->search !== '') {
            $meta .= ' | Search: ' . $this->search;
        }

        $sheet->setCellValue('A3', $meta);
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
            $sheet->getStyle("B5:K{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

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
        $sheet->setCellValue("B{$totalRow}", $this->whole($this->totals['opening_stock']));
        $sheet->setCellValue("C{$totalRow}", $this->whole($this->totals['receipt']));
        $sheet->setCellValue("D{$totalRow}", $this->whole($this->totals['total_stock']));
        $sheet->setCellValue("E{$totalRow}", $this->whole($this->totals['sales_by_meters']));
        $sheet->setCellValue("F{$totalRow}", $this->whole($this->totals['pump_test']));
        $sheet->setCellValue("G{$totalRow}", $this->whole($this->totals['net_sales_by_meters']));
        $sheet->setCellValue("I{$totalRow}", $this->whole($this->totals['sales_by_dip']));
        $sheet->setCellValue("J{$totalRow}", $this->whole($this->totals['daily_variation']));
        $sheet->setCellValue("K{$totalRow}", $this->whole($this->totals['cumulative_variation']));
        $sheet->getStyle("A{$totalRow}:{$highestColumn}{$totalRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgEnd]],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => $primaryDark]]],
        ]);
        $sheet->getStyle("B{$totalRow}:K{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->freezePane('A5');

        return [];
    }

    private function whole($value): int
    {
        return (int) round((float) $value);
    }
}
