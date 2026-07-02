<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('day_fuel')) {
            return;
        }

        Schema::create('day_fuel', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('nozzle_id')->nullable()->index();
            $table->decimal('open', 12, 2)->default(0);
            $table->decimal('close', 12, 2)->default(0);
            $table->decimal('Test', 12, 2)->default(0);
            $table->decimal('Quantity', 12, 2)->default(0);
            $table->string('items')->nullable();
            $table->decimal('rate', 12, 2)->default(0);
            $table->decimal('Amount', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('day_fuel');
    }
};
