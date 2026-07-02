<?php

namespace App\Exports\Concerns;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait AppliesReportHeading
{
    public function startCell(): string
    {
        return 'A4';
    }

    protected function applyReportHeading(Worksheet $sheet, string $highestColumn, int $highestRow): void
    {
        $primaryDark = strtoupper(ltrim($this->theme['primaryDark'] ?? '#115e59', '#'));
        $accent = strtoupper(ltrim($this->theme['accent'] ?? '#f59e0b', '#'));
        $bgEnd = strtoupper(ltrim($this->theme['bgEnd'] ?? '#eef5f3', '#'));
        $reportTitle = str_ends_with(strtolower($this->title()), 'list') ? $this->title() : $this->title() . ' List';
        $metaParts = [];

        if (property_exists($this, 'selectedDate')) {
            $metaParts[] = 'Date: ' . $this->selectedDate;
        }

        $metaParts[] = 'Generated on ' . now()->format('d M Y h:i A');
        $metaParts[] = 'Total Entries: ' . max(0, $highestRow - 4);

        $sheet->mergeCells("A1:{$highestColumn}1");
        $sheet->mergeCells("A2:{$highestColumn}2");
        $sheet->setCellValue('A1', $reportTitle);
        $sheet->setCellValue('A2', implode(' | ', $metaParts));
        $sheet->getPageSetup()->setHorizontalCentered(true);

        $sheet->getStyle("A1:{$highestColumn}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 16],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $primaryDark]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getStyle("A2:{$highestColumn}2")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => $primaryDark], 'size' => 10],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgEnd]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getStyle("A1:{$highestColumn}2")->applyFromArray([
            'borders' => [
                'outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => $accent]],
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(28);
        $sheet->getRowDimension(2)->setRowHeight(22);
    }
}
