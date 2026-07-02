<?php

namespace App\Http\Controllers;

use App\Exports\VehicleExport;
use App\Models\Vehicles;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use App\Models\AccountName;

class VehicleController extends Controller
{
    public function showvehicle()
    {
        $accountName = AccountName::whereRaw('TRIM(under_group) = ?', ['SUNDRY DEBTORS'])
            ->orderBy('account_perticular')
            ->get();

        return view('vehicle', compact('accountName'));
    }

    public function createvehicle(Request $request)
    {
        $validatedData = $this->validateVehicle($request);
        $accountName = AccountName::firstOrCreate(
            ['account_perticular' => $validatedData['Party_name']],
            ['under_group' => 'SUNDRY DEBTORS']
        );

        Vehicles::create($validatedData);

        return redirect()->back()->with('success', 'Vehicle added successfully!');
    }


    public function deletevehicle($id)
    {
        $vehicle = Vehicles::findOrFail($id);
        $vehicle->delete();

        return redirect()->back()->with('success', 'Vehicle deleted successfully!');
    }

    public function editvehicle($id)
    {
        $vehicle = Vehicles::findOrFail($id);

        return view('edit_vehicle', compact('vehicle'));
    }

    public function updatevehicle(Request $request, $id)
    {
        $vehicle = Vehicles::findOrFail($id);
        $validatedData = $this->validateVehicle($request, $vehicle->id);
        $vehicle->update($validatedData);

        return redirect()->back()->with('success', 'Vehicle updated successfully!');
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

    public function exportpdf(Request $request)
    {
        $vehicles = Vehicles::orderBy('Party_name')->get();
        if ($vehicles->isEmpty()) {
            return redirect()->route('vehicle.list')->with('error', 'No vehicle entries available to export.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Vehicle Master List');
        }

        $theme = $this->exportTheme($request);

        $mpdf = new Mpdf();
        $html = view('vehicle_pdf', compact('vehicles', 'theme'))->render();
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('Vehicle Master List.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }

    public function exportexcel(Request $request)
    {
        if (! Vehicles::exists()) {
            return redirect()->route('vehicle.list')->with('error', 'No vehicle entries available to export.');
        }

        return Excel::download(new VehicleExport($this->exportTheme($request)), 'VehicleMaster.xlsx');
    }

    public function listvehicle(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $sort = $request->query('sort', 'id');
        $direction = $request->query('direction', 'asc') === 'desc' ? 'desc' : 'asc';
        $perPageOptions = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 10);
        $sortableColumns = ['id', 'Party_name', 'Vehicle_no', 'created_at'];

        if (! in_array($sort, $sortableColumns, true)) {
            $sort = 'id';
        }

        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $vehicles = Vehicles::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('Party_name', 'like', "%{$search}%")
                    ->orWhere('Vehicle_no', 'like', "%{$search}%");
            })
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return view('vehicle_list', compact('vehicles', 'search', 'sort', 'direction', 'perPage', 'perPageOptions'));
    }

    private function validateVehicle(Request $request, ?int $ignoreId = null): array
    {
        $request->merge([
            'Party_name' => trim((string) $request->input('Party_name')),
            'Vehicle_no' => strtoupper(preg_replace('/[^A-Z0-9]/i', '', (string) $request->input('Vehicle_no'))) ?: null,
        ]);

        return $request->validate([
            'Party_name' => [
                'required',
                'string',
                'max:100',
            ],
            'Vehicle_no' => [
                'nullable',
                'string',
                'max:11',
                'regex:/^([A-Z]{2}[0-9]{2}[A-Z]{1,3}[0-9]{4}|[0-9]{2}BH[0-9]{4}[A-Z]{2})$/',
                Rule::unique('vehicles', 'Vehicle_no')->ignore($ignoreId),
            ],
        ], [
            'Party_name.max' => 'Party name can be up to 100 characters.',
            'Vehicle_no.regex' => 'Vehicle number must be an Indian format like GJ01AB1234 or 22BH1234AA.',
            'Vehicle_no.max' => 'Vehicle number can be up to 11 characters without spaces.',
        ]);
    }
}
