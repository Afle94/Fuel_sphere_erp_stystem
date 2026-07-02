<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillItem extends Model
{
    protected $fillable = [
        'bill_id',
        'bill_date',
        'vehicle_no',
        'slip_no',
        'item_name',
        'hsn_code',
        'qty',
        'rate',
        'amount',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'qty' => 'decimal:3',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }
}
