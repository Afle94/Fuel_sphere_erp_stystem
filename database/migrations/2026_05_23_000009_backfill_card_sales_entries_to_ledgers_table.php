<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cardsales') || ! Schema::hasTable('ledgers')) {
            return;
        }

        DB::table('cardsales')
            ->where('Amount', '>', 0)
            ->orderBy('id')
            ->chunk(100, function ($cardSales) {
                foreach ($cardSales as $cardSale) {
                    $ledgerExists = DB::table('ledgers')
                        ->where('VOUCHERNO', $cardSale->id)
                        ->where('VTYPE', 'CARD SALES')
                        ->exists();

                    if ($ledgerExists) {
                        continue;
                    }

                    DB::table('ledgers')->insert([
                        [
                            'VOUCHERNO' => $cardSale->id,
                            'VTYPE' => 'CARD SALES',
                            'TRANDATE' => $cardSale->date,
                            'TRANTYPE' => 'D',
                            'ACNO' => $cardSale->perticulars,
                            'AMOUNT' => $cardSale->Amount,
                        ],
                        [
                            'VOUCHERNO' => $cardSale->id,
                            'VTYPE' => 'CARD SALES',
                            'TRANDATE' => $cardSale->date,
                            'TRANTYPE' => 'C',
                            'ACNO' => 'CARD SALES',
                            'AMOUNT' => $cardSale->Amount,
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
            ->where('VTYPE', 'CARD SALES')
            ->delete();
    }
};
