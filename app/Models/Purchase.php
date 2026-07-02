<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    protected $table = 'purchase';

    protected $fillable = [
       'perticulars',
         'Ref_no',
            'interstate',
            'postal address',
            'location',
            'date',
            'invoice_no',
            'vehicle_no',
            'transporter',
            'driver',
            'item_name',
            'quantity',
            'rate',
            'amount',
            'discount%',
            'taxable_amount',
            'cgst',
            'sgst',
            'igst',
            'total_amount',
            'total_tax_amount',
            'discountinrs',
            'total_cgst_amount',
            'total_sgst_amount',
            'total_igst_amount',



            
        
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:3',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
        'discount%' => 'decimal:2',
        'discountinrs' => 'decimal:2',
        'taxable_amount' => 'decimal:2',
        'cgst' => 'decimal:2',
        'sgst' => 'decimal:2',
        'igst' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'total_tax_amount' => 'decimal:2',
        'total_cgst_amount' => 'decimal:2',
        'total_sgst_amount' => 'decimal:2',
        'total_igst_amount' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_id');
    }
}
