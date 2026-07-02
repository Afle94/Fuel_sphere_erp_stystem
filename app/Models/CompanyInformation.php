<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInformation extends Model
{
    protected $fillable = [
        'company_name',
        'registered_office',
        'phone_no',
        'mobile_no',
        'email_id',
        'gst_no',
    ];
}
