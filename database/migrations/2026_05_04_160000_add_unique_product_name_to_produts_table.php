<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produts', function (Blueprint $table) {
            $table->unique('Product_Name');
        });
    }

    public function down(): void
    {
        Schema::table('produts', function (Blueprint $table) {
            $table->dropUnique(['Product_Name']);
        });
    }
};
