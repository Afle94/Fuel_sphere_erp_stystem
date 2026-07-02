<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProductExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithCustomStartCell, WithEvents, ShouldAutoSize
{
    public function __construct(private array $theme = [])
    {
        $this->theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'accent' => '#f59e0b',
            'bgEnd' => '#eef5f3',
        ], $theme);
    }

    public function collection()
    {
        return Product::orderBy('Product_Name')->get();
    }

    public function title(): string
    {
        return 'Product Master';
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function headings(): array
    {
        return [
            'ID',
            'Product Name',
            'HSN Code',
            'Category',
            'GST Rate (%)',
            'Purchase Rate',
            'Opening Stock',
            'Opening Stock Value',
            'Created Date',
            'Updated Date',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->Product_Name,
            $product->HSN ?: '-',
            $product->Category,
            $product->GST_per !== null ? (float) $product->GST_per : '-',
            $product->Purchase_rate !== null ? (float) $product->Purchase_rate : '-',
            $product->opening_stock !== null ? (int) $product->opening_stock : '-',
            $product->opening_stock_value !== null ? (float) $product->opening_stock_value : '-',
            optional($product->created_at)->format('d M Y') ?: '-',
            optional($product->updated_at)->format('d M Y') ?: '-',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = 'J';
                $primary = strtoupper(ltrim($this->theme['primary'], '#'));
                $primaryDark = strtoupper(ltrim($this->theme['primaryDark'], '#'));
                $accent = strtoupper(ltrim($this->theme['accent'], '#'));
                $bgEnd = strtoupper(ltrim($this->theme['bgEnd'], '#'));

                $sheet->mergeCells('A1:J1');
                $sheet->mergeCells('A2:J2');
                $sheet->setCellValue('A1', 'Product Master List');
                $sheet->setCellValue('A2', 'Generated on ' . now()->format('d M Y h:i A') . ' | Total Entries: ' . max(0, $highestRow - 4));
                $sheet->getPageSetup()->setHorizontalCentered(true);

                $sheet->getStyle('A1:J1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 16,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $primaryDark],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A2:J2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => $primaryDark],
                        'size' => 10,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $bgEnd],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle("A4:{$highestColumn}4")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $primary],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                $sheet->getStyle("A4:{$highestColumn}{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => $primaryDark],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_TOP,
                        'wrapText' => true,
                    ],
                ]);

                if ($highestRow >= 5) {
                    $sheet->getStyle("A5:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("E5:H{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("E5:F{$highestRow}")->getNumberFormat()->setFormatCode('0.00');
                    $sheet->getStyle("G5:G{$highestRow}")->getNumberFormat()->setFormatCode('0');
                    $sheet->getStyle("H5:H{$highestRow}")->getNumberFormat()->setFormatCode('0.00');

                    for ($row = 5; $row <= $highestRow; $row++) {
                        if ($row % 2 === 1) {
                            $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB($bgEnd);
                        }
                    }
                }

                $sheet->getStyle('A1:J2')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => $accent],
                        ],
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(28);
                $sheet->getRowDimension(2)->setRowHeight(22);
                $sheet->getRowDimension(4)->setRowHeight(26);
                $sheet->freezePane('A5');
                $sheet->setAutoFilter("A4:{$highestColumn}{$highestRow}");
            },
        ];
    }
}

