<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_samples', function (Blueprint $table) {
            $table->decimal('hsd_base_density', 10, 4)->default(0)->change();
            $table->decimal('hsd_value', 10, 4)->default(0)->change();
            $table->decimal('ms_base_density', 10, 4)->default(0)->change();
            $table->decimal('ms_value', 10, 4)->default(0)->change();
            $table->decimal('power_ms_base_density', 10, 4)->default(0)->change();
            $table->decimal('power_ms_value', 10, 4)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('purchase_samples', function (Blueprint $table) {
            $table->decimal('hsd_base_density', 10, 2)->default(0)->change();
            $table->decimal('hsd_value', 10, 2)->default(0)->change();
            $table->decimal('ms_base_density', 10, 2)->default(0)->change();
            $table->decimal('ms_value', 10, 2)->default(0)->change();
            $table->decimal('power_ms_base_density', 10, 2)->default(0)->change();
            $table->decimal('power_ms_value', 10, 2)->default(0)->change();
        });
    }
};
