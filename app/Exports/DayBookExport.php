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

class DayBookExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithCustomStartCell, ShouldAutoSize
{
    use AppliesReportHeading;

    public function __construct(private array $dayBookData, private string $selectedDate, private array $theme = [])
    {
        $this->theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'bgEnd' => '#eef5f3',
        ], $theme);
    }

    public function collection(): Collection
    {
        $summaryRows = collect($this->dayBookData)
            ->except('ItemsMatrix')
            ->map(fn ($amount, $particular) => [
                'particular' => $particular,
                'amount' => $amount,
            ])
            ->values();

        $itemRows = collect($this->dayBookData['ItemsMatrix'] ?? [])
            ->map(fn ($itemData, $itemName) => [
                'item_name' => $itemName,
                'quantity' => $itemData['quantity'] ?? 0,
                'item_amount' => $itemData['amount'] ?? 0,
            ])
            ->values();

        $rowCount = max($summaryRows->count(), $itemRows->count());
        $formattedData = collect();

        for ($index = 0; $index < $rowCount; $index++) {
            $summary = $summaryRows->get($index, []);
            $item = $itemRows->get($index, []);

            $formattedData->push([
                'particular' => $summary['particular'] ?? '',
                'amount' => $summary['amount'] ?? null,
                'item_name' => $item['item_name'] ?? '',
                'quantity' => $item['quantity'] ?? null,
                'item_amount' => $item['item_amount'] ?? null,
            ]);
        }

        return $formattedData;
    }

    public function title(): string
    {
        return 'Day Book Register';
    }

    public function headings(): array
    {
        return ['Particulars', 'Amount (Rs)', '', 'Item Name', 'Quantity', 'Amount'];
    }

    public function map($row): array
    {
        return [
            $row['particular'],
            $row['amount'] === null ? '' : (float) $row['amount'],
            '',
            $row['item_name'],
            $row['quantity'] === null ? '' : (float) $row['quantity'],
            $row['item_amount'] === null ? '' : (float) $row['item_amount'],
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

        $sheet->getStyle("A4:B4")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $primary]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->getStyle("D4:F4")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $primary]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->getStyle("A4:B{$highestRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => $primaryDark]]],
            'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
        ]);

        $sheet->getStyle("D4:F{$highestRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => $primaryDark]]],
            'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
        ]);

        $sheet->getStyle("B5:B{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("E5:F{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("C4:C{$highestRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFFFF');

        for ($row = 5; $row <= $highestRow; $row++) {
            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:B{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB($bgEnd);
                $sheet->getStyle("D{$row}:F{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB($bgEnd);
            }
        }

        $sheet->getStyle("A5:B5")->getFont()->setBold(true);
        for ($row = 5; $row <= $highestRow; $row++) {
            if ($sheet->getCell("A{$row}")->getValue() === 'Closing Cash') {
                $sheet->getStyle("A{$row}:B{$row}")->getFont()->setBold(true);
                break;
            }
        }
        $sheet->getStyle("D5:D{$highestRow}")->getFont()->setBold(true);

        $sheet->freezePane('A5');

        return [];
    }
}
