<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('bills') || Schema::hasColumn('bills', 'bill_date')) {
            return;
        }

        Schema::table('bills', function (Blueprint $table) {
            $table->date('bill_date')->nullable()->after('bill_no');
        });

        DB::table('bills')
            ->whereNull('bill_date')
            ->update(['bill_date' => DB::raw('date_from')]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('bills') || ! Schema::hasColumn('bills', 'bill_date')) {
            return;
        }

        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('bill_date');
        });
    }
};
