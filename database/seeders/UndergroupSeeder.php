<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UndergroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('under_group')->insert([
            ['group_name' => 'ADJUSTMENT'],
            ['group_name' => 'BANK ACCOUNTS'],
            ['group_name' => 'BANK OCC A/C'],
            ['group_name' => 'BILL ADJUSTMENT'],
            ['group_name' => 'BILL SALES TAX'],
            ['group_name' => 'BRANCH & DIVISION'],
            ['group_name' => 'CAPITAL A/C'],
            ['group_name' => 'CARRIAGE (INWARD)'],
            ['group_name' => 'CARRIAGE (OUTWARD)'],
            ['group_name' => 'CASH IN HAND'],
            ['group_name' => 'CASH PURCHASE'],
            ['group_name' => 'CASH PURCHASE RETURN'],
            ['group_name' => 'CASH SALES'],
            ['group_name' => 'CASH SALES RETURN'],
            ['group_name' => 'COURIER CHARGES'],
            ['group_name' => 'CREDIT PURCHASE'],
            ['group_name' => 'CREDIT PURCHASE RETURN'],
            ['group_name' => 'CREDIT SALES'],
            ['group_name' => 'CREDIT SALES RETURN'],
            ['group_name' => 'CURRENT ASSETS'],
            ['group_name' => 'CURRENT LIABILITIES'],
            ['group_name' => 'DEPOSITS (ASSETS)'],
            ['group_name' => 'DIRECT INCOME'],
            ['group_name' => 'DISCOUNT'],
            ['group_name' => 'DUTIES & TAXES'],
            ['group_name' => 'EXPENDITURE ACCOUNT'],
            ['group_name' => 'EXPENSES (DIRECT)'],
            ['group_name' => 'EXPENSES (INDIRECT)'],
            ['group_name' => 'FIX ASSETS'],
            ['group_name' => 'GODAM CONSTRUCTION'],
            ['group_name' => 'INCOME (REVENUE)'],
            ['group_name' => 'INDIRECT INCOME'],
            ['group_name' => 'INVESTMENTS'],
            ['group_name' => 'KISAN LOAN A/C'],
            ['group_name' => 'LOANS & ADVANCES (ASSET)'],
            ['group_name' => 'LOANS (LIABILITIES)'],
            ['group_name' => 'MISC.EXPENSES'],
            ['group_name' => 'OPENING STOCK'],
            ['group_name' => 'OTHERS'],
            ['group_name' => 'PROFIT & LOSS A/C'],
            ['group_name' => 'PROVISIONS'],
            ['group_name' => 'PURCHASE'],
            ['group_name' => 'PURCHASE RETURN'],
            ['group_name' => 'PURCHASE2'],
            ['group_name' => 'RESERVES & SURPLUS'],
            ['group_name' => 'REVENUE ACCOUNTS'],
            ['group_name' => 'SALARY A/C'],
            ['group_name' => 'SALES'],
            ['group_name' => 'SALES ADJUSTMENT'],
            ['group_name' => 'SALES MAN'],
            ['group_name' => 'SALES RETURN'],
            ['group_name' => 'SALES TAX'],
            ['group_name' => 'SALES2'],
            ['group_name' => 'SCHEME'],
            ['group_name' => 'SECURED LOANS'],
            ['group_name' => 'SERVICE CHARGES'],
            ['group_name' => 'SUNDRY CREDITORS'],
            ['group_name' => 'SUNDRY DEBTORS'],
            ['group_name' => 'SURCHARGE'],
            ['group_name' => 'SUSPENSE A/C'],
            ['group_name' => 'UNSECURED LOANS'],
            ['group_name' => 'VAT TAX'],
        ]);
    }
}
