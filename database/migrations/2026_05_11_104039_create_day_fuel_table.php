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
        if (Schema::hasTable('day_fuel')) {
            return;
        }

        Schema::create('day_fuel', function (Blueprint $table) {
            $table->id();
            $table->date("date");
            $table->foreignId('nozzle_id')->constrained('nozzles')->cascadeOnDelete();
            $table->decimal('open', 10, 2);
            $table->decimal('close', 10, 2);
            $table->decimal('Test', 10, 2);
            $table->decimal('Quantity', 10, 2);
            $table->string('items', 50);
            $table->decimal('rate',10,2);
            $table->decimal('Amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_fuel');
    }
};
