<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('day_fuel') || Schema::hasColumn('day_fuel', 'nozzle_id')) {
            return;
        }

        Schema::table('day_fuel', function (Blueprint $table) {
            $table->unsignedBigInteger('nozzle_id')->nullable()->after('date')->index();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('day_fuel') || ! Schema::hasColumn('day_fuel', 'nozzle_id')) {
            return;
        }

        Schema::table('day_fuel', function (Blueprint $table) {
            $table->dropIndex(['nozzle_id']);
            $table->dropColumn('nozzle_id');
        });
    }
};
