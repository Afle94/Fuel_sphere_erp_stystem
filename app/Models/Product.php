<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'produts';

    protected $fillable = [
        'Product_Name',
        'HSN',
        'GST_per',
        'Category',
        'Purchase_rate',
        'opening_stock',
        'opening_stock_value',
    ];

    protected $casts = [
        'GST_per' => 'decimal:2',
        'Purchase_rate' => 'decimal:2',
        'opening_stock' => 'integer',
        'opening_stock_value' => 'decimal:2',
    ];
}
