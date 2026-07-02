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

class PurchaseExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithCustomStartCell, ShouldAutoSize
{
    use AppliesReportHeading;

    private int $serial = 0;

    public function __construct(private Collection $purchases, private string $selectedDate, private array $theme = [], private mixed $companyInformation = null)
    {
        $this->theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'bgEnd' => '#eef5f3',
            'accent' => '#f59e0b',
        ], $theme);
    }

    public function collection(): Collection
    {
        return $this->purchases;
    }

    public function title(): string
    {
        return 'Purchase';
    }

    public function headings(): array
    {
        return [
            'S.N.',
            'Product No.',
            'Particulars',
            'Qty',
            'Rate',
            'Discount %',
            'Taxable Amount',
            'CGST %',
            'SGST %',
            'IGST %',
            'Total Tax',
            'Total Amount',
        ];
    }

    public function map($purchase): array
    {
        $this->serial++;

        return [
            $this->serial,
            $purchase->item_name ?: '-',
            $purchase->item_name ?: '-',
            (float) $purchase->quantity,
            is_numeric($purchase->rate) ? (float) $purchase->rate : 0,
            (float) $purchase->{'discount%'},
            (float) $purchase->taxable_amount,
            (float) $purchase->cgst,
            (float) $purchase->sgst,
            (float) $purchase->igst,
            (float) $purchase->total_tax_amount,
            (float) $purchase->total_amount,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $primary = strtoupper(ltrim($this->theme['primary'], '#'));
        $primaryDark = strtoupper(ltrim($this->theme['primaryDark'], '#'));
        $bgEnd = strtoupper(ltrim($this->theme['bgEnd'], '#'));
        $accent = strtoupper(ltrim($this->theme['accent'], '#'));

        $this->applyReportHeading($sheet, $highestColumn, $highestRow);
        $companyName = $this->companyInformation->company_name ?? 'FuelTracker';
        $companyOffice = $this->companyInformation->registered_office ?? '';
        $companyPhone = $this->companyInformation->phone_no ?? '';
        $companyMobile = $this->companyInformation->mobile_no ?? '';
        $companyEmail = $this->companyInformation->email_id ?? '';
        $companyGstNo = $this->companyInformation->gst_no ?? '';
        $first = $this->purchases->first();
        $meta = array_filter([
            'Ref No: ' . ($first->Ref_no ?? $this->selectedDate),
            'Invoice No: ' . ($first->invoice_no ?? '-'),
            'Date: ' . (substr((string) ($first->date ?? ''), 0, 10) ?: $this->selectedDate),
            'Supplier: ' . ($first->perticulars ?? '-'),
        ]);

        $sheet->setCellValue('A1', $companyName);
        $sheet->setCellValue('A2', trim(implode(' | ', array_filter([
            $companyOffice,
            ($companyPhone || $companyMobile) ? 'Phone: ' . ($companyPhone ?: $companyMobile) : null,
            $companyEmail ? 'Email: ' . $companyEmail : null,
            $companyGstNo ? 'GSTIN: ' . $companyGstNo : null,
        ]))));
        $sheet->mergeCells("A3:{$highestColumn}3");
        $sheet->setCellValue('A3', 'Purchase Invoice | ' . implode(' | ', $meta));
        $sheet->getStyle("A3:{$highestColumn}3")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => $primaryDark], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgEnd]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => $accent]]],
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

        for ($row = 5; $row <= $highestRow; $row++) {
            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB($bgEnd);
            }
        }

        $sheet->freezePane('A5');
        $totalRow = $highestRow + 2;
        $sheet->setCellValue("H{$totalRow}", 'Subtotal');
        $sheet->setCellValue("L{$totalRow}", $this->purchases->sum(fn ($purchase) => (float) ($purchase->amount ?? 0)));
        $sheet->setCellValue("H" . ($totalRow + 1), 'Discount');
        $sheet->setCellValue("L" . ($totalRow + 1), $this->purchases->sum(fn ($purchase) => (float) ($purchase->discountinrs ?? 0)));
        $sheet->setCellValue("H" . ($totalRow + 2), 'Taxable Amount');
        $sheet->setCellValue("L" . ($totalRow + 2), $this->purchases->sum(fn ($purchase) => (float) ($purchase->taxable_amount ?? 0)));
        $sheet->setCellValue("H" . ($totalRow + 3), 'Total Tax');
        $sheet->setCellValue("L" . ($totalRow + 3), $this->purchases->sum(fn ($purchase) => (float) ($purchase->total_tax_amount ?? 0)));
        $sheet->setCellValue("H" . ($totalRow + 4), 'Total Purchase Amount');
        $sheet->setCellValue("L" . ($totalRow + 4), $this->purchases->sum(fn ($purchase) => (float) ($purchase->total_amount ?? 0)));
        $sheet->getStyle("H{$totalRow}:L" . ($totalRow + 4))->applyFromArray([
            'font' => ['bold' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => $primaryDark]]],
        ]);
        $sheet->getStyle("H" . ($totalRow + 4) . ":L" . ($totalRow + 4))->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => $primaryDark]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgEnd]],
        ]);
        $sheet->getStyle("D5:L" . ($totalRow + 4))->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle("D5:D{$highestRow}")->getNumberFormat()->setFormatCode('#,##0.000');

        return [];
    }
}


