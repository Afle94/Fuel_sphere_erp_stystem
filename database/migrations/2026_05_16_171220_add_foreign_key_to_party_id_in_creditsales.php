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
         if (! Schema::hasTable('creditsales') || ! Schema::hasColumn('creditsales', 'party_id')) {
            return;
        }
        Schema::table('creditsales', function (Blueprint $table) {
            $table->foreign('party_id')->references('id')->on('parties')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            if (! Schema::hasTable('creditsales') || ! Schema::hasColumn('creditsales', 'party_id')) {
                return;
            }
        Schema::table('creditsales', function (Blueprint $table) {
            $table->dropForeign('creditsales_party_id_foreign');
            
        });
    }
};
