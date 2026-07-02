<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('creditsales')
            ->orderBy('id')
            ->chunk(100, function ($creditSales) {
                foreach ($creditSales as $creditSale) {
                    DB::table('ledgers')
                        ->where('VOUCHERNO', $creditSale->id)
                        ->where('VTYPE', 'CREDIT SALES')
                        ->delete();

                    DB::table('ledgers')->insert([
                        [
                            'VOUCHERNO' => $creditSale->id,
                            'VTYPE' => 'CREDIT SALES',
                            'TRANTYPE' => 'D',
                            'ACNO' => $creditSale->Party_name,
                            'TRANDATE' => $creditSale->date,
                            'AMOUNT' => $creditSale->amount,
                        ],
                        [
                            'VOUCHERNO' => $creditSale->id,
                            'VTYPE' => 'CREDIT SALES',
                            'TRANTYPE' => 'C',
                            'ACNO' => 'CREDIT SALES',
                            'TRANDATE' => $creditSale->date,
                            'AMOUNT' => $creditSale->amount,
                        ],
                    ]);
                }
            });
    }

    public function down(): void
    {
        DB::table('ledgers')
            ->where('VTYPE', 'CREDIT SALES')
            ->delete();
    }
};
