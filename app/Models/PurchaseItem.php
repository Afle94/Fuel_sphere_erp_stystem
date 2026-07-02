<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    protected $table = 'purchase_items';

    protected $fillable = [
        'purchase_id',
        'Ref_no',
        'date',
        'item_name',
        'quantity',
        'rate',
        'amount',
        'discount%',
        'discountinrs',
        'taxable_amount',
        'cgst',
        'sgst',
        'igst',
        'total_tax_amount',
        'total_amount',
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:3',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
        'discount%' => 'decimal:2',
        'discountinrs' => 'decimal:2',
        'taxable_amount' => 'decimal:2',
        'cgst' => 'decimal:2',
        'sgst' => 'decimal:2',
        'igst' => 'decimal:2',
        'total_tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
}
