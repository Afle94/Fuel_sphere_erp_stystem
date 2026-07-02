<?php

namespace App\Http\Controllers;

use App\Exports\ItemDateRateExport;
use App\Models\ItemDateRate;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class ItemDateRateController extends Controller
{
    public function show()
    {
        $products = Product::orderBy('Product_Name')->get();

        return view('item_date_rate', compact('products'));
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateItemDateRate($request);

        $rate = ItemDateRate::updateOrCreate(
            [
                'rate_date' => $validatedData['rate_date'],
                'product_id' => $validatedData['product_id'],
            ],
            ['rate' => $validatedData['rate']]
        );

        $message = $rate->wasRecentlyCreated
            ? 'Item date wise rate added successfully!'
            : 'Same date par existing item rate update ho gaya.';

        return redirect()->back()->with('success', $message);
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $sort = $request->query('sort', 'rate_date');
        $direction = $request->query('direction', 'asc') === 'desc' ? 'desc' : 'asc';
        $perPageOptions = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 10);
        $sortableColumns = ['id', 'rate_date', 'product_name', 'rate', 'created_at'];

        if (! in_array($sort, $sortableColumns, true)) {
            $sort = 'rate_date';
        }

        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $query = ItemDateRate::query()
            ->with('product')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('rate_date', 'like', "%{$search}%")
                    ->orWhere('rate', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($productQuery) use ($search) {
                        $productQuery->where('Product_Name', 'like', "%{$search}%");
                    });
            });

        if ($sort === 'product_name') {
            $query->leftJoin('produts', 'item_date_rates.product_id', '=', 'produts.id')
                ->select('item_date_rates.*')
                ->orderBy('produts.Product_Name', $direction);
        } else {
            $query->orderBy("item_date_rates.{$sort}", $direction);
        }

        $itemDateRates = $query->paginate($perPage)->withQueryString();

        return view('list_item_date_rate', compact('itemDateRates', 'search', 'sort', 'direction', 'perPage', 'perPageOptions'));
    }

    public function edit($id)
    {
        $itemDateRate = ItemDateRate::findOrFail($id);
        $products = Product::orderBy('Product_Name')->get();

        return view('edit_item_date_rate', compact('itemDateRate', 'products'));
    }

    public function update(Request $request, $id)
    {
        $itemDateRate = ItemDateRate::findOrFail($id);
        $validatedData = $this->validateItemDateRate($request);

        DB::transaction(function () use ($itemDateRate, $validatedData) {
            $existingRate = ItemDateRate::query()
                ->where('rate_date', $validatedData['rate_date'])
                ->where('product_id', $validatedData['product_id'])
                ->where('id', '<>', $itemDateRate->id)
                ->first();

            if ($existingRate) {
                $existingRate->update(['rate' => $validatedData['rate']]);
                $itemDateRate->delete();

                return;
            }

            $itemDateRate->update($validatedData);
        });

        return redirect()->route('item-date-rates.list')->with('success', 'Item date wise rate updated successfully!');
    }

    public function destroy($id)
    {
        $itemDateRate = ItemDateRate::findOrFail($id);
        $itemDateRate->delete();

        return redirect()->route('item-date-rates.list')->with('success', 'Item date wise rate deleted successfully!');
    }

    public function pdf(Request $request)
    {
        $itemDateRates = ItemDateRate::with('product')
            ->leftJoin('produts', 'item_date_rates.product_id', '=', 'produts.id')
            ->select('item_date_rates.*')
            ->orderBy('item_date_rates.rate_date')
            ->orderBy('produts.Product_Name')
            ->get();
        if ($itemDateRates->isEmpty()) {
            return redirect()->route('item-date-rates.list')->with('error', 'No item date wise rate entries available to export.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Item Date Wise Rate List');
        }

        $theme = $this->exportTheme($request);
        $html = view('item_date_rate_pdf', compact('itemDateRates', 'theme'))->render();
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('Item Date Wise Rate List.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }

    public function excel(Request $request)
    {
        if (! ItemDateRate::exists()) {
            return redirect()->route('item-date-rates.list')->with('error', 'No item date wise rate entries available to export.');
        }

        return Excel::download(new ItemDateRateExport($this->exportTheme($request)), 'ItemDateWiseRates.xlsx');
    }

    private function validateItemDateRate(Request $request): array
    {
        return $request->validate([
            'rate_date' => 'required|date',
            'product_id' => 'required|integer|exists:produts,id',
            'rate' => 'required|numeric|min:0',
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
}
