<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditSales extends Model
{
    protected $table = 'creditsales';

    protected $fillable = [
        'slip_no',
        'date',
        'ref_no',
        'Party_name',
        'vehicle_no',
        'item_name',
        'quantity',
        'rate',
        'amount',
        'Narration',
        'bill_no',
    ];

    public function creditSalesreg()
    {
        return $this->hasMany(CreditSalesreg::class, 'credit_sales_id');
    }
    
}
