<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashSales extends Model
{
    protected $table = 'cashsales';

    protected $fillable = [
        'slip_no',
        'date',
        'ref_no',
        'item_name',
        'quantity',
        'rate',
        'amount',
        'Narration'
    ];
}
