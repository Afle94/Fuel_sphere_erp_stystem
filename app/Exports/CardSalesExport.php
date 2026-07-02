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

class CardSalesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithCustomStartCell, ShouldAutoSize
{
    use AppliesReportHeading;

    public function __construct(private Collection $cardsales, private string $selectedDate, private array $theme = [])
    {
        $this->theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'bgEnd' => '#eef5f3',
        ], $theme);
    }

    public function collection(): Collection
    {
        return $this->cardsales;
    }

    public function title(): string
    {
        return 'Card Sales';
    }

    public function headings(): array
    {
        return ['Date', 'Invoice No.', 'Batch No.', 'Card Type', 'Perticulars', 'Amount', 'Narration'];
    }

    public function map($sale): array
    {
        return [
            substr((string) $sale->date, 0, 10) ?: $this->selectedDate,
            $sale->invoice_no,
            $sale->Batch_no,
            $sale->Card_type,
            $sale->perticulars,
            (float) $sale->Amount,
            $sale->narration,
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

        return [];
    }
}


