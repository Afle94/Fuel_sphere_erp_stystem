<?php

namespace App\Exports;

use App\Exports\Concerns\AppliesReportHeading;
use App\Models\Bill;
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

class BillListExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithCustomStartCell, ShouldAutoSize
{
    use AppliesReportHeading;

    public function __construct(private string $search = '', private array $theme = [])
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
        $bills = Bill::query()
            ->with('items:id,bill_id,vehicle_no,amount')
            ->withCount('items')
            ->when($this->search !== '', function ($query) {
                $query->where(function ($query) {
                    $query->where('bill_no', 'like', "%{$this->search}%")
                        ->orWhere('party', 'like', "%{$this->search}%")
                        ->orWhere('vehicle_no', 'like', "%{$this->search}%")
                        ->orWhereHas('items', function ($query) {
                            $query->where('vehicle_no', 'like', "%{$this->search}%");
                        })
                        ->orWhere('bill_date', 'like', "%{$this->search}%")
                        ->orWhere('date_from', 'like', "%{$this->search}%")
                        ->orWhere('date_to', 'like', "%{$this->search}%")
                        ->orWhere('total_amount', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->get();

        return $bills->flatMap(function (Bill $bill) {
            if ($bill->items->isEmpty()) {
                return collect([(object) [
                    'bill' => $bill,
                    'vehicle_no' => $bill->vehicle_no ?: '-',
                    'slips' => $bill->items_count,
                    'total_amount' => (float) $bill->total_amount,
                ]]);
            }

            return $bill->items->map(fn ($item) => (object) [
                'bill' => $bill,
                'vehicle_no' => $item->vehicle_no ?: '-',
                'slips' => 1,
                'total_amount' => (float) $item->amount,
            ]);
        });
    }

    public function title(): string
    {
        return 'Saved Bills';
    }

    public function headings(): array
    {
        return [
            'Bill No',
            'Bill Date',
            'Party',
            'Vehicle No',
            'Date From',
            'Date To',
            'Slips',
            'Total',
            'Saved On',
        ];
    }

    public function map($row): array
    {
        $bill = $row->bill;

        return [
            $bill->bill_no ?: '-',
            optional($bill->bill_date)->format('d/m/Y') ?: '-',
            $bill->party ?: '-',
            $row->vehicle_no ?: '-',
            optional($bill->date_from)->format('d/m/Y') ?: '-',
            optional($bill->date_to)->format('d/m/Y') ?: '-',
            $row->slips,
            number_format((float) $row->total_amount, 2),
            optional($bill->created_at)->format('d/m/Y') ?: '-',
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
            $sheet->getStyle("G5:H{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

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
}
