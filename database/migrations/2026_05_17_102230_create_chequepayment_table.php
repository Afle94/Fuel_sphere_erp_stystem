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
        Schema::create('chequepayment', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('slip_no')->required();
            $table->string('debit')->required();
            $table->string('credit')->required();
            $table->decimal('amount', 15, 2)->required();
            $table->string('Narration')->nullable();
            $table->string('cheque_no')->required();
            $table->string('cheque_date')->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chequepayment');
    }
};
