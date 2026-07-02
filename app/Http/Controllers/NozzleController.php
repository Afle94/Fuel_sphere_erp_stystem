<?php

namespace App\Http\Controllers;

use App\Models\Nozzle;
use App\Models\Product;
use App\Exports\NozzleExport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class NozzleController extends Controller
{
    public function shownozzle()
    {
        $products = Product::orderBy('Product_Name')->get();

        return view('nozzle', compact('products'));
    }

    public function createnozzle(Request $request)
    {
        $validatedData = $this->validateNozzle($request);

        Nozzle::create($validatedData);

        return redirect()->back()->with('success', 'Nozzle created successfully.');
    }

    public function listnozzle(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $sort = $request->query('sort', 'id');
        $direction = $request->query('direction', 'asc') === 'desc' ? 'desc' : 'asc';
        $perPageOptions = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 10);
        $sortableColumns = ['id', 'Nozzle_Name', 'Item', 'Open_Date', 'Close_Date', 'created_at'];

        if (! in_array($sort, $sortableColumns, true)) {
            $sort = 'id';
        }

        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $nozzles = Nozzle::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('Nozzle_Name', 'like', "%{$search}%")
                    ->orWhere('Item', 'like', "%{$search}%");
            })
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return view('list_nozzle', compact('nozzles', 'search', 'sort', 'direction', 'perPage', 'perPageOptions'));
    }

    public function editnozzle($id)
    {
        $nozzle = Nozzle::findOrFail($id);
        $products = Product::orderBy('Product_Name')->get();

        return view('edit_nozzle', compact('nozzle', 'products'));
    }

    public function updatenozzle(Request $request, $id)
    {
        $nozzle = Nozzle::findOrFail($id);
        $validatedData = $this->validateNozzle($request, $nozzle->id);

        $nozzle->update($validatedData);

        return redirect()->route('nozzle.list')->with('success', 'Nozzle updated successfully.');
    }

    public function deletenozzle($id)
    {
        $nozzle = Nozzle::findOrFail($id);
        $nozzle->delete();

        return redirect()->back()->with('success', 'Nozzle deleted successfully.');
    }

    private function validateNozzle(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'Nozzle_Name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('nozzles', 'Nozzle_Name')->ignore($ignoreId),
            ],
            'Item' => ['required', 'string', 'max:50', 'exists:produts,Product_Name'],
            'Open_Date' => ['nullable', 'date'],
            'Close_Date' => ['nullable', 'date', 'after_or_equal:Open_Date'],
        ]);
    }

    private function exportTheme(Request $request): array
    {
        $themes = [
            'default' => ['primary' => '#0f766e', 'primaryDark' => '#115e59', 'accent' => '#f59e0b', 'bgEnd' => '#eef5f3'],
            'ocean' => ['primary' => '#0369a1', 'primaryDark' => '#075985', 'accent' => '#14b8a6', 'bgEnd' => '#edf7fb'],
            'royal' => ['primary' => '#4338ca', 'primaryDark' => '#3730a3', 'accent' => '#f59e0b', 'bgEnd' => '#f1f2ff'],
            'rose' => ['primary' => '#be123c', 'primaryDark' => '#9f1239', 'accent' => '#0f766e', 'bgEnd' => '#fff1f4'],
            'charcoal' => ['primary' => '#334155', 'primaryDark' => '#1e293b', 'accent' => '#d97706', 'bgEnd' => '#eef2f7'],
            'sunset-sky' => ['primary' => '#ea580c', 'primaryDark' => '#c2410c', 'accent' => '#be123c', 'bgEnd' => '#ffe4d6'],
            'royal-print' => ['primary' => '#4c1d95', 'primaryDark' => '#3b0764', 'accent' => '#f59e0b', 'bgEnd' => '#f5f0ff'],
            'peacock-print' => ['primary' => '#0f766e', 'primaryDark' => '#134e4a', 'accent' => '#0891b2', 'bgEnd' => '#ecfeff'],
            'marigold-print' => ['primary' => '#b45309', 'primaryDark' => '#92400e', 'accent' => '#be123c', 'bgEnd' => '#fff7ed'],
            'velvet-print' => ['primary' => '#9d174d', 'primaryDark' => '#831843', 'accent' => '#7c3aed', 'bgEnd' => '#fdf2f8'],
        ];

        $themeName = $request->query('theme', 'default');
        $theme = $themes[$themeName] ?? $themes['default'];
        $theme['name'] = array_key_exists($themeName, $themes) ? $themeName : 'default';

        return $theme;
    }

    public function nozzle_pdf(Request $request)
    {
        $nozzles = Nozzle::orderBy('Nozzle_Name')->get();
        if ($nozzles->isEmpty()) {
            return redirect()->route('nozzle.list')->with('error', 'No nozzle entries available to export.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Nozzle Master List');
        }

        $theme = $this->exportTheme($request);
        $html = view('nozzle_pdf', compact('nozzles', 'theme'))->render();
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('Nozzle Master List.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }

    public function nozzle_excel(Request $request)
    {
        if (! Nozzle::exists()) {
            return redirect()->route('nozzle.list')->with('error', 'No nozzle entries available to export.');
        }

        return Excel::download(new NozzleExport($this->exportTheme($request)), 'NozzleMaster.xlsx');
    }
}
