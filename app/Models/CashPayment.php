<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashPayment extends Model
{
    protected $table = 'cashpayment';

    protected $fillable = [
        'date',
        'slip_no',
        'debit',
        'credit',
        'amount',
        'Narration'
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
