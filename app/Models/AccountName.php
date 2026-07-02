<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountName extends Model
{
    protected $table = 'account_name';

    protected $fillable = [
        'account_perticular',
        'under_group',
        'opening_balance',
        'transaction_type',
        'address',
        'city',
        'state',
        'email',
        'mobile_number',
        'phone_number',
        'gst_number'
    ];
    
}
