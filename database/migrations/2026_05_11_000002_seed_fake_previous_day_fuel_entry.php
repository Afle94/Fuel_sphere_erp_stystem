<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // No fake entries should be seeded. First real day must start from 0.00.
    }

    public function down(): void
    {
        // Nothing to rollback.
    }
};
