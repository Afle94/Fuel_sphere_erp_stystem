<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cashsales')) {
            Schema::table('cashsales', function (Blueprint $table) {
                $table->string('slip_no')->nullable()->change();
            });
        }

        if (Schema::hasTable('creditsales')) {
            Schema::table('creditsales', function (Blueprint $table) {
                $table->string('slip_no')->nullable()->change();
                $table->string('vehicle_no')->nullable()->change();
            });
        }

        if (Schema::hasTable('vehicles')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->string('Vehicle_no', 255)->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cashsales')) {
            Schema::table('cashsales', function (Blueprint $table) {
                $table->string('slip_no')->nullable(false)->change();
            });
        }

        if (Schema::hasTable('creditsales')) {
            Schema::table('creditsales', function (Blueprint $table) {
                $table->string('slip_no')->nullable(false)->change();
                $table->string('vehicle_no')->nullable(false)->change();
            });
        }

        if (Schema::hasTable('vehicles')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->string('Vehicle_no', 255)->nullable(false)->change();
            });
        }
    }
};
