<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase', 'vehicle_no')) {
                $table->string('vehicle_no', 13)->nullable()->after('invoice_no');
            }

            if (!Schema::hasColumn('purchase', 'transporter')) {
                $table->string('transporter')->nullable()->after('vehicle_no');
            }

            if (!Schema::hasColumn('purchase', 'driver')) {
                $table->string('driver')->nullable()->after('transporter');
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchase', function (Blueprint $table) {
            if (Schema::hasColumn('purchase', 'vehicle_no')) {
                $table->dropColumn('vehicle_no');
            }

            if (Schema::hasColumn('purchase', 'transporter')) {
                $table->dropColumn('transporter');
            }

            if (Schema::hasColumn('purchase', 'driver')) {
                $table->dropColumn('driver');
            }
        });
    }
};
