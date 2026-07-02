<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('day_fuel') || ! Schema::hasColumn('day_fuel', 'nozzle_id')) {
            return;
        }

        Schema::table('day_fuel', function (Blueprint $table) {
            $table->foreign('nozzle_id')
                ->references('id')
                ->on('nozzles')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('day_fuel') || ! Schema::hasColumn('day_fuel', 'nozzle_id')) {
            return;
        }

        Schema::table('day_fuel', function (Blueprint $table) {
            $table->dropForeign(['nozzle_id']);
        });
    }
};
