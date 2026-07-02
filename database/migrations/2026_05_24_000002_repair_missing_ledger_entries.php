<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ledgers')) {
            return;
        }

        $this->repairBills();
        $this->repairCashSales();
        $this->repairCashReceipts();
        $this->repairChequeReceipts();
        $this->repairCashPayments();
        $this->repairChequePayments();
        $this->repairPurchases();
        $this->repairCardSales();
    }

    public function down(): void
    {
        // Repair migration is intentionally not reversible.
    }

    private function repairBills(): void
    {
        if (! Schema::hasTable('bills')) {
            return;
        }

        DB::table('bills')
            ->whereNotNull('bill_no')
            ->where('bill_no', '<>', '')
            ->where('total_amount', '>', 0)
            ->orderBy('id')
            ->chunk(100, function ($bills) {
                foreach ($bills as $bill) {
                    $this->insertPairIfMissing(
                        (string) $bill->bill_no,
                        'BILL',
                        $bill->bill_date ?: ($bill->date_from ?: substr((string) $bill->created_at, 0, 10)),
                        'D',
                        $bill->party,
                        'C',
                        'BILL',
                        $bill->total_amount
                    );
                }
            });
    }

    private function repairCashSales(): void
    {
        if (! Schema::hasTable('cashsales')) {
            return;
        }

        DB::table('cashsales')
            ->where('amount', '>', 0)
            ->orderBy('id')
            ->chunk(100, function ($rows) {
                foreach ($rows as $row) {
                    $this->insertPairIfMissing($row->id, 'CASH SALES', $row->date, 'D', 'CASH IN HAND', 'C', 'CASH SALES', $row->amount);
                }
            });
    }

    private function repairCashReceipts(): void
    {
        if (! Schema::hasTable('cashreceipt')) {
            return;
        }

        DB::table('cashreceipt')
            ->where('amount', '>', 0)
            ->orderBy('id')
            ->chunk(100, function ($rows) {
                foreach ($rows as $row) {
                    $this->insertPairIfMissing($row->id, 'CASH RECEIPTS', $row->date, 'D', 'CASH IN HAND', 'C', 'CASH RECEIPTS', $row->amount);
                }
            });
    }

    private function repairChequeReceipts(): void
    {
        if (! Schema::hasTable('chequereceipt')) {
            return;
        }

        DB::table('chequereceipt')
            ->where('amount', '>', 0)
            ->orderBy('id')
            ->chunk(100, function ($rows) {
                foreach ($rows as $row) {
                    $this->insertPairIfMissing($row->id, 'CHEQUE RECEIPTS', $row->date, 'D', $row->debit, 'C', $row->credit, $row->amount);
                }
            });
    }

    private function repairCashPayments(): void
    {
        if (! Schema::hasTable('cashpayment')) {
            return;
        }

        DB::table('cashpayment')
            ->where('amount', '>', 0)
            ->orderBy('id')
            ->chunk(100, function ($rows) {
                foreach ($rows as $row) {
                    $this->insertPairIfMissing($row->id, 'CASH PAYMENTS', $row->date, 'C', 'CASH IN HAND', 'D', $row->debit, $row->amount);
                }
            });
    }

    private function repairChequePayments(): void
    {
        if (! Schema::hasTable('chequepayment')) {
            return;
        }

        DB::table('chequepayment')
            ->where('amount', '>', 0)
            ->orderBy('id')
            ->chunk(100, function ($rows) {
                foreach ($rows as $row) {
                    $this->insertPairIfMissing($row->id, 'CHEQUE PAYMENTS', $row->date, 'D', $row->debit, 'C', $row->credit, $row->amount);
                }
            });
    }

    private function repairPurchases(): void
    {
        if (! Schema::hasTable('purchase')) {
            return;
        }

        DB::table('purchase')
            ->where('amount', '>', 0)
            ->orderBy('id')
            ->chunk(100, function ($rows) {
                foreach ($rows as $row) {
                    $this->insertPairIfMissing($row->id, 'PURCHASE', $row->date, 'D', 'PURCHASE', 'C', $row->perticulars, $row->amount);
                }
            });
    }

    private function repairCardSales(): void
    {
        if (! Schema::hasTable('cardsales')) {
            return;
        }

        DB::table('cardsales')
            ->where('Amount', '>', 0)
            ->orderBy('id')
            ->chunk(100, function ($rows) {
                foreach ($rows as $row) {
                    $this->insertPairIfMissing($row->id, 'CARD SALES', $row->date, 'D', $row->perticulars, 'C', 'CARD SALES', $row->Amount);
                }
            });
    }

    private function insertPairIfMissing(
        string|int $voucherNo,
        string $voucherType,
        ?string $date,
        string $firstTranType,
        ?string $firstAccount,
        string $secondTranType,
        ?string $secondAccount,
        mixed $amount
    ): void {
        if (! $date || ! $firstAccount || ! $secondAccount || (float) $amount <= 0) {
            return;
        }

        $exists = DB::table('ledgers')
            ->where('VOUCHERNO', (string) $voucherNo)
            ->where('VTYPE', $voucherType)
            ->exists();

        if ($exists) {
            return;
        }

        DB::table('ledgers')->insert([
            [
                'VOUCHERNO' => (string) $voucherNo,
                'VTYPE' => $voucherType,
                'TRANDATE' => substr($date, 0, 10),
                'TRANTYPE' => $firstTranType,
                'ACNO' => $firstAccount,
                'AMOUNT' => $amount,
            ],
            [
                'VOUCHERNO' => (string) $voucherNo,
                'VTYPE' => $voucherType,
                'TRANDATE' => substr($date, 0, 10),
                'TRANTYPE' => $secondTranType,
                'ACNO' => $secondAccount,
                'AMOUNT' => $amount,
            ],
        ]);
    }
};
