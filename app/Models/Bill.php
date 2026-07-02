<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bill extends Model
{
    protected $fillable = [
        'bill_no',
        'bill_date',
        'date_from',
        'date_to',
        'party',
        'vehicle_no',
        'total_slips',
        'total_amount',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'bill_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(BillItem::class);
    }
}
