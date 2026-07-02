<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cashpayment') || ! Schema::hasTable('ledgers')) {
            return;
        }

        DB::table('cashpayment')
            ->where('amount', '>', 0)
            ->orderBy('id')
            ->chunk(100, function ($cashPayments) {
                foreach ($cashPayments as $cashPayment) {
                    $ledgerExists = DB::table('ledgers')
                        ->where('VOUCHERNO', $cashPayment->id)
                        ->where('VTYPE', 'CASH PAYMENTS')
                        ->exists();

                    if ($ledgerExists) {
                        continue;
                    }

                    DB::table('ledgers')->insert([
                        [
                            'VOUCHERNO' => $cashPayment->id,
                            'VTYPE' => 'CASH PAYMENTS',
                            'TRANDATE' => $cashPayment->date,
                            'TRANTYPE' => 'C',
                            'ACNO' => 'CASH IN HAND',
                            'AMOUNT' => $cashPayment->amount,
                        ],
                        [
                            'VOUCHERNO' => $cashPayment->id,
                            'VTYPE' => 'CASH PAYMENTS',
                            'TRANDATE' => $cashPayment->date,
                            'TRANTYPE' => 'D',
                            'ACNO' => $cashPayment->debit,
                            'AMOUNT' => $cashPayment->amount,
                        ],
                    ]);
                }
            });
    }

    public function down(): void
    {
        if (! Schema::hasTable('ledgers')) {
            return;
        }

        DB::table('ledgers')
            ->where('VTYPE', 'CASH PAYMENTS')
            ->delete();
    }
};
