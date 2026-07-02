<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('creditsales') || Schema::hasColumn('creditsales', 'bill_no')) {
            return;
        }

        Schema::table('creditsales', function (Blueprint $table) {
            $table->string('bill_no', 50)->nullable()->index()->after('Narration');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('creditsales') || ! Schema::hasColumn('creditsales', 'bill_no')) {
            return;
        }

        Schema::table('creditsales', function (Blueprint $table) {
            $table->dropColumn('bill_no');
        });
    }
};
