<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_samples', function (Blueprint $table) {
            if (! Schema::hasColumn('purchase_samples', 'ref_no')) {
                $table->string('ref_no')->nullable()->after('date')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchase_samples', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_samples', 'ref_no')) {
                $table->dropColumn('ref_no');
            }
        });
    }
};
