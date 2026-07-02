<?php

namespace App\Exports;

use App\Exports\Concerns\AppliesReportHeading;
use App\Models\Vehicles;
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

class VehicleExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithCustomStartCell, ShouldAutoSize
{
    use AppliesReportHeading;

    public function __construct(private array $theme = [])
    {
        $this->theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'bgEnd' => '#eef5f3',
        ], $theme);
    }

    public function collection()
    {
        return Vehicles::orderBy('Party_name')->get();
    }

    public function title(): string
    {
        return 'Vehicle Master';
    }

    public function headings(): array
    {
        return [
            'ID',
            'Party Name',
            'Vehicle No.',
            'Created Date',
            'Updated Date',
        ];
    }

    public function map($vehicle): array
    {
        return [
            $vehicle->id,
            $vehicle->Party_name,
            $vehicle->Vehicle_no,
            optional($vehicle->created_at)->format('d M Y') ?: '-',
            optional($vehicle->updated_at)->format('d M Y') ?: '-',
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
            $sheet->getStyle("A5:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

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


