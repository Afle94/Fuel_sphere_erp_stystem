<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cashreceipt') || ! Schema::hasTable('ledgers')) {
            return;
        }

        DB::table('cashreceipt')
            ->where('amount', '>', 0)
            ->orderBy('id')
            ->chunk(100, function ($cashReceipts) {
                foreach ($cashReceipts as $cashReceipt) {
                    $ledgerExists = DB::table('ledgers')
                        ->where('VOUCHERNO', $cashReceipt->id)
                        ->where('VTYPE', 'CASH RECEIPTS')
                        ->exists();

                    if ($ledgerExists) {
                        continue;
                    }

                    DB::table('ledgers')->insert([
                        [
                            'VOUCHERNO' => $cashReceipt->id,
                            'VTYPE' => 'CASH RECEIPTS',
                            'TRANDATE' => $cashReceipt->date,
                            'TRANTYPE' => 'D',
                            'ACNO' => 'CASH IN HAND',
                            'AMOUNT' => $cashReceipt->amount,
                        ],
                        [
                            'VOUCHERNO' => $cashReceipt->id,
                            'VTYPE' => 'CASH RECEIPTS',
                            'TRANDATE' => $cashReceipt->date,
                            'TRANTYPE' => 'C',
                            'ACNO' => 'CASH RECEIPTS',
                            'AMOUNT' => $cashReceipt->amount,
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
            ->where('VTYPE', 'CASH RECEIPTS')
            ->delete();
    }
};
