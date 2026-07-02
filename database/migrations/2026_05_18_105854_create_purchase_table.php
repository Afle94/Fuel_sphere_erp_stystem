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
        Schema::create('purchase', function (Blueprint $table) {
            $table->id();
            $table->string('perticulars')->required();
            $table->string('Ref_no')->required();
            $table->date('date')->required();
            $table->enum('interstate', ['Yes', 'No'])->default('No');
            $table->string('postal address')->nullable();
            $table->string('location')->nullable();
            $table->string('invoice_no')->required();
            $table->string('Batch_no')->nullable();
            $table->string('transporter')->nullable();
            $table->string('driver')->nullable();
            $table->string('item_name')->required();
            $table->integer('quantity')->required();
            $table->decimal('rate', 10, 2)->required();
            $table->decimal('amount', 15, 2)->required();
            $table->decimal('discount%', 5, 2)->default(0);
            $table->decimal('discountinrs', 10, 2)->default(0);
            $table->decimal('taxable_amount', 15, 2)->required();
            $table->decimal('cgst', 10, 2)->default(0);
            $table->decimal('sgst', 10, 2)->default(0);
            $table->decimal('igst', 10, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->required();
            $table->string('total_tax_amount')->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase');
    }
};
