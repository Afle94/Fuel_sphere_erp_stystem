<?php

namespace App\Http\Controllers;

use App\Imports\Dipparameterimport;
use App\Models\Dipparameter;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class DipparameterController extends Controller
{
    public function index(Request $request)
    {
        $items = Dipparameter::select('item')->distinct()->orderBy('item')->pluck('item');
        $totalDipparameterRecords = Dipparameter::count();
        $selectedItem = trim((string) $request->query('item', ''));
        $search = trim((string) $request->query('search', ''));
        $sort = (string) $request->query('sort', 'item');
        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';
        $sortableColumns = ['id', 'item', 'depth', 'liter'];

        if (! in_array($sort, $sortableColumns, true)) {
            $sort = 'item';
        }

        $query = Dipparameter::query();

        if ($selectedItem !== '') {
            $query->where('item', $selectedItem);
        }

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where('item', 'like', "%{$search}%")
                    ->orWhere('depth', 'like', "%{$search}%")
                    ->orWhere('liter', 'like', "%{$search}%");
            });
        }

        $data = $query->orderBy($sort, $direction)
            ->orderBy('item')
            ->orderBy('depth')
            ->paginate(25)
            ->withQueryString();

        return view('dipparameter', compact('data', 'items', 'selectedItem', 'search', 'sort', 'direction', 'totalDipparameterRecords'));
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'dipparameter_file' => ['required', 'file', 'max:20480'],
        ]);

        $import = new Dipparameterimport();

        try {
            Excel::import($import, $validated['dipparameter_file']);
        } catch (Throwable $exception) {
            $importedLegacyFile = $import->importLegacyBiff($validated['dipparameter_file']->getRealPath());

            if (! $importedLegacyFile) {
                return redirect()
                    ->route('dipparameter.index')
                    ->with('error', 'Import failed. Please check the file format and column headings.');
            }
        }

        if ($import->importedCount() === 0) {
            return redirect()
                ->route('dipparameter.index')
                ->with('error', 'No valid rows found. Required columns: Item, Depth, Liter.');
        }

        return redirect()
            ->route('dipparameter.index')
            ->with('success', 'File uploaded successfully.');
    }

    public function destroyAll()
    {
        $deletedCount = Dipparameter::count();

        Dipparameter::query()->delete();

        return redirect()
            ->route('dipparameter.index')
            ->with('success', $deletedCount . ' dip parameter rows deleted successfully.');
    }
}
