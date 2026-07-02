<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyDip extends Model
{
    protected $table = 'dailydips';

    protected $fillable = [
        'date',
        'item',
        'enter_depth',
        'depth',
        'dip',
        'dip_depth',
        'liter',
        'litre',
        'ltr',
        'fy_opening_depth',
        'fy_opening_liter',
    ];

    protected $casts = [
        'date' => 'date',
        'enter_depth' => 'decimal:2',
        'depth' => 'decimal:2',
        'dip' => 'decimal:2',
        'dip_depth' => 'decimal:2',
        'liter' => 'decimal:2',
        'litre' => 'decimal:2',
        'ltr' => 'decimal:2',
        'fy_opening_depth' => 'decimal:2',
        'fy_opening_liter' => 'decimal:2',
    ];
}
