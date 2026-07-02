<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashReceipt extends Model
{
    protected $table = 'cashreceipt';

    protected $fillable = [
        'date',
        'slip_no',
        'credit',
        'debit',
        'amount',
        'narration',
    ];
    
}
