<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Nozzle extends Model
{
    use HasFactory;
    protected $fillable = [
        'Nozzle_Name',
        'Item',
        'Open_Date',
        'Close_Date',
    ];

    protected $casts = [
        'Open_Date' => 'date',
        'Close_Date' => 'date',
    ];

    public function Dayfuel()
    {
        return $this->hasMany(DayFuel::class);
    }


}
