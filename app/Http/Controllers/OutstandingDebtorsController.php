<?php

namespace App\Http\Controllers;

use App\Exports\OutstandingDebtorsExport;
use App\Models\AccountName;
use App\Models\Ledgers;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class OutstandingDebtorsController extends Controller
{
    public function index(Request $request)
    {
        $perPageOptions = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 25);
        $perPage = in_array($perPage, $perPageOptions, true) ? $perPage : 25;
        $search = trim((string) $request->query('search', ''));
        $asOnDate = $request->query('as_on_date');
        $hasAsOnDate = $request->filled('as_on_date');

        $rows = $hasAsOnDate ? $this->debtorRows($search, (string) $asOnDate) : collect();
        $totalBalance = (float) $rows->sum('balance');
        $totalBalanceLabel = $this->balanceLabel($totalBalance);

        $debtors = new LengthAwarePaginator(
            $rows->forPage(max(1, (int) $request->query('page', 1)), $perPage)->values(),
            $rows->count(),
            $perPage,
            max(1, (int) $request->query('page', 1)),
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('outstanding_debtors', [
            'debtors' => $debtors,
            'search' => $search,
            'perPage' => $perPage,
            'perPageOptions' => $perPageOptions,
            'totalBalance' => $totalBalance,
            'totalBalanceLabel' => $totalBalanceLabel,
            'asOnDate' => $asOnDate,
            'hasAsOnDate' => $hasAsOnDate,
        ]);
    }

    public function pdf(Request $request)
    {
        $asOnDate = (string) $request->query('as_on_date', '');
        $rows = $asOnDate !== '' ? $this->debtorRows(trim((string) $request->query('search', '')), $asOnDate) : collect();

        if ($asOnDate === '' || $rows->isEmpty()) {
            return redirect()
                ->route('outstanding.debtors', $request->query())
                ->with('error', 'No outstanding debtor records available to export.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Outstanding Debtors');
        }

        $totalBalance = (float) $rows->sum('balance');
        $theme = $this->exportTheme($request);

        $html = view('outstanding_debtors_pdf', [
            'rows' => $rows,
            'asOnDate' => $asOnDate,
            'totalBalanceLabel' => $this->balanceLabel($totalBalance),
            'theme' => $theme,
        ])->render();

        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('OutstandingDebtors-' . $asOnDate . '.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="OutstandingDebtors-' . $asOnDate . '.pdf"');
    }

    public function excel(Request $request)
    {
        $asOnDate = (string) $request->query('as_on_date', '');
        $rows = $asOnDate !== '' ? $this->debtorRows(trim((string) $request->query('search', '')), $asOnDate) : collect();

        if ($asOnDate === '' || $rows->isEmpty()) {
            return redirect()
                ->route('outstanding.debtors', $request->query())
                ->with('error', 'No outstanding debtor records available to export.');
        }

        return Excel::download(
            new OutstandingDebtorsExport(
                $rows,
                $asOnDate,
                $this->balanceLabel((float) $rows->sum('balance')),
                $this->exportTheme($request)
            ),
            'OutstandingDebtors-' . $asOnDate . '.xlsx'
        );
    }

    private function debtorRows(string $search, string $asOnDate): Collection
    {
        $accounts = AccountName::query()
            ->whereRaw("UPPER(TRIM(under_group)) in ('SUNDRY DEBTORS', 'SUNDURY DEBTORS')")
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('account_perticular', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('mobile_number', 'like', "%{$search}%");
                });
            })
            ->orderBy('account_perticular')
            ->get();

        $ledgerTotals = $this->ledgerBalanceTotals($accounts->pluck('account_perticular'), $asOnDate);

        return $accounts
            ->map(function (AccountName $account) use ($ledgerTotals) {
                $balance = (float) ($ledgerTotals[$account->account_perticular] ?? 0);

                return (object) [
                    'particulars' => $account->account_perticular,
                    'balance' => $balance,
                    'balance_label' => $this->balanceLabel($balance),
                    'location' => $account->city ?: '-',
                    'mobile' => $account->mobile_number ?: '-',
                ];
            })
            ->filter(fn ($row) => round((float) $row->balance, 2) !== 0.0)
            ->values();
    }

    private function ledgerBalanceTotals(Collection $accountNames, string $asOnDate): Collection
    {
        if ($accountNames->isEmpty()) {
            return collect();
        }

        return Ledgers::query()
            ->whereIn('ACNO', $accountNames)
            ->whereDate('TRANDATE', '<=', $asOnDate)
            ->orderBy('ACNO')
            ->orderBy('TRANDATE')
            ->orderBy('ID')
            ->get(['ACNO', 'TRANTYPE', 'AMOUNT'])
            ->groupBy('ACNO')
            ->map(function (Collection $rows) {
                $runningBalance = 0.0;
                $balanceTotal = 0.0;

                foreach ($rows as $row) {
                    $amount = (float) $row->AMOUNT;
                    $runningBalance += strtoupper((string) $row->TRANTYPE) === 'D' ? $amount : -$amount;
                    $balanceTotal += $runningBalance;
                }

                return $balanceTotal;
            });
    }

    private function balanceLabel(float $balance): string
    {
        return number_format(abs($balance), 2) . ' ' . ($balance >= 0 ? 'Dr' : 'Cr');
    }

    private function exportTheme(Request $request): array
    {
        $themes = [
            'default' => ['primary' => '#0f766e', 'primaryDark' => '#115e59', 'accent' => '#f59e0b', 'bgEnd' => '#eef5f3'],
            'ocean' => ['primary' => '#0369a1', 'primaryDark' => '#075985', 'accent' => '#14b8a6', 'bgEnd' => '#edf7fb'],
            'royal' => ['primary' => '#4338ca', 'primaryDark' => '#3730a3', 'accent' => '#f59e0b', 'bgEnd' => '#f1f2ff'],
            'rose' => ['primary' => '#be123c', 'primaryDark' => '#9f1239', 'accent' => '#0f766e', 'bgEnd' => '#fff1f4'],
            'charcoal' => ['primary' => '#334155', 'primaryDark' => '#1e293b', 'accent' => '#d97706', 'bgEnd' => '#eef2f7'],
        ];

        return $themes[$request->query('theme', 'default')] ?? $themes['default'];
    }
}
