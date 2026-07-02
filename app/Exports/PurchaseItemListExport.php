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

class PurchaseItemListExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithCustomStartCell, ShouldAutoSize
{
    use AppliesReportHeading;

    private array $theme;

    public function __construct(
        private Collection $purchaseItems,
        private string $periodLabel,
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
        return $this->purchaseItems;
    }

    public function title(): string
    {
        return 'Purchase Item List';
    }

    public function headings(): array
    {
        return [
            'Item Code',
            'Particulars',
            'Qty.',
            'Rate',
            'Amount',
            'Discount %',
            'Discount',
            'Taxable Amt.',
            'Total Amount',
            'CGST %',
            'SGST %',
            'IGST %',
            'Total Tax',
        ];
    }

    public function map($purchaseItem): array
    {
        return [
            $purchaseItem->item_name ?: '-',
            $purchaseItem->item_name ?: '-',
            (float) ($purchaseItem->quantity ?? 0),
            (float) ($purchaseItem->rate ?? 0),
            (float) ($purchaseItem->amount ?? 0),
            (float) ($purchaseItem->{'discount%'} ?? 0),
            (float) ($purchaseItem->discountinrs ?? 0),
            (float) ($purchaseItem->taxable_amount ?? 0),
            (float) ($purchaseItem->total_amount ?? 0),
            (float) ($purchaseItem->cgst ?? 0),
            (float) ($purchaseItem->sgst ?? 0),
            (float) ($purchaseItem->igst ?? 0),
            (float) ($purchaseItem->total_tax_amount ?? 0),
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
        $meta = 'Period: ' . $this->periodLabel;

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
            $sheet->getStyle("C5:M{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("C5:C{$highestRow}")->getNumberFormat()->setFormatCode('#,##0.000');
            $sheet->getStyle("D5:M{$highestRow}")->getNumberFormat()->setFormatCode('#,##0.00');

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
        $sheet->setCellValue("C{$totalRow}", $this->purchaseItems->sum(fn ($item) => (float) ($item->quantity ?? 0)));
        $sheet->setCellValue("E{$totalRow}", $this->purchaseItems->sum(fn ($item) => (float) ($item->amount ?? 0)));
        $sheet->setCellValue("G{$totalRow}", $this->purchaseItems->sum(fn ($item) => (float) ($item->discountinrs ?? 0)));
        $sheet->setCellValue("H{$totalRow}", $this->purchaseItems->sum(fn ($item) => (float) ($item->taxable_amount ?? 0)));
        $sheet->setCellValue("I{$totalRow}", $this->purchaseItems->sum(fn ($item) => (float) ($item->total_amount ?? 0)));
        $sheet->setCellValue("M{$totalRow}", $this->purchaseItems->sum(fn ($item) => (float) ($item->total_tax_amount ?? 0)));
        $sheet->getStyle("A{$totalRow}:{$highestColumn}{$totalRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgEnd]],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => $primaryDark]]],
        ]);
        $sheet->getStyle("C{$totalRow}:M{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->freezePane('A5');

        return [];
    }
}
