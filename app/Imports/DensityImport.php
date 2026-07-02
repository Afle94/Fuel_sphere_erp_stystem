<?php

namespace App\Imports;

use App\Models\Density;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DensityImport implements ToCollection, WithHeadingRow
{
    private int $imported = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $fuelType = trim((string) ($row['fuel_type'] ?? $row['fueltype'] ?? ''));
            $temperature = $this->numericValue($row['temp'] ?? $row['temperature'] ?? null);
            $baseDensity = $this->numericValue($row['base_density'] ?? $row['base_dens'] ?? $row['basedensity'] ?? null);
            $chartValue = $this->numericValue($row['value'] ?? $row['chart_val'] ?? $row['chart_value'] ?? null);

            if ($fuelType === '' || $temperature === null || $baseDensity === null || $chartValue === null) {
                continue;
            }

            Density::updateOrCreate(
                [
                    'fuel_type' => $fuelType,
                    'temperature' => $temperature,
                    'base_dens' => $baseDensity,
                ],
                [
                    'chart_val' => $chartValue,
                ]
            );

            $this->imported++;
        }
    }

    public function importedCount(): int
    {
        return $this->imported;
    }

    private function numericValue(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        $normalized = str_replace(',', '', trim((string) $value));

        return is_numeric($normalized) ? (float) $normalized : null;
    }
}
