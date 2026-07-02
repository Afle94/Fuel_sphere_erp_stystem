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
        Schema::create('produts', function (Blueprint $table) {
            $table->id();
            $table->string('Product_Name', 50);
            $table->string('HSN', 20)->nullable();
            $table->decimal('GST_per', 5, 2)->nullable();
            $table->string('Category', 50);
            $table->decimal('Purchase_rate', 10, 2)->nullable();
            $table->integer('opening_stock')->nullable();
            $table->decimal('opening_stock_value', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produts');
    }
};
