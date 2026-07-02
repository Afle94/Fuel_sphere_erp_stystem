<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_samples', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('tanker')->nullable();
            $table->string('transport')->nullable();
            $table->string('oil_company')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('product')->nullable();

            $table->decimal('hsd_temp', 10, 2)->default(0);
            $table->decimal('hsd_base_density', 10, 4)->default(0);
            $table->decimal('hsd_value', 10, 4)->default(0);
            $table->string('hsd_sample')->nullable();
            $table->string('hsd_invoice_sample')->nullable();
            $table->string('hsd_plastic_seal')->nullable();
            $table->string('hsd_aluminium_seal')->nullable();

            $table->decimal('ms_temp', 10, 2)->default(0);
            $table->decimal('ms_base_density', 10, 4)->default(0);
            $table->decimal('ms_value', 10, 4)->default(0);
            $table->string('ms_sample')->nullable();
            $table->string('ms_invoice_sample')->nullable();
            $table->string('ms_plastic_seal')->nullable();
            $table->string('ms_aluminium_seal')->nullable();

            $table->decimal('power_ms_temp', 10, 2)->default(0);
            $table->decimal('power_ms_base_density', 10, 4)->default(0);
            $table->decimal('power_ms_value', 10, 4)->default(0);
            $table->string('power_ms_sample')->nullable();
            $table->string('power_ms_invoice_sample')->nullable();
            $table->string('power_ms_plastic_seal')->nullable();
            $table->string('power_ms_aluminium_seal')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_samples');
    }
};
