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

class PurchaseSampleExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithCustomStartCell, ShouldAutoSize
{
    use AppliesReportHeading;

    public function __construct(private Collection $purchaseSamples, private string $selectedDate, private array $theme = [])
    {
        $this->theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'bgEnd' => '#eef5f3',
        ], $theme);
    }

    public function collection(): Collection
    {
        return $this->purchaseSamples;
    }

    public function title(): string
    {
        return 'Purchase Sample';
    }

    public function headings(): array
    {
        return [
            'Date',
            'Tanker',
            'Transport',
            'Oil Company',
            'Invoice No.',
            'Product',
            'HSD Temp',
            'HSD Base Density',
            'HSD Value',
            'HSD Sample',
            'HSD Invoice Sample',
            'HSD Plastic Seal',
            'HSD Aluminium Seal',
            'MS Temp',
            'MS Base Density',
            'MS Value',
            'MS Sample',
            'MS Invoice Sample',
            'MS Plastic Seal',
            'MS Aluminium Seal',
            'Power MS Temp',
            'Power MS Base Density',
            'Power MS Value',
            'Power MS Sample',
            'Power MS Invoice Sample',
            'Power MS Plastic Seal',
            'Power MS Aluminium Seal',
        ];
    }

    public function map($sample): array
    {
        return [
            optional($sample->date)->format('Y-m-d') ?: $this->selectedDate,
            $sample->tanker,
            $sample->transport,
            $sample->oil_company,
            $sample->invoice_no,
            $sample->product,
            (float) $sample->hsd_temp,
            (float) $sample->hsd_base_density,
            (float) $sample->hsd_value,
            $sample->hsd_sample,
            $sample->hsd_invoice_sample,
            $sample->hsd_plastic_seal,
            $sample->hsd_aluminium_seal,
            (float) $sample->ms_temp,
            (float) $sample->ms_base_density,
            (float) $sample->ms_value,
            $sample->ms_sample,
            $sample->ms_invoice_sample,
            $sample->ms_plastic_seal,
            $sample->ms_aluminium_seal,
            (float) $sample->power_ms_temp,
            (float) $sample->power_ms_base_density,
            (float) $sample->power_ms_value,
            $sample->power_ms_sample,
            $sample->power_ms_invoice_sample,
            $sample->power_ms_plastic_seal,
            $sample->power_ms_aluminium_seal,
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
