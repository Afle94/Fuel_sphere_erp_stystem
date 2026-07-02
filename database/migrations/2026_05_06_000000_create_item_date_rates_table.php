<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_date_rates', function (Blueprint $table) {
            $table->id();
            $table->date('rate_date');
            $table->foreignId('product_id')->constrained('produts')->cascadeOnDelete();
            $table->decimal('rate', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_date_rates');
    }
};
