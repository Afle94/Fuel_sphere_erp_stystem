<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ledgers extends Model
{
    protected $table = 'ledgers';

    protected $primaryKey = 'ID';

    public $timestamps = false;

    protected $fillable = [
        'VOUCHERNO',
        'VTYPE',
        'TRANTYPE',
        'ACNO',
        'TRANDATE',
        'AMOUNT',
    ];

    protected $casts = [
        'TRANDATE' => 'date',
        'AMOUNT' => 'decimal:2',
    ];
}
