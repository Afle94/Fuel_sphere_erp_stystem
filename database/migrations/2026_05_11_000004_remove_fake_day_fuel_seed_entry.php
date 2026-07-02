<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('day_fuel')) {
            return;
        }

        DB::table('day_fuel')
            ->where('open', 1000)
            ->where('close', 1125)
            ->where('Test', 5)
            ->where('Quantity', 120)
            ->where('items', 'Petrol')
            ->where('rate', 100)
            ->where('Amount', 12000)
            ->delete();
    }

    public function down(): void
    {
        // Do not recreate fake data.
    }
};
