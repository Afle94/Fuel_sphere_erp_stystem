<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('purchase') || ! Schema::hasTable('ledgers')) {
            return;
        }

        DB::table('purchase')
            ->where('amount', '>', 0)
            ->orderBy('id')
            ->chunk(100, function ($purchases) {
                foreach ($purchases as $purchase) {
                    $ledgerExists = DB::table('ledgers')
                        ->where('VOUCHERNO', $purchase->id)
                        ->where('VTYPE', 'PURCHASE')
                        ->exists();

                    if ($ledgerExists) {
                        continue;
                    }

                    DB::table('ledgers')->insert([
                        [
                            'VOUCHERNO' => $purchase->id,
                            'VTYPE' => 'PURCHASE',
                            'TRANDATE' => $purchase->date,
                            'TRANTYPE' => 'D',
                            'ACNO' => 'PURCHASE',
                            'AMOUNT' => $purchase->amount,
                        ],
                        [
                            'VOUCHERNO' => $purchase->id,
                            'VTYPE' => 'PURCHASE',
                            'TRANDATE' => $purchase->date,
                            'TRANTYPE' => 'C',
                            'ACNO' => $purchase->perticulars,
                            'AMOUNT' => $purchase->amount,
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
            ->where('VTYPE', 'PURCHASE')
            ->delete();
    }
};
