<?php

namespace App\Http\Controllers;

use App\Exports\AccountLedgerExport;
use App\Models\Ledgers;
use App\Models\AccountName;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class AccountLedgerController extends Controller
{
    /**
     * Display account ledger.
     */
    public function index(Request $request)
    {
        $perPageOptions = [10, 25, 50, 100];

        $perPage = (int) $request->query('per_page', 10);

        $perPage = in_array($perPage, $perPageOptions, true)
            ? $perPage
            : 10;

        $search = trim((string) $request->query('search', ''));
        $accountParticular = trim((string) $request->query('account_particular', ''));
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        $hasLedgerFilters = $request->filled('from_date')
            && $request->filled('to_date')
            && $accountParticular !== '';

        [$sort, $direction] = $this->sortOptions($request);
        $ledgerRows = $hasLedgerFilters ? $this->ledgerRowsForRequest($request) : collect();
        $ledgerTotals = $this->ledgerTotals($ledgerRows);

        $transactions = $this->paginateLedgerRows(
            $ledgerRows,
            $perPage,
            (int) $request->query('page', 1),
            $request
        );
        $selectedAccount = $accountParticular !== ''
            ? AccountName::query()
                ->where('account_perticular', $accountParticular)
                ->first()
            : null;

        return view('Account_ledger', [
            'transactions' => $transactions,
            'accountNames' => AccountName::query()
                ->orderBy('account_perticular')
                ->pluck('account_perticular'),
            'accountParticular' => $accountParticular,
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction,
            'perPage' => $perPage,
            'perPageOptions' => $perPageOptions,
            'hasLedgerFilters' => $hasLedgerFilters,
            'selectedAccount' => $selectedAccount,
            'ledgerTotals' => $ledgerTotals,
        ]);
    }

    public function pdf(Request $request)
    {
        $rows = $this->ledgerRowsForRequest($request);

        if ($rows->isEmpty()) {
            return redirect()
                ->route('accounts.ledger', $request->query())
                ->with('error', 'No account ledger records available to export.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Account Ledger');
        }

        $accountParticular = trim((string) $request->query('account_particular', ''));
        $selectedAccount = AccountName::query()
            ->where('account_perticular', $accountParticular)
            ->first();
        $theme = $this->exportTheme($request);

        $html = view('account_ledger_pdf', [
            'rows' => $rows,
            'accountParticular' => $accountParticular,
            'underGroup' => $selectedAccount?->under_group ?: '-',
            'fromDate' => $request->query('from_date'),
            'toDate' => $request->query('to_date'),
            'theme' => $theme,
        ])->render();

        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('AccountLedger.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="AccountLedger.pdf"');
    }

    public function excel(Request $request)
    {
        $rows = $this->ledgerRowsForRequest($request);

        if ($rows->isEmpty()) {
            return redirect()
                ->route('accounts.ledger', $request->query())
                ->with('error', 'No account ledger records available to export.');
        }

        $accountParticular = trim((string) $request->query('account_particular', ''));
        $selectedAccount = AccountName::query()
            ->where('account_perticular', $accountParticular)
            ->first();

        return Excel::download(
            new AccountLedgerExport(
                $rows,
                $accountParticular,
                $selectedAccount?->under_group ?: '-',
                (string) $request->query('from_date', ''),
                (string) $request->query('to_date', ''),
                $this->exportTheme($request)
            ),
            'AccountLedger.xlsx'
        );
    }

    private function sortOptions(Request $request): array
    {
        $sort = $request->query('sort', 'TRANDATE');
        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';
        $sortableColumns = [
            'TRANDATE',
            'ACNO',
            'TRANTYPE',
            'AMOUNT',
            'vehicle_no',
            'debit',
            'credit',
            'balance',
        ];

        if (! in_array($sort, $sortableColumns, true)) {
            $sort = 'TRANDATE';
        }

        return [$sort, $direction];
    }

    private function ledgerRowsForRequest(Request $request): Collection
    {
        $accountParticular = trim((string) $request->query('account_particular', ''));

        if (! $request->filled('from_date') || ! $request->filled('to_date') || $accountParticular === '') {
            return collect();
        }

        $search = trim((string) $request->query('search', ''));
        [$sort, $direction] = $this->sortOptions($request);
        $dbSort = in_array($sort, ['vehicle_no', 'debit', 'credit', 'balance'], true)
            ? 'TRANDATE'
            : $sort;

        $query = Ledgers::query()
            ->whereDate('TRANDATE', '>=', $request->query('from_date'))
            ->whereDate('TRANDATE', '<=', $request->query('to_date'))
            ->where('ACNO', $accountParticular);

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where('TRANTYPE', 'like', "%{$search}%")
                    ->orWhere('ACNO', 'like', "%{$search}%");
            });
        }

        return $this->withLedgerParticulars($this->withRunningBalances(
            $query
                ->orderBy($dbSort, $direction)
                ->orderBy('ID', $direction)
                ->get()
        ));
    }

    private function withRunningBalances(Collection $rows): Collection
    {
        $balance = 0.0;

        return $rows->map(function ($row) use (&$balance) {
            $amount = (float) $row->AMOUNT;
            $tranType = strtoupper((string) $row->TRANTYPE);
            $row->tran_type_label = str_starts_with($tranType, 'D') ? 'Dr' : 'Cr';
            $row->debit = $row->tran_type_label === 'Dr' ? $amount : 0.0;
            $row->credit = $row->tran_type_label === 'Cr' ? $amount : 0.0;
            $balance += $row->debit - $row->credit;
            $row->balance = $balance;
            $row->balance_label = number_format(abs($balance), 2) . ' ' . ($balance >= 0 ? 'Dr' : 'Cr');

            return $row;
        });
    }

    private function ledgerTotals(Collection $rows): array
    {
        $totalDebit = (float) $rows->sum('debit');
        $totalCredit = (float) $rows->sum('credit');
        $totalBalance = (float) $rows->sum('balance');
        $closingBalance = (float) ($rows->last()?->balance ?? 0);

        return [
            'debit' => $totalDebit,
            'credit' => $totalCredit,
            'balance' => $totalBalance,
            'balance_label' => number_format(abs($totalBalance), 2) . ' ' . ($totalBalance >= 0 ? 'Dr' : 'Cr'),
            'closing_balance' => $closingBalance,
            'closing_balance_label' => number_format(abs($closingBalance), 2) . ' ' . ($closingBalance >= 0 ? 'Dr' : 'Cr'),
        ];
    }

    private function withLedgerParticulars(Collection $rows): Collection
    {
        $voucherNosByType = $rows
            ->groupBy(fn ($row) => strtoupper((string) $row->VTYPE))
            ->map(fn ($typeRows) => $typeRows->pluck('VOUCHERNO')->filter()->unique()->values());

        $details = [
            'CASH SALES' => $this->rowsById('cashsales', $voucherNosByType->get('CASH SALES', collect())),
            'CREDIT SALES' => $this->rowsById('creditsales', $voucherNosByType->get('CREDIT SALES', collect())),
            'CARD SALES' => $this->rowsById('cardsales', $voucherNosByType->get('CARD SALES', collect())),
            'PURCHASE' => $this->rowsById('purchase', $voucherNosByType->get('PURCHASE', collect())),
            'CASH RECEIPTS' => $this->rowsById('cashreceipt', $voucherNosByType->get('CASH RECEIPTS', collect())),
            'CASH PAYMENTS' => $this->rowsById('cashpayment', $voucherNosByType->get('CASH PAYMENTS', collect())),
            'CHEQUE RECEIPTS' => $this->rowsById('chequereceipt', $voucherNosByType->get('CHEQUE RECEIPTS', collect())),
            'CHEQUE PAYMENTS' => $this->rowsById('chequepayment', $voucherNosByType->get('CHEQUE PAYMENTS', collect())),
        ];

        $billNos = $voucherNosByType->get('BILL', collect());
        $bills = $billNos->isEmpty()
            ? collect()
            : DB::table('bills')->whereIn('bill_no', $billNos)->get()->keyBy('bill_no');
        $billItems = $bills->isEmpty()
            ? collect()
            : DB::table('bill_items')
                ->whereIn('bill_id', $bills->pluck('id'))
                ->get()
                ->groupBy('bill_id');

        return $rows->map(function ($row) use ($details, $bills, $billItems) {
            $voucherType = strtoupper((string) $row->VTYPE);
            $voucherNo = (string) $row->VOUCHERNO;
            $source = ($details[$voucherType] ?? collect())->get($voucherNo);

            $row->particular_label = match ($voucherType) {
                'CASH SALES', 'CREDIT SALES' => $this->saleParticular($source),
                'CARD SALES' => $this->cardSaleParticular($source),
                'PURCHASE' => $this->purchaseParticular($source),
                'CASH RECEIPTS', 'CASH PAYMENTS' => $this->cashVoucherParticular($source),
                'CHEQUE RECEIPTS', 'CHEQUE PAYMENTS' => $this->chequeVoucherParticular($source),
                'BILL' => $this->billParticular($bills->get($voucherNo), $billItems),
                default => $row->ACNO ?? '-',
            };
            $row->vehicle_no_label = match ($voucherType) {
                'CREDIT SALES' => $source->vehicle_no ?? '-',
                'PURCHASE' => $source->vehicle_no ?? '-',
                'BILL' => $this->billVehicleNo($bills->get($voucherNo), $billItems),
                default => '-',
            };

            return $row;
        });
    }

    private function rowsById(string $table, Collection $ids): Collection
    {
        if ($ids->isEmpty()) {
            return collect();
        }

        return DB::table($table)
            ->whereIn('id', $ids)
            ->get()
            ->keyBy(fn ($row) => (string) $row->id);
    }

    private function saleParticular(?object $sale): string
    {
        if (! $sale) {
            return '-';
        }

        return $this->appendDetails($sale->item_name ?? 'Sale', [
            'Slip' => $sale->slip_no ?? null,
            'Rate' => $sale->rate ?? null,
        ]);
    }

    private function cardSaleParticular(?object $sale): string
    {
        if (! $sale) {
            return '-';
        }

        return $this->appendDetails($sale->perticulars ?? $sale->Card_type ?? 'Card Sale', [
            'Invoice' => $sale->invoice_no ?? null,
            'Batch' => $sale->Batch_no ?? null,
        ]);
    }

    private function purchaseParticular(?object $purchase): string
    {
        if (! $purchase) {
            return '-';
        }

        return $this->appendDetails($purchase->item_name ?? 'Purchase', [
            'Invoice' => $purchase->invoice_no ?? null,
            'Rate' => $purchase->rate ?? null,
        ]);
    }

    private function cashVoucherParticular(?object $voucher): string
    {
        if (! $voucher) {
            return '-';
        }

        return $this->appendDetails($voucher->narration ?? $voucher->Narration ?? 'Cash Voucher', [
            'Slip' => $voucher->slip_no ?? null,
        ]);
    }

    private function chequeVoucherParticular(?object $voucher): string
    {
        if (! $voucher) {
            return '-';
        }

        return $this->appendDetails($voucher->narration ?? $voucher->Narration ?? 'Cheque Voucher', [
            'Slip' => $voucher->slip_no ?? null,
            'Cheque' => $voucher->cheque_no ?? null,
        ]);
    }

    private function billParticular(?object $bill, Collection $billItems): string
    {
        if (! $bill) {
            return '-';
        }

        $items = $billItems->get($bill->id, collect())
            ->pluck('item_name')
            ->filter()
            ->unique()
            ->take(3)
            ->implode(', ');

        return $this->appendDetails($items ?: 'Bill', [
            'Bill No' => $bill->bill_no ?? null,
            'Vehicle' => $bill->vehicle_no ?? null,
        ]);
    }

    private function billVehicleNo(?object $bill, Collection $billItems): string
    {
        if (! $bill) {
            return '-';
        }

        if (filled($bill->vehicle_no ?? null)) {
            return $bill->vehicle_no;
        }

        return $billItems->get($bill->id, collect())
            ->pluck('vehicle_no')
            ->filter()
            ->unique()
            ->implode(', ') ?: '-';
    }

    private function appendDetails(string $label, array $details): string
    {
        $parts = collect($details)
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value, $key) => "{$key}: {$value}")
            ->values()
            ->implode(', ');

        return $parts === '' ? $label : "{$label} ({$parts})";
    }

    private function paginateLedgerRows(Collection $rows, int $perPage, int $page, Request $request): LengthAwarePaginator
    {
        $page = max(1, $page);

        return new LengthAwarePaginator(
            $rows->forPage($page, $perPage)->values(),
            $rows->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
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
