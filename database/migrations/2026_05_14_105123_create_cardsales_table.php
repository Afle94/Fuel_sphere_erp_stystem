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
        Schema::create('cardsales', function (Blueprint $table) {
            $table->id();
            $table->string('Card_type')->required();
            $table->string('Batch_no')->required();
            $table->string('invoice_no')->required();
            $table->decimal('Amount', 10, 2);
            $table->string('perticulars')->required();
            $table->string('narration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cardsales');
    }
};
