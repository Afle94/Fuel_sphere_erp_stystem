<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('chequepayment') || ! Schema::hasTable('ledgers')) {
            return;
        }

        DB::table('chequepayment')
            ->where('amount', '>', 0)
            ->orderBy('id')
            ->chunk(100, function ($chequePayments) {
                foreach ($chequePayments as $chequePayment) {
                    $ledgerExists = DB::table('ledgers')
                        ->where('VOUCHERNO', $chequePayment->id)
                        ->where('VTYPE', 'CHEQUE PAYMENTS')
                        ->exists();

                    if ($ledgerExists) {
                        continue;
                    }

                    DB::table('ledgers')->insert([
                        [
                            'VOUCHERNO' => $chequePayment->id,
                            'VTYPE' => 'CHEQUE PAYMENTS',
                            'TRANDATE' => $chequePayment->date,
                            'TRANTYPE' => 'D',
                            'ACNO' => $chequePayment->debit,
                            'AMOUNT' => $chequePayment->amount,
                        ],
                        [
                            'VOUCHERNO' => $chequePayment->id,
                            'VTYPE' => 'CHEQUE PAYMENTS',
                            'TRANDATE' => $chequePayment->date,
                            'TRANTYPE' => 'C',
                            'ACNO' => $chequePayment->credit,
                            'AMOUNT' => $chequePayment->amount,
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
            ->where('VTYPE', 'CHEQUE PAYMENTS')
            ->delete();
    }
};
