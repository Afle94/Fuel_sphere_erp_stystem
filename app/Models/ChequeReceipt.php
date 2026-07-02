<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChequeReceipt extends Model
{
    protected $table = 'chequereceipt';

    protected $fillable = [
        'date',
        'slip_no',
        'debit',
        'credit',
        'amount',
        'narration',
        'cheque_no',
        'datet',
    ];
}
