<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DayFuel extends Model
{
    use HasFactory;

    protected $table = 'day_fuel';

    protected $fillable = [
        'date',
        'nozzle_id',
        'Nozzel_id',
        'open',
        'close',
        'Test',
        'Quantity',
        'items',
        'rate',
        'Amount',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function Nozzle()
    {
        return $this->belongsTo(Nozzle::class, 'nozzle_id');
    }

}
