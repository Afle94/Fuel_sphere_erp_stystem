<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dipparameter extends Model
{
    protected $fillable = [
        'item',
        'depth',
        'liter',
    ];
}
