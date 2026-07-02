<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;
use App\Exports\ProductExport;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class ProductController extends Controller
{
    public function showproduct()
    {
        $categories = Category::orderBy('Category_Name')->get();

        return view('product', compact('categories'));
    }

    public function createproduct(Request $request)
    {
        $validatedData = $request->validate([
            'Product_Name' => 'required|string|max:255|unique:produts,Product_Name',
            'HSN' => 'nullable|string|max:255',
            'GST_per' => 'nullable|numeric|min:0',
            'Category' => 'required|string|max:255|exists:categories,Category_Name',
            'Purchase_rate' => 'nullable|numeric|min:0',
            'opening_stock' => 'nullable|integer|min:0',
            'opening_stock_value' => 'nullable|numeric|min:0',
        ]);

        Product::create($validatedData);

        return redirect()->back()->with('success', 'Product added successfully!');
    } 
    
    public function deleteproduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully!');
    }

    public function editproduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('Category_Name')->get();

        return view('edit_product', compact('product', 'categories'));
    }

    public function updateproduct(Request $request, $id)
    {
        $validatedData = $request->validate([
            'Product_Name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('produts', 'Product_Name')->ignore($id),
            ],
            'HSN' => 'nullable|string|max:255',
            'GST_per' => 'nullable|numeric|min:0',
            'Category' => 'required|string|max:255|exists:categories,Category_Name',
            'Purchase_rate' => 'nullable|numeric|min:0',
            'opening_stock' => 'nullable|integer|min:0',
            'opening_stock_value' => 'nullable|numeric|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->update($validatedData);

        return redirect()->back()->with('success', 'Product updated successfully!');
    }

    public function productList(Request $request)
    {
        $sort = $request->input('sort', 'id');
        $direction = $request->input('direction', 'asc') === 'desc' ? 'desc' : 'asc';
        $search = $request->input('search', '');
        $perPageOptions = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 10);
        $sortableColumns = ['id', 'Product_Name', 'HSN', 'Category', 'GST_per', 'Purchase_rate', 'opening_stock', 'created_at'];

        if (! in_array($sort, $sortableColumns, true)) {
            $sort = 'id';
        }

        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $query = Product::query();

        if ($search) {
            $query->where('Product_Name', 'like', "%{$search}%")
                  ->orWhere('HSN', 'like', "%{$search}%")
                  ->orWhere('Category', 'like', "%{$search}%");
        }

        $products = $query->orderBy($sort, $direction)->paginate($perPage)->withQueryString();

        return view('list_product', compact('products', 'sort', 'direction', 'search', 'perPage', 'perPageOptions'));
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

    public function product_pdf(Request $request)
    {
        $products = Product::orderBy('Product_Name')->get();
        if ($products->isEmpty()) {
            return redirect()->route('product.list')->with('error', 'No product entries available to export.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Product Master List');
        }

        $theme = $this->exportTheme($request);
        $html = view('product_pdf', compact('products', 'theme'))->render();
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('Product Master List.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }

    public function product_excel(Request $request)
    {
        if (! Product::exists()) {
            return redirect()->route('product.list')->with('error', 'No product entries available to export.');
        }

        return Excel::download(new ProductExport($this->exportTheme($request)), 'ProductMaster.xlsx');
    }

}
