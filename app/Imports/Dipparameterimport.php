<?php

namespace App\Imports;

use App\Models\Dipparameter;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class Dipparameterimport implements ToCollection, WithHeadingRow
{
    private int $imported = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $item = trim((string) ($row['item'] ?? $row['product'] ?? $row['fuel_type'] ?? $row['fueltype'] ?? ''));
            $depth = $this->stringValue($row['depth'] ?? $row['dip'] ?? $row['dip_depth'] ?? null);
            $liter = $this->stringValue($row['liter'] ?? $row['litre'] ?? $row['ltr'] ?? $row['liters'] ?? null);

            if ($item === '' || $depth === null || $liter === null) {
                continue;
            }

            Dipparameter::updateOrCreate(
                [
                    'item' => $item,
                    'depth' => $depth,
                ],
                [
                    'liter' => $liter,
                ]
            );

            $this->imported++;
        }
    }

    public function importedCount(): int
    {
        return $this->imported;
    }

    public function importLegacyBiff(string $path): bool
    {
        $bytes = @file_get_contents($path);

        if ($bytes === false || strlen($bytes) < 8 || substr($bytes, 0, 2) !== "\x09\x00") {
            return false;
        }

        $rows = [];
        $position = 0;
        $length = strlen($bytes);

        while ($position + 4 <= $length) {
            $recordType = unpack('v', substr($bytes, $position, 2))[1];
            $recordLength = unpack('v', substr($bytes, $position + 2, 2))[1];
            $payloadStart = $position + 4;

            if ($payloadStart + $recordLength > $length) {
                break;
            }

            $payload = substr($bytes, $payloadStart, $recordLength);
            $cell = $this->legacyBiffCellValue($recordType, $payload);

            if ($cell !== null) {
                [$row, $column, $value] = $cell;
                $rows[$row][$column] = $value;
            }

            $position = $payloadStart + $recordLength;
        }

        if ($rows === []) {
            return false;
        }

        ksort($rows);
        $headings = $this->legacyHeadings(reset($rows) ?: []);

        foreach ($rows as $rowIndex => $row) {
            if ($rowIndex === array_key_first($rows)) {
                continue;
            }

            $item = trim((string) ($row[$headings['item']] ?? ''));
            $depth = $this->stringValue($row[$headings['depth']] ?? null);
            $liter = $this->stringValue($row[$headings['liter']] ?? null);

            if ($item === '' || $depth === null || $liter === null) {
                continue;
            }

            Dipparameter::updateOrCreate(
                [
                    'item' => $item,
                    'depth' => $depth,
                ],
                [
                    'liter' => $liter,
                ]
            );

            $this->imported++;
        }

        return true;
    }

    private function stringValue(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $normalized = str_replace(',', '', trim((string) $value));

        if ($normalized === '') {
            return null;
        }

        if (is_numeric($normalized)) {
            return (string) (int) (float) $normalized;
        }

        return $normalized;
    }

    private function legacyBiffCellValue(int $recordType, string $payload): ?array
    {
        if (strlen($payload) < 7) {
            return null;
        }

        $row = unpack('v', substr($payload, 0, 2))[1];
        $column = unpack('v', substr($payload, 2, 2))[1];

        if ($recordType === 0x0004 && strlen($payload) >= 8) {
            $textLength = ord($payload[7]);
            $value = substr($payload, 8, $textLength);

            return [$row, $column, trim($value)];
        }

        if ($recordType === 0x0003 && strlen($payload) >= 15) {
            $value = unpack('e', substr($payload, 7, 8))[1];

            return [$row, $column, $this->formatLegacyNumber($value)];
        }

        if ($recordType === 0x0002 && strlen($payload) >= 9) {
            $value = unpack('v', substr($payload, 7, 2))[1];

            return [$row, $column, (string) $value];
        }

        return null;
    }

    private function legacyHeadings(array $headingRow): array
    {
        $headings = [
            'item' => 0,
            'depth' => 1,
            'liter' => 2,
        ];

        foreach ($headingRow as $column => $heading) {
            $normalized = strtolower(preg_replace('/[^a-z0-9]+/', '', (string) $heading));

            if (in_array($normalized, ['item', 'product', 'fueltype'], true)) {
                $headings['item'] = $column;
            } elseif (in_array($normalized, ['depth', 'dip', 'dipdepth'], true)) {
                $headings['depth'] = $column;
            } elseif (in_array($normalized, ['liter', 'litre', 'ltr', 'liters'], true)) {
                $headings['liter'] = $column;
            }
        }

        return $headings;
    }

    private function formatLegacyNumber(float $value): string
    {
        return (string) (int) $value;
    }
}
