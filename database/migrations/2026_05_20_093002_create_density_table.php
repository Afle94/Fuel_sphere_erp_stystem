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
        Schema::create('density', function (Blueprint $table) {
            $table->id();
            $table->string('fuel_type');
            $table->decimal('temperature', 8, 2);
            $table->decimal('base_dens', 8, 4);
            $table->decimal('chart_val', 8, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('density');
    }
};
