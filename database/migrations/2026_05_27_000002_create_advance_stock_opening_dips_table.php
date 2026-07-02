<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('advance_stock_opening_dips')) {
            return;
        }

        Schema::create('advance_stock_opening_dips', function (Blueprint $table) {
            $table->id();
            $table->date('fy_start_date');
            $table->string('item');
            $table->decimal('enter_depth', 10, 2)->default(0);
            $table->decimal('liter', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['fy_start_date', 'item']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advance_stock_opening_dips');
    }
};
