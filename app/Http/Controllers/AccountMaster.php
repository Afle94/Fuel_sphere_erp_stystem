<?php

namespace App\Http\Controllers;

use App\Models\AccountName;
use App\Models\UnderGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Mpdf\Mpdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AccountMasterExport;
class AccountMaster extends Controller
{
    public function ShowAccountMaster()
    {
        $underGroups = UnderGroup::orderBy('group_name')->get(['group_name']);

        return view('accountmaster', compact('underGroups'));
    }

    public function StoreAccountMaster(Request $request)
    {
        try {
            $requiresStateCity = $this->requiresStateCity($request->input('under_group'));

            $validated = $request->validate([
                'particulars' => 'required|string|max:50|unique:account_name,account_perticular',
                'under_group' => 'required|string|exists:under_group,group_name',
                'opening_balance' => 'nullable|numeric|min:0|max:99999999.99',
                'balance_type' => 'required|in:dr,cr',
                'postal_address' => 'nullable|string|max:500',
                'state' => ($requiresStateCity ? 'required' : 'nullable') . '|string|max:255',
                'location' => ($requiresStateCity ? 'required' : 'nullable') . '|string|max:255',
                'email' => 'nullable|email|max:255',
                'mobile' => 'nullable|digits:10|regex:/^[6-9]\d{9}$/',
                'phone_landline' => 'nullable|digits:10',
                'gst_no' => 'nullable|size:15|unique:account_name,gst_number|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
            ], [
                'particulars.required' => 'Account particular field is required',
                'particulars.unique' => 'This account already exists',
                'particulars.max' => 'Maximum 50 characters allowed',
                'under_group.required' => 'Please select Under Group',
                'under_group.exists' => 'Selected group is invalid',
                'opening_balance.numeric' => 'Opening balance must be a number',
                'balance_type.required' => 'Please select balance type',
                'state.required' => 'State is required for Sundry Debtors and Sundry Creditors',
                'location.required' => 'City / District is required for Sundry Debtors and Sundry Creditors',
                'email.email' => 'Please enter a valid email',
                'mobile.digits' => 'Mobile number must be 10 digits',
                'mobile.regex' => 'Please enter a valid mobile number',
                'phone_landline.digits' => 'Phone land line must be 10 digits',
                'gst_no.unique' => 'This GST number already exists',
                'gst_no.size' => 'GST number must be 15 characters',
                'gst_no.regex' => 'Please enter a valid GST number',
            ]);

            AccountName::create([
                'account_perticular' => $validated['particulars'],
                'under_group' => $validated['under_group'],
                'opening_balance' => $validated['opening_balance'] ?? 0,
                'transaction_type' => $validated['balance_type'] === 'dr' ? 'Dr' : 'Cr.',
                'address' => $validated['postal_address'] ?? null,
                'city' => $validated['location'] ?? '',
                'state' => $validated['state'] ?? '',
                'email' => $validated['email'] ?? '',
                'mobile_number' => $validated['mobile'] ?? '',
                'phone_number' => $validated['phone_landline'] ?? null,
                'gst_number' => $validated['gst_no'] ?? null,
            ]);

            return redirect()->back()->with('success', 'Account saved successfully.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function editaccountmaster($id)
    {
        $account = AccountName::findOrFail($id);
        $underGroups = UnderGroup::orderBy('group_name')->get(['group_name']);

        return view('editaccountmaster', compact('account', 'underGroups'));
    }

    public function updateaccountmaster(Request $request, $id)
    {
        try {
            $account = AccountName::findOrFail($id);
            $requiresStateCity = $this->requiresStateCity($request->input('under_group'));

            $validated = $request->validate([
                'particulars' => 'required|string|max:50|unique:account_name,account_perticular,' . $account->id,
                'under_group' => 'required|string|exists:under_group,group_name',
                'opening_balance' => 'nullable|numeric|min:0|max:99999999.99',
                'balance_type' => 'required|in:dr,cr',
                'postal_address' => 'nullable|string|max:500',
                'state' => ($requiresStateCity ? 'required' : 'nullable') . '|string|max:255',
                'location' => ($requiresStateCity ? 'required' : 'nullable') . '|string|max:255',
                'email' => 'nullable|email|max:255',
                'mobile' => 'nullable|digits:10|regex:/^[6-9]\d{9}$/',
                'phone_landline' => 'nullable|digits:10',
                'gst_no' => 'nullable|size:15|unique:account_name,gst_number,' . $account->id . '|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
            ], [
                'particulars.required' => 'Account particular field is required',
                'particulars.unique' => 'This account already exists',
                'particulars.max' => 'Maximum 50 characters allowed',
                'under_group.required' => 'Please select Under Group',
                'under_group.exists' => 'Selected group is invalid',
                'opening_balance.numeric' => 'Opening balance must be a number',
                'balance_type.required' => 'Please select balance type',
                'state.required' => 'State is required for Sundry Debtors and Sundry Creditors',
                'location.required' => 'City / District is required for Sundry Debtors and Sundry Creditors',
                'email.email' => 'Please enter a valid email',
                'mobile.digits' => 'Mobile number must be 10 digits',
                'mobile.regex' => 'Please enter a valid mobile number',
                'phone_landline.digits' => 'Phone land line must be 10 digits',
                'gst_no.unique' => 'This GST number already exists',
                'gst_no.size' => 'GST number must be 15 characters',
                'gst_no.regex' => 'Please enter a valid GST number',
            ]);

            $account->update([
                'account_perticular' => $validated['particulars'],
                'under_group' => $validated['under_group'],
                'opening_balance' => $validated['opening_balance'] ?? 0,
                'transaction_type' => $validated['balance_type'] === 'dr' ? 'Dr' : 'Cr.',
                'address' => $validated['postal_address'] ?? null,
                'city' => $validated['location'] ?? '',
                'state' => $validated['state'] ?? '',
                'email' => $validated['email'] ?? '',
                'mobile_number' => $validated['mobile'] ?? '',
                'phone_number' => $validated['phone_landline'] ?? null,
                'gst_number' => $validated['gst_no'] ?? null,
            ]);

            return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    private function requiresStateCity(?string $underGroup): bool
    {
        $group = preg_replace('/\s+/', ' ', strtoupper(trim($underGroup ?? '')));

        return in_array($group, [
            'SUNDRY DEBTORS',
            'SUNDRY CREDITORS',
            'SUNDURY DEBTORS',
            'SUNDURY CREDITORS',
        ], true);
    }


    public function deleteaccountmaster($id)
    {
        try {
            $account = AccountName::findOrFail($id);
            $account->delete();

            return redirect()->back()->with('success', 'Account deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function ListAccountMaster(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $sort = $request->query('sort', 'account_perticular');
        $direction = $request->query('direction', 'asc') === 'desc' ? 'desc' : 'asc';
        $perPageOptions = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 10);

        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $sortableColumns = [
            'id',
            'account_perticular',
            'under_group',
            'opening_balance',
            'transaction_type',
            'address',
            'city',
            'state',
            'email',
            'mobile_number',
            'phone_number',
            'gst_number',
            'created_at',
            'updated_at',
        ];

        if (! in_array($sort, $sortableColumns, true)) {
            $sort = 'account_perticular';
        }

        $accounts = AccountName::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('account_perticular', 'like', "%{$search}%")
                        ->orWhere('under_group', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('state', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('mobile_number', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%")
                        ->orWhere('gst_number', 'like', "%{$search}%");
                });
            })
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return view('listaccountmaster', compact('accounts', 'search', 'sort', 'direction', 'perPage', 'perPageOptions'));
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

    public function ListAccountMaster_pdf(Request $request)
    {
        $pdf = AccountName::all();
        if ($pdf->isEmpty()) {
            return redirect()->route('accounts.index')->with('error', 'No account entries available to export.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Account Master List');
        }

        $theme = $this->exportTheme($request);
        $html = view('accountmaster_pdf', compact('pdf', 'theme'))->render();
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        return response($mpdf->Output('Account Master List.pdf','S'))
                     ->header('Content-Type','application/pdf');
    }


    public function ListAccountMaster_excel(Request $request)
    {
        if (! AccountName::exists()) {
            return redirect()->route('accounts.index')->with('error', 'No account entries available to export.');
        }

        return Excel::download(new AccountMasterExport($this->exportTheme($request)), 'AccountMaster.xlsx');
    }
    
}
