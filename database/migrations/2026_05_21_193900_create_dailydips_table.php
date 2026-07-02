<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dailydips', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('item');
            $table->decimal('enter_depth', 10, 2)->default(0);
            $table->decimal('liter', 12, 2)->default(0);
            $table->timestamps();

            $table->index(['date', 'item']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dailydips');
    }
};
