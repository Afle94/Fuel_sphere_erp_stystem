<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id('ID');
            $table->string('VOUCHERNO', 20);
            $table->string('VTYPE', 40);
            $table->char('TRANTYPE', 1);
            $table->string('ACNO', 40);
            $table->date('TRANDATE');
            $table->decimal('AMOUNT', 19, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledgers');
    }
};
