<?php

namespace App\Http\Controllers;

use App\Models\CompanyInformation;
use Illuminate\Http\Request;

class CompanyInformationController extends Controller
{
    public function edit()
    {
        $companyInformation = $this->latestCompanyInformation()
            ?? new CompanyInformation();

        return view('company_information', compact('companyInformation'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['nullable', 'string', 'max:255'],
            'registered_office' => ['nullable', 'string', 'max:1000'],
            'phone_no' => ['nullable', 'string', 'max:50'],
            'mobile_no' => ['nullable', 'string', 'max:50'],
            'email_id' => ['nullable', 'email', 'max:255'],
            'gst_no' => ['nullable', 'string', 'max:50'],
        ]);

        $companyInformation = $this->latestCompanyInformation()
            ?? new CompanyInformation();

        $companyInformation->fill($validated);
        $companyInformation->save();

        return redirect()
            ->route('company-information.edit')
            ->with('success', 'Company information saved successfully.');
    }

    private function latestCompanyInformation(): ?CompanyInformation
    {
        return CompanyInformation::query()
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->first();
    }
}
