<?php

namespace App\Exports;

use Illuminate\Support\Collection;

class CashPayment extends CashPaymentExport
{
    public function __construct(Collection $cashpayments, string $selectedDate, array $theme = [])
    {
        parent::__construct($cashpayments, $selectedDate, $theme);
    }
}
