<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Imports\DensityImport;
use App\Models\Density;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class DensityController extends Controller
{
    public function showdensityChart(Request $request)
    {
        $fuelTypes = Density::select('fuel_type')->distinct()->orderBy('fuel_type')->pluck('fuel_type');
        $totalDensityRecords = Density::count();
        $hasFilter = $request->query('filter') === '1';
        $selectedFuelType = $hasFilter ? (string) $request->query('fuel_type', '') : '';
        $sort = (string) $request->query('sort', 'temperature');
        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';
        $sortableColumns = ['id', 'temperature', 'base_dens', 'chart_val'];

        if (! in_array($sort, $sortableColumns, true)) {
            $sort = 'temperature';
        }

        if ($selectedFuelType === '') {
            $data = new LengthAwarePaginator([], 0, 25, 1, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);

            return view('density-chart', compact('data', 'fuelTypes', 'selectedFuelType', 'sort', 'direction', 'totalDensityRecords'));
        }

        $query = Density::query()
            ->where('fuel_type', $selectedFuelType)
            ->orderBy($sort, $direction)
            ->orderBy('temperature')
            ->orderBy('base_dens');

        $data = $query->paginate(25)->withQueryString();

        return view('density-chart', compact('data', 'fuelTypes', 'selectedFuelType', 'sort', 'direction', 'totalDensityRecords'));
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'density_file' => ['required', 'file', 'max:20480'],
        ]);

        $import = new DensityImport();

        try {
            Excel::import($import, $validated['density_file']);
        } catch (Throwable $exception) {
            return redirect()
                ->route('density.chart')
                ->with('error', 'Import failed. Please check the file format and column headings.');
        }

        if ($import->importedCount() === 0) {
            return redirect()
                ->route('density.chart')
                ->with('error', 'No valid rows found. Required columns: Fuel Type, Temp, Base Density, Value.');
        }

        return redirect()
            ->route('density.chart')
            ->with('success', 'File uploaded successfully.');
    }

    public function destroyAll()
    {
        $deletedCount = Density::count();

        Density::query()->delete();

        return redirect()
            ->route('density.chart')
            ->with('success', $deletedCount . ' density chart rows deleted successfully.');
    }
}
