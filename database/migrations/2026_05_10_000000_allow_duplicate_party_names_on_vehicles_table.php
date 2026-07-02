<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Throwable;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->dropUnique(['Party_name']);
            });
        } catch (Throwable $exception) {
            // Fresh databases already create Party_name without a unique index.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->unique('Party_name');
        });
    }
};
