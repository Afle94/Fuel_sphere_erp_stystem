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

class AccountLedgerExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithCustomStartCell, ShouldAutoSize
{
    use AppliesReportHeading;

    private array $theme;

    public function __construct(
        private Collection $rows,
        private string $accountParticular,
        private string $underGroup,
        private string $fromDate,
        private string $toDate,
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
        $totalDebit = (float) $this->rows->sum('debit');
        $totalCredit = (float) $this->rows->sum('credit');
        $closingBalance = (float) ($this->rows->last()?->balance ?? 0);

        return $this->rows
            ->concat([
                (object) [
                    'is_summary_row' => true,
                    'summary_label' => 'Total',
                    'debit' => $totalDebit,
                    'credit' => $totalCredit,
                    'balance_label' => number_format(abs($totalDebit - $totalCredit), 2) . ' ' . ($totalDebit >= $totalCredit ? 'Dr' : 'Cr'),
                ],
                (object) [
                    'is_summary_row' => true,
                    'summary_label' => 'Closing Balance',
                    'debit' => null,
                    'credit' => null,
                    'balance_label' => number_format(abs($closingBalance), 2) . ' ' . ($closingBalance >= 0 ? 'Dr' : 'Cr'),
                ],
            ]);
    }

    public function title(): string
    {
        return 'Account Ledger';
    }

    public function headings(): array
    {
        return ['Date', 'Particular', 'Vehicle No', 'Debit', 'Credit', 'Balance'];
    }

    public function map($row): array
    {
        if ($row->is_summary_row ?? false) {
            return [
                '',
                $row->summary_label,
                '',
                $row->debit === null ? '' : number_format((float) $row->debit, 2, '.', ''),
                $row->credit === null ? '' : number_format((float) $row->credit, 2, '.', ''),
                $row->balance_label ?? '-',
            ];
        }

        return [
            $row->TRANDATE ? \Carbon\Carbon::parse($row->TRANDATE)->format('d M Y') : '-',
            $row->particular_label ?? '-',
            $row->vehicle_no_label ?? '-',
            $row->debit > 0 ? number_format($row->debit, 2, '.', '') : '-',
            $row->credit > 0 ? number_format($row->credit, 2, '.', '') : '-',
            $row->balance_label ?? '-',
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
        $sheet->setCellValue('A3', "Particular: {$this->accountParticular} | Under Group: {$this->underGroup} | From: {$this->fromDate} | To: {$this->toDate}");
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
            $sheet->getStyle("D5:F{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            for ($row = 5; $row <= $highestRow; $row++) {
                if ($row % 2 === 0) {
                    $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB($bgEnd);
                }
            }

            $summaryStartRow = max(5, $highestRow - 1);
            $sheet->getStyle("A{$summaryStartRow}:{$highestColumn}{$highestRow}")->getFont()->setBold(true);
        }

        $sheet->freezePane('A5');

        return [];
    }
}
