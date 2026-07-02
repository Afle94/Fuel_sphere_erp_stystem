<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dailydips', function (Blueprint $table) {
            if (! Schema::hasColumn('dailydips', 'fy_opening_depth')) {
                $table->decimal('fy_opening_depth', 10, 2)->nullable()->after('liter');
            }

            if (! Schema::hasColumn('dailydips', 'fy_opening_liter')) {
                $table->decimal('fy_opening_liter', 12, 2)->nullable()->after('fy_opening_depth');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dailydips', function (Blueprint $table) {
            if (Schema::hasColumn('dailydips', 'fy_opening_liter')) {
                $table->dropColumn('fy_opening_liter');
            }

            if (Schema::hasColumn('dailydips', 'fy_opening_depth')) {
                $table->dropColumn('fy_opening_depth');
            }
        });
    }
};
