<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DayFuelExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithCustomStartCell, WithEvents, ShouldAutoSize
{
    public function __construct(
        private Collection $entries,
        private string $selectedDate,
        private array $theme = []
    ) {
        $this->theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'bgEnd' => '#eef5f3',
        ], $theme);
    }

    public function collection()
    {
        return $this->entries;
    }

    public function title(): string
    {
        return 'Day Fuel Sales';
    }

    public function headings(): array
    {
        return [
            'Date',
            'Nozzle Name',
            'Item',
            'Opening Reading',
            'Closing Reading',
            'Test',
            'Quantity',
            'Rate',
            'Amount',
        ];
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function map($dayFuel): array
    {
        return [
            optional($dayFuel->date)->format('d M Y') ?: $this->selectedDate,
            optional($dayFuel->Nozzle)->Nozzle_Name ?: '-',
            $dayFuel->items ?: '-',
            number_format((float) $dayFuel->open, 2, '.', ''),
            number_format((float) $dayFuel->close, 2, '.', ''),
            number_format((float) $dayFuel->Test, 2, '.', ''),
            number_format((float) $dayFuel->Quantity, 2, '.', ''),
            number_format((float) $dayFuel->rate, 2, '.', ''),
            number_format((float) $dayFuel->Amount, 2, '.', ''),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $primary = strtoupper(ltrim($this->theme['primary'], '#'));
        $primaryDark = strtoupper(ltrim($this->theme['primaryDark'], '#'));
        $bgEnd = strtoupper(ltrim($this->theme['bgEnd'], '#'));

        $sheet->getStyle("A4:{$highestColumn}4")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $primary]],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle("A4:{$highestColumn}{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => $primaryDark],
                ],
            ],
            'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
        ]);

        if ($highestRow >= 5) {
            $sheet->getStyle("A5:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D5:I{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            for ($row = 5; $row <= $highestRow; $row++) {
                if ($row % 2 === 0) {
                    $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB($bgEnd);
                }
            }
        }

        $sheet->getRowDimension(4)->setRowHeight(24);
        $sheet->freezePane('A5');

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $primaryDark = strtoupper(ltrim($this->theme['primaryDark'], '#'));
                $accent = strtoupper(ltrim($this->theme['accent'] ?? '#f59e0b', '#'));
                $formattedDate = date('d M Y', strtotime($this->selectedDate));

                $sheet->mergeCells('A1:I1');
                $sheet->mergeCells('A2:I2');

                $sheet->setCellValue('A1', 'Day Fuel Sales');
                $sheet->setCellValue('A2', 'Date: ' . $formattedDate . ' | Generated on ' . now()->format('d M Y h:i A') . ' | Total Entries: ' . max(0, $sheet->getHighestRow() - 4));
                $sheet->getPageSetup()->setHorizontalCentered(true);

                $sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 18],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $primaryDark]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->getStyle('A2:I2')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $primaryDark]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->getStyle('A3:I3')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $accent]],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(28);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(3)->setRowHeight(5);
            },
        ];
    }
}

