<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('bills') || ! Schema::hasTable('ledgers')) {
            return;
        }

        DB::table('bills')
            ->whereNotNull('bill_no')
            ->where('bill_no', '<>', '')
            ->where('total_amount', '>', 0)
            ->orderBy('id')
            ->chunk(100, function ($bills) {
                foreach ($bills as $bill) {
                    $ledgerExists = DB::table('ledgers')
                        ->where('VOUCHERNO', $bill->bill_no)
                        ->where('VTYPE', 'BILL')
                        ->exists();

                    if ($ledgerExists) {
                        continue;
                    }

                    $billDate = $bill->created_at
                        ? substr((string) $bill->created_at, 0, 10)
                        : now()->toDateString();

                    DB::table('ledgers')->insert([
                        [
                            'VOUCHERNO' => $bill->bill_no,
                            'VTYPE' => 'BILL',
                            'TRANDATE' => $billDate,
                            'TRANTYPE' => 'D',
                            'ACNO' => $bill->party,
                            'AMOUNT' => $bill->total_amount,
                        ],
                        [
                            'VOUCHERNO' => $bill->bill_no,
                            'VTYPE' => 'BILL',
                            'TRANDATE' => $billDate,
                            'TRANTYPE' => 'C',
                            'ACNO' => 'BILL',
                            'AMOUNT' => $bill->total_amount,
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
            ->where('VTYPE', 'BILL')
            ->delete();
    }
};
