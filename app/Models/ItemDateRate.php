<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemDateRate extends Model
{
    protected $fillable = [
        'rate_date',
        'product_id',
        'rate',
    ];

    protected $casts = [
        'rate_date' => 'date',
        'rate' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function effectiveRatesByProductName(?string $date = null): Collection
    {
        return static::query()
            ->with('product:id,Product_Name')
            ->when($date, fn ($query) => $query->whereDate('rate_date', '<=', $date))
            ->orderByDesc('rate_date')
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get()
            ->filter(fn (ItemDateRate $rate) => $rate->product && trim((string) $rate->product->Product_Name) !== '')
            ->unique(fn (ItemDateRate $rate) => $rate->product->Product_Name)
            ->mapWithKeys(fn (ItemDateRate $rate) => [
                $rate->product->Product_Name => number_format((float) $rate->rate, 2, '.', ''),
            ]);
    }
}
