<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('chequereceipt') || ! Schema::hasTable('ledgers')) {
            return;
        }

        DB::table('chequereceipt')
            ->where('amount', '>', 0)
            ->orderBy('id')
            ->chunk(100, function ($chequeReceipts) {
                foreach ($chequeReceipts as $chequeReceipt) {
                    $ledgerExists = DB::table('ledgers')
                        ->where('VOUCHERNO', $chequeReceipt->id)
                        ->where('VTYPE', 'CHEQUE RECEIPTS')
                        ->exists();

                    if ($ledgerExists) {
                        continue;
                    }

                    DB::table('ledgers')->insert([
                        [
                            'VOUCHERNO' => $chequeReceipt->id,
                            'VTYPE' => 'CHEQUE RECEIPTS',
                            'TRANDATE' => $chequeReceipt->date,
                            'TRANTYPE' => 'D',
                            'ACNO' => $chequeReceipt->debit,
                            'AMOUNT' => $chequeReceipt->amount,
                        ],
                        [
                            'VOUCHERNO' => $chequeReceipt->id,
                            'VTYPE' => 'CHEQUE RECEIPTS',
                            'TRANDATE' => $chequeReceipt->date,
                            'TRANTYPE' => 'C',
                            'ACNO' => $chequeReceipt->credit,
                            'AMOUNT' => $chequeReceipt->amount,
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
            ->where('VTYPE', 'CHEQUE RECEIPTS')
            ->delete();
    }
};
