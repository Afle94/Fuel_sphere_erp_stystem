<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Density extends Model
{
    protected $table = 'density';

    protected $fillable = [
        'fuel_type',
        'temperature',
        'base_dens',
        'chart_val',
    ];
}
