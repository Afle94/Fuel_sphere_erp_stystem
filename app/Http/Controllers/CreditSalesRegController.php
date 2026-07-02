<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CreditSales;

class CreditSalesRegController extends Controller
{
    public function storecreditSalesReg(CreditSales $creditSales):void
    {
        $creditSales->registers()->updateOrCreate(
            ['credit_sales_id' => $creditSales->id],
            [
                'date' => $creditSales->date,
                'ref_no' => $creditSales->ref_no,
                'Party_name' => $creditSales->Party_name,
                'vehicle_no' => $creditSales->vehicle_no,
                'item_name' => $creditSales->item_name,
                'quantity' => $creditSales->quantity,
                'rate' => $creditSales->rate,
                'amount' => $creditSales->amount
            ]
        );
        
    }
}
