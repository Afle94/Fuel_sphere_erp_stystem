<?php

use App\Models\Purchase;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('purchase_items')) {
            Schema::create('purchase_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_id')->nullable()->constrained('purchase')->nullOnDelete();
                $table->string('Ref_no')->index();
                $table->date('date')->nullable()->index();
                $table->string('item_name');
                $table->decimal('quantity', 15, 3)->default(0);
                $table->decimal('rate', 15, 2)->default(0);
                $table->decimal('amount', 15, 2)->default(0);
                $table->decimal('discount%', 8, 2)->default(0);
                $table->decimal('discountinrs', 15, 2)->default(0);
                $table->decimal('taxable_amount', 15, 2)->default(0);
                $table->decimal('cgst', 8, 2)->default(0);
                $table->decimal('sgst', 8, 2)->default(0);
                $table->decimal('igst', 8, 2)->default(0);
                $table->decimal('total_tax_amount', 15, 2)->default(0);
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->timestamps();
            });
        }

        Schema::table('purchase', function (Blueprint $table) {
            if (! Schema::hasColumn('purchase', 'total_cgst_amount')) {
                $table->decimal('total_cgst_amount', 15, 2)->default(0)->after('total_tax_amount');
            }

            if (! Schema::hasColumn('purchase', 'total_sgst_amount')) {
                $table->decimal('total_sgst_amount', 15, 2)->default(0)->after('total_cgst_amount');
            }

            if (! Schema::hasColumn('purchase', 'total_igst_amount')) {
                $table->decimal('total_igst_amount', 15, 2)->default(0)->after('total_sgst_amount');
            }
        });

        if (DB::connection()->getDriverName() === 'mysql') {
            foreach ([
                'item_name' => 'VARCHAR(255) NULL',
                'quantity' => 'DECIMAL(15,3) NULL',
                'rate' => 'DECIMAL(15,2) NULL',
                'amount' => 'DECIMAL(15,2) NULL',
                'taxable_amount' => 'DECIMAL(15,2) NULL',
                'total_amount' => 'DECIMAL(15,2) NULL',
                'total_tax_amount' => 'DECIMAL(15,2) NULL',
            ] as $column => $definition) {
                if (Schema::hasColumn('purchase', $column)) {
                    DB::statement("ALTER TABLE `purchase` MODIFY `{$column}` {$definition}");
                }
            }
        }

        Purchase::query()
            ->whereNotNull('item_name')
            ->orderBy('id')
            ->get()
            ->each(function (Purchase $purchase) {
                $alreadyBackfilled = DB::table('purchase_items')
                    ->where('purchase_id', $purchase->id)
                    ->exists();

                if ($alreadyBackfilled) {
                    return;
                }

                DB::table('purchase_items')->insert([
                    'purchase_id' => $purchase->id,
                    'Ref_no' => $purchase->Ref_no,
                    'date' => $purchase->date,
                    'item_name' => $purchase->item_name,
                    'quantity' => $purchase->quantity ?? 0,
                    'rate' => $purchase->rate ?? 0,
                    'amount' => $purchase->amount ?? 0,
                    'discount%' => $purchase->{'discount%'} ?? 0,
                    'discountinrs' => $purchase->discountinrs ?? 0,
                    'taxable_amount' => $purchase->taxable_amount ?? 0,
                    'cgst' => $purchase->cgst ?? 0,
                    'sgst' => $purchase->sgst ?? 0,
                    'igst' => $purchase->igst ?? 0,
                    'total_tax_amount' => $purchase->total_tax_amount ?? 0,
                    'total_amount' => $purchase->total_amount ?? 0,
                    'created_at' => $purchase->created_at,
                    'updated_at' => $purchase->updated_at,
                ]);
            });

        Purchase::query()
            ->select('Ref_no')
            ->whereNotNull('Ref_no')
            ->groupBy('Ref_no')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('Ref_no')
            ->each(function ($refNo) {
                $purchases = Purchase::query()
                    ->where('Ref_no', $refNo)
                    ->orderBy('id')
                    ->get();

                $keeper = $purchases->first();

                if (! $keeper) {
                    return;
                }

                DB::table('purchase_items')
                    ->whereIn('purchase_id', $purchases->pluck('id'))
                    ->update(['purchase_id' => $keeper->id]);

                $items = DB::table('purchase_items')
                    ->where('purchase_id', $keeper->id)
                    ->get();

                $keeper->update([
                    'item_name' => $items->pluck('item_name')->filter()->unique()->implode(', '),
                    'quantity' => $items->sum(fn ($item) => (float) $item->quantity),
                    'rate' => $items->sum(fn ($item) => (float) $item->rate),
                    'amount' => $items->sum(fn ($item) => (float) $item->amount),
                    'discountinrs' => $items->sum(fn ($item) => (float) $item->discountinrs),
                    'taxable_amount' => $items->sum(fn ($item) => (float) $item->taxable_amount),
                    'total_tax_amount' => $items->sum(fn ($item) => (float) $item->total_tax_amount),
                    'total_amount' => $items->sum(fn ($item) => (float) $item->total_amount),
                    'total_cgst_amount' => $items->sum(fn ($item) => ((float) $item->taxable_amount * (float) $item->cgst) / 100),
                    'total_sgst_amount' => $items->sum(fn ($item) => ((float) $item->taxable_amount * (float) $item->sgst) / 100),
                    'total_igst_amount' => $items->sum(fn ($item) => ((float) $item->taxable_amount * (float) $item->igst) / 100),
                ]);

                $duplicateIds = $purchases->slice(1)->pluck('id');

                if ($duplicateIds->isNotEmpty()) {
                    if (Schema::hasTable('ledgers')) {
                        DB::table('ledgers')
                            ->where('VTYPE', 'PURCHASE')
                            ->whereIn('VOUCHERNO', $duplicateIds)
                            ->delete();
                    }

                    Purchase::query()
                        ->whereIn('id', $duplicateIds)
                        ->delete();
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_items');

        Schema::table('purchase', function (Blueprint $table) {
            foreach (['total_cgst_amount', 'total_sgst_amount', 'total_igst_amount'] as $column) {
                if (Schema::hasColumn('purchase', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
