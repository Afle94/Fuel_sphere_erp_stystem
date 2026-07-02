<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cashsales') || ! Schema::hasTable('ledgers')) {
            return;
        }

        DB::table('cashsales')
            ->where('amount', '>', 0)
            ->orderBy('id')
            ->chunk(100, function ($cashSales) {
                foreach ($cashSales as $cashSale) {
                    $ledgerExists = DB::table('ledgers')
                        ->where('VOUCHERNO', $cashSale->id)
                        ->where('VTYPE', 'CASH SALES')
                        ->exists();

                    if ($ledgerExists) {
                        continue;
                    }

                    DB::table('ledgers')->insert([
                        [
                            'VOUCHERNO' => $cashSale->id,
                            'VTYPE' => 'CASH SALES',
                            'TRANDATE' => $cashSale->date,
                            'TRANTYPE' => 'D',
                            'ACNO' => 'CASH IN HAND',
                            'AMOUNT' => $cashSale->amount,
                        ],
                        [
                            'VOUCHERNO' => $cashSale->id,
                            'VTYPE' => 'CASH SALES',
                            'TRANDATE' => $cashSale->date,
                            'TRANTYPE' => 'C',
                            'ACNO' => 'CASH SALES',
                            'AMOUNT' => $cashSale->amount,
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
            ->where('VTYPE', 'CASH SALES')
            ->delete();
    }
};
