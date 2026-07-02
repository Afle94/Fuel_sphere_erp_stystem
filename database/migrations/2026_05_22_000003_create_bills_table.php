<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bills')) {
            return;
        }

        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no', 50)->nullable()->index();
            $table->date('bill_date')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->string('party')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->unsignedInteger('total_slips')->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
