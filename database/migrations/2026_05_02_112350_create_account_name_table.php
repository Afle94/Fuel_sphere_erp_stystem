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
        Schema::create('account_name', function (Blueprint $table) {
            $table->id();
            $table->string('account_perticular',50)->unique()->required();
            $table->string('under_group')->required();
            $table->decimal('opening_balance', 10, 2)->default(0)->required();
            $table->enum('transaction_type', ['Dr', 'Cr.'])->required();
            $table->string('address',500)->nullable();
            $table->string('city')->required();
            $table->string('state')->required();
            $table->string('email')->required();
            $table->string('mobile_number',10)->required();
            $table->string('phone_number',10)->nullable();
            $table->string('gst_number',15)->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_name');
    }
};
