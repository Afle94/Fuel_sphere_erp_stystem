<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditSalesreg extends Model
{
    protected $table = 'creditsalereg';

    protected $fillable = [
        'date',
        'ref_no',
        'Party_name',
        'vehicle_no',
        'item_name',
        'quantity',
        'rate',
        'amount'
    ];

        protected $casts = [
            'date' => 'date',
        ];

        public function credit_sales()
        {
            return $this->belongsto(CreditSales::class, 'credit_sales_id');
        }
}
