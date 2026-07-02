<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Exports\CategoryExport;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class CategoryController extends Controller
{
    public function showcategory()
    {
        return view('category');
    }

    public function createcategory(Request $request)
    {
        $request->validate([
            'Category_Name' => 'required|unique:categories,Category_Name',
        ]);

        Category::create([
            'Category_Name' => $request->input('Category_Name'),
        ]);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function deletecategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully.');
    }

    public function editcategory($id)
    {
        $category = Category::findOrFail($id);
        return view('edit_category', compact('category'));
    }

    public function updatecategory(Request $request, $id)
    {
        $request->validate([
            'Category_Name' => 'required|unique:categories,Category_Name,' . $id,
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'Category_Name' => $request->input('Category_Name'),
        ]);

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function listcategory(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $sort = $request->query('sort', 'id');
        $direction = $request->query('direction', 'asc') === 'desc' ? 'desc' : 'asc';
        $perPageOptions = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 10);
        $sortableColumns = ['id', 'Category_Name', 'created_at', 'updated_at'];

        if (! in_array($sort, $sortableColumns, true)) {
            $sort = 'id';
        }

        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $categories = Category::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('Category_Name', 'like', "%{$search}%");
            })
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return view('list_category', compact('categories', 'search', 'sort', 'direction', 'perPage', 'perPageOptions'));
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

    public function listcategory_pdf(Request $request)
    {
        $categories = Category::orderBy('Category_Name')->get();
        if ($categories->isEmpty()) {
            return redirect()->route('category.list')->with('error', 'No category entries available to export.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Category Master List');
        }

        $theme = $this->exportTheme($request);
        $html = view('category_pdf', compact('categories', 'theme'))->render();
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('Category Master List.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }

    public function listcategory_excel(Request $request)
    {
        if (! Category::exists()) {
            return redirect()->route('category.list')->with('error', 'No category entries available to export.');
        }

        return Excel::download(new CategoryExport($this->exportTheme($request)), 'CategoryMaster.xlsx');
    }
}
