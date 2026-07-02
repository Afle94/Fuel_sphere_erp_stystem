<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('purchase')) {
            return;
        }

        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `purchase` MODIFY `quantity` DECIMAL(12, 3) NOT NULL');
            DB::statement('ALTER TABLE `purchase` MODIFY `total_tax_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0');

            return;
        }

        Schema::table('purchase', function (Blueprint $table) {
            $table->decimal('quantity', 12, 3)->change();
            $table->decimal('total_tax_amount', 15, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('purchase')) {
            return;
        }

        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `purchase` MODIFY `quantity` INT NOT NULL');
            DB::statement('ALTER TABLE `purchase` MODIFY `total_tax_amount` VARCHAR(255) NOT NULL');

            return;
        }

        Schema::table('purchase', function (Blueprint $table) {
            $table->integer('quantity')->change();
            $table->string('total_tax_amount')->change();
        });
    }
};
