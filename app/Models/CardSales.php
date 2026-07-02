<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardSales extends Model
{
    protected $table = 'cardsales';

    protected $fillable = [
        'date',
        'Card_type',
        'Batch_no',
        'invoice_no',
        'Amount',
        'perticulars',
        'narration',
    ];
    
}
