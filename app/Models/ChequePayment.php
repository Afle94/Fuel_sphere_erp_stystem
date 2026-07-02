<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChequePayment extends Model
{
    protected $table = 'chequepayment';

    protected $fillable = [
        'date',
        'slip_no',
        'debit',
        'credit',
        'amount',
        'Narration',
        'cheque_no',
        'cheque_date'
    ];

    protected $casts = [
        'date' => 'date',
        'cheque_date' => 'date',
    ];
}
