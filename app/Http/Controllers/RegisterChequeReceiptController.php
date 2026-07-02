<?php

namespace App\Http\Controllers;

use App\Models\ChequeReceipt;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ChequeReceiptExport;
use Mpdf\Mpdf;

class RegisterChequeReceiptController extends Controller
{
    public function filterbydate(Request $request)
    {
        [
            $query,
            $search,
            $sort,
            $direction,
            $perPage,
            $perPageOptions
        ] = $this->registerQuery($request);

        $entries = $query
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return view(
            'RegisterChequeReceiptFilter',
            compact(
                'entries',
                'search',
                'sort',
                'direction',
                'perPage',
                'perPageOptions'
            )
        );
    }

    public function pdf(Request $request)
    {
        [
            $query,
            $search,
            $sort,
            $direction
        ] = $this->registerQuery($request);

        $entries = $query
            ->orderBy($sort, $direction)
            ->get();

        if ($entries->isEmpty()) {
            return redirect()
                ->route('RegisterChequeReceiptFilter', $request->query())
                ->with(
                    'error',
                    'No cheque receipt register entries available to export.'
                );
        }

        $theme = $this->exportTheme($request);
        $periodLabel = $this->periodLabel($request);

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Cheque Receipt Register');
        }

        $html = view(
            'Cheque_Receipt_Register_pdf',
            compact(
                'entries',
                'theme',
                'periodLabel',
                'search'
            )
        )->render();

        $mpdf = new Mpdf([
            'orientation' => 'L'
        ]);

        $mpdf->WriteHTML($html);

        return response(
            $mpdf->Output(
                'ChequeReceiptRegister.pdf',
                'S'
            )
        )
            ->header('Content-Type', 'application/pdf')
            ->header(
                'Content-Disposition',
                'inline; filename="ChequeReceiptRegister.pdf"'
            );
    }

    public function excel(Request $request)
    {
        [$query, $search, $sort, $direction] = $this->registerQuery($request);

        $entries = $query
            ->orderBy($sort, $direction)
            ->get();

        if ($entries->isEmpty()) {
            return redirect()
                ->route('RegisterChequeReceiptFilter', $request->query())
                ->with(
                    'error',
                    'No cheque receipt register entries available to export.'
                );
        }

        return Excel::download(
            new ChequeReceiptExport(
                $entries,
                $this->periodLabel($request),
                $this->exportTheme($request)
            ),
            'ChequeReceiptRegister.xlsx'
        );
    }

    private function registerQuery(Request $request): array
    {
        $perPageOptions = [10, 25, 50, 100, 200, 500];
        $perPage = (int) $request->query('per_page', 10);

        $perPage = in_array(
            $perPage,
            $perPageOptions,
            true
        ) ? $perPage : 10;

        $search = trim(
            (string) $request->query('search', '')
        );

        $sort = $request->query('sort', 'date');
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

       
        $sortableColumns = [
            'date',
            'slip_no',
            'credit',
            'debit',
            'amount',
            'cheque_no',
            'datet',
            'narration',
        ];

        if (! in_array($sort, $sortableColumns, true)) {
            $sort = 'date';
        }

        $query = ChequeReceipt::query();

        if ($request->filled('from_date')) {
            $query->whereDate(
                'date',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $query->whereDate(
                'date',
                '<=',
                $request->to_date
            );
        }

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where('slip_no', 'like', "%{$search}%")
                    ->orWhere('credit', 'like', "%{$search}%")
                    ->orWhere('debit', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhere('cheque_no', 'like', "%{$search}%")
                    ->orWhere('narration', 'like', "%{$search}%");
            });
        }

        return [
            $query,
            $search,
            $sort,
            $direction,
            $perPage,
            $perPageOptions,
        ];
    }

    private function periodLabel(Request $request): string
    {
        $fromDate = $request->filled('from_date')
            ? date('d M Y', strtotime($request->from_date))
            : null;

        $toDate = $request->filled('to_date')
            ? date('d M Y', strtotime($request->to_date))
            : null;

        if ($fromDate && $toDate) {
            return $fromDate . ' to ' . $toDate;
        }

        if ($fromDate) {
            return 'From ' . $fromDate;
        }

        if ($toDate) {
            return 'Up to ' . $toDate;
        }

        return 'All Dates';
    }

    private function exportTheme(Request $request): array
    {
        $themes = [
            'default' => [
                'primary' => '#0f766e',
                'primaryDark' => '#115e59',
                'accent' => '#f59e0b',
                'bgEnd' => '#eef5f3',
            ],
            'ocean' => [
                'primary' => '#0369a1',
                'primaryDark' => '#075985',
                'accent' => '#14b8a6',
                'bgEnd' => '#edf7fb',
            ],
            'royal' => [
                'primary' => '#4338ca',
                'primaryDark' => '#3730a3',
                'accent' => '#f59e0b',
                'bgEnd' => '#f1f2ff',
            ],
            'rose' => [
                'primary' => '#be123c',
                'primaryDark' => '#9f1239',
                'accent' => '#0f766e',
                'bgEnd' => '#fff1f4',
            ],
            'charcoal' => [
                'primary' => '#334155',
                'primaryDark' => '#1e293b',
                'accent' => '#d97706',
                'bgEnd' => '#eef2f7',
            ],
            'sunset-sky' => [
                'primary' => '#ea580c',
                'primaryDark' => '#c2410c',
                'accent' => '#be123c',
                'bgEnd' => '#ffe4d6',
            ],
            'royal-print' => [
                'primary' => '#4c1d95',
                'primaryDark' => '#3b0764',
                'accent' => '#f59e0b',
                'bgEnd' => '#f5f0ff',
            ],
            'peacock-print' => [
                'primary' => '#0f766e',
                'primaryDark' => '#134e4a',
                'accent' => '#0891b2',
                'bgEnd' => '#ecfeff',
            ],
            'marigold-print' => [
                'primary' => '#b45309',
                'primaryDark' => '#92400e',
                'accent' => '#be123c',
                'bgEnd' => '#fff7ed',
            ],
            'velvet-print' => [
                'primary' => '#9d174d',
                'primaryDark' => '#831843',
                'accent' => '#7c3aed',
                'bgEnd' => '#fdf2f8',
            ],
        ];

        return $themes[$request->query('theme', 'default')]
            ?? $themes['default'];
    }
}
