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
        Schema::create('creditsales', function (Blueprint $table) {
            $table->id();
            $table->string('slip_no')->unique();
            $table->date('date');
            $table->string('ref_no');
            $table->string('Party_name');
            $table->string('vehicle_no');
            $table->string('item_name');
            $table->decimal('quantity', 10, 2);
            $table->decimal('rate', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->string('Narration');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creditsales');
    }
};
