<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseSample extends Model
{
    protected $table = 'purchase_samples';

    protected $fillable = [
        'date',
        'ref_no',
        'tanker',
        'transport',
        'oil_company',
        'invoice_no',
        'product',
        'hsd_temp',
        'hsd_base_density',
        'hsd_value',
        'hsd_sample',
        'hsd_invoice_sample',
        'hsd_plastic_seal',
        'hsd_aluminium_seal',
        'ms_temp',
        'ms_base_density',
        'ms_value',
        'ms_sample',
        'ms_invoice_sample',
        'ms_plastic_seal',
        'ms_aluminium_seal',
        'power_ms_temp',
        'power_ms_base_density',
        'power_ms_value',
        'power_ms_sample',
        'power_ms_invoice_sample',
        'power_ms_plastic_seal',
        'power_ms_aluminium_seal',
    ];

    protected $casts = [
        'date' => 'date',
        'hsd_temp' => 'decimal:2',
        'hsd_base_density' => 'decimal:4',
        'hsd_value' => 'decimal:4',
        'ms_temp' => 'decimal:2',
        'ms_base_density' => 'decimal:4',
        'ms_value' => 'decimal:4',
        'power_ms_temp' => 'decimal:2',
        'power_ms_base_density' => 'decimal:4',
        'power_ms_value' => 'decimal:4',
    ];
}
