<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountMaster;
use App\Http\Controllers\AdvanceStockRegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardSalesController;
use App\Http\Controllers\CashReceiptController;
use App\Http\Controllers\CashPaymentController;
use App\Http\Controllers\CashSalesController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChequePaymentController;
use App\Http\Controllers\ChequeReceiptController;
use App\Http\Controllers\CompanyInformationController;
use App\Http\Controllers\CreditSalesController;
use App\Http\Controllers\DayFuelController;
use App\Http\Controllers\GenerateBillController;
use App\Http\Controllers\ItemDateRateController;
use App\Http\Controllers\NozzleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RegisterDayFuelFilterController;
use App\Http\Controllers\DensityController;
use App\Http\Controllers\DipparameterController;
use App\Http\Controllers\RegisterCreditSalesFilterController;
use App\Http\Controllers\RegisterCashSalesController;
use App\Http\Controllers\RegisterCashReceiptController;
use App\Http\Controllers\RegisterChequeReceiptController;
use App\Http\Controllers\AccountLedgerController;
use App\Http\Controllers\RegisterChequePaymentController;
use App\Http\Controllers\RegisterCashPaymentController;
use App\Http\Controllers\RegisterPurchaseController;
use App\Http\Controllers\OutstandingDebtorsController;
use App\Http\Controllers\RegisterDayBookController;
use App\Http\Controllers\RegisterProductWiseSalesController;
use App\Http\Controllers\StockReportController;

Route::get('/', function () {
    return view('user.login');
});

Route::get('/login', [AuthController::class, 'ShowLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'ShowRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/forgot-password', [AuthController::class, 'ShowForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'ShowResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::get('/company-information', [CompanyInformationController::class, 'edit'])->name('company-information.edit')->middleware('auth');
Route::post('/company-information', [CompanyInformationController::class, 'update'])->name('company-information.update')->middleware('auth');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
//Account Master
Route::get('/accountmaster', [AccountMaster::class, 'ShowAccountMaster'])->name('accountmaster')->middleware('auth');
Route::post('/accountmaster', [AccountMaster::class, 'StoreAccountMaster'])->name('accountmaster.store')->middleware('auth');
Route::get('/accounts', [AccountMaster::class, 'ListAccountMaster'])->name('accounts.index')->middleware('auth');
Route::get('/accounts/{id}/edit', [AccountMaster::class, 'editaccountmaster'])->name('accounts.edit')->middleware('auth');
Route::put('/accounts/{id}', [AccountMaster::class, 'updateaccountmaster'])->name('accounts.update')->middleware('auth');
Route::delete('/accounts/{id}', [AccountMaster::class, 'deleteaccountmaster'])->name('accounts.destroy')->middleware('auth');
Route::get('/download_accountmaster_pdf', [AccountMaster::class, 'ListAccountMaster_pdf'])->name('accounts.pdf')->middleware('auth');
Route::get('/export_accountmaster_excel', [AccountMaster::class, 'ListAccountMaster_excel'])->name('accounts.excel')->middleware('auth');
//Category Master
Route::get('/category', [CategoryController::class, 'showcategory'])->name('category')->middleware('auth');
Route::post('/category', [CategoryController::class, 'createcategory'])->name('category.create')->middleware('auth');
Route::get('/category/{id}/edit', [CategoryController::class, 'editcategory'])->name('category.edit')->middleware('auth');
Route::put('/category/{id}', [CategoryController::class, 'updatecategory'])->name('category.update')->middleware('auth');
Route::delete('/category/{id}', [CategoryController::class, 'deletecategory'])->name('category.destroy')->middleware('auth');
Route::get('/category/list', [CategoryController::class, 'listcategory'])->name('category.list')->middleware('auth');
Route::get('/download_category_pdf', [CategoryController::class, 'listcategory_pdf'])->name('category.pdf')->middleware('auth');
Route::get('/export_category_excel', [CategoryController::class, 'listcategory_excel'])->name('category.excel')->middleware('auth');
//Product Master
Route::get('/product', [ProductController::class, 'showproduct'])->name('product')->middleware('auth');
Route::post('/product', [ProductController::class, 'createproduct'])->name('product.create')->middleware('auth');
Route::get('/products', [ProductController::class, 'productList'])->name('product.list')->middleware('auth');
Route::get('/product/{id}/edit', [ProductController::class, 'editproduct'])->name('product.edit')->middleware('auth');
Route::put('/product/{id}', [ProductController::class, 'updateproduct'])->name('product.update')->middleware('auth');
Route::delete('/product/{id}', [ProductController::class, 'deleteproduct'])->name('product.destroy')->middleware('auth');
Route::get('/download_product_pdf', [ProductController::class, 'product_pdf'])->name('product.pdf')->middleware('auth');
Route::get('/export_product_excel', [ProductController::class, 'product_excel'])->name('product.excel')->middleware('auth');
//Item Date Wise Rate
Route::get('/item-date-rates', [ItemDateRateController::class, 'show'])->name('item-date-rates')->middleware('auth');
Route::post('/item-date-rates', [ItemDateRateController::class, 'store'])->name('item-date-rates.store')->middleware('auth');
Route::get('/item-date-rates/list', [ItemDateRateController::class, 'index'])->name('item-date-rates.list')->middleware('auth');
Route::get('/item-date-rates/{id}/edit', [ItemDateRateController::class, 'edit'])->name('item-date-rates.edit')->middleware('auth');
Route::put('/item-date-rates/{id}', [ItemDateRateController::class, 'update'])->name('item-date-rates.update')->middleware('auth');
Route::delete('/item-date-rates/{id}', [ItemDateRateController::class, 'destroy'])->name('item-date-rates.destroy')->middleware('auth');
Route::get('/download_item_date_rates_pdf', [ItemDateRateController::class, 'pdf'])->name('item-date-rates.pdf')->middleware('auth');
Route::get('/export_item_date_rates_excel', [ItemDateRateController::class, 'excel'])->name('item-date-rates.excel')->middleware('auth');
//Day Fuel Sale
Route::get('/day-fuel', [DayFuelController::class, 'showdayfuel'])->name('day-fuel.list')->middleware('auth');
Route::post('/day-fuel', [DayFuelController::class, 'storedayfuel'])->name('day-fuel.store')->middleware('auth');
Route::post('/day-fuel/dip-entry', [DayFuelController::class, 'storeDailyDip'])->name('day-fuel.dip-entry.store')->middleware('auth');
Route::get('/dip-chart', [DayFuelController::class, 'dipChart'])->name('daily-dip.index')->middleware('auth');
Route::get('/dip-chart/pdf', [DayFuelController::class, 'dipChartPdf'])->name('daily-dip.pdf')->middleware('auth');
Route::get('/dip-chart/excel', [DayFuelController::class, 'dipChartExcel'])->name('daily-dip.excel')->middleware('auth');
Route::get('/download_day_fuel_pdf', [DayFuelController::class, 'dayfuel_pdf'])->name('day-fuel.pdf')->middleware('auth');
Route::get('/export_day_fuel_excel', [DayFuelController::class, 'dayfuel_excel'])->name('day-fuel.excel')->middleware('auth');
//Nozzle Master
Route::get('/nozzle', [NozzleController::class, 'shownozzle'])->name('nozzle')->middleware('auth');
Route::post('/nozzle', [NozzleController::class, 'createnozzle'])->name('nozzle.create')->middleware('auth');
Route::get('/nozzles', [NozzleController::class, 'listnozzle'])->name('nozzle.list')->middleware('auth');
Route::get('/nozzle/{id}/edit', [NozzleController::class, 'editnozzle'])->name('nozzle.edit')->middleware('auth');
Route::put('/nozzle/{id}', [NozzleController::class, 'updatenozzle'])->name('nozzle.update')->middleware('auth');
Route::delete('/nozzle/{id}', [NozzleController::class, 'deletenozzle'])->name('nozzle.destroy')->middleware('auth');
Route::get('/download_nozzle_pdf', [NozzleController::class, 'nozzle_pdf'])->name('nozzle.pdf')->middleware('auth');
Route::get('/export_nozzle_excel', [NozzleController::class, 'nozzle_excel'])->name('nozzle.excel')->middleware('auth');
//Vehicle Master
Route::get('/vehicle', [VehicleController::class, 'showvehicle'])->name('vehicle')->middleware('auth');
Route::post('/vehicle', [VehicleController::class, 'createvehicle'])->name('vehicle.create')->middleware('auth');
Route::get('/vehicles', [VehicleController::class, 'listvehicle'])->name('vehicle.list')->middleware('auth');
Route::get('/vehicle/{id}/edit', [VehicleController::class, 'editvehicle'])->name('vehicle.edit')->middleware('auth'); 
Route::put('/vehicle/{id}', [VehicleController::class, 'updatevehicle'])->name('vehicle.update')->middleware('auth');
Route::delete('/vehicle/{id}', [VehicleController::class, 'deletevehicle'])->name('vehicle.destroy')->middleware('auth');
Route::get('/download_vehicle_pdf', [VehicleController::class, 'exportpdf'])->name('vehicle.pdf')->middleware('auth');
Route::get('/export_vehicle_excel', [VehicleController::class, 'exportexcel'])->name('vehicle.excel')->middleware('auth');
//Credit Sales
Route::get('/creditsales', [CreditSalesController::class, 'showcreditSales'])->name('creditsales')->middleware('auth');
Route::post('/creditsales', [CreditSalesController::class, 'storecreditsales'])->name('creditsales.store')->middleware('auth');
Route::put('/creditsales/{creditsale}', [CreditSalesController::class, 'updatecreditsales'])->name('creditsales.update')->middleware('auth');
Route::delete('/creditsales/{creditsale}', [CreditSalesController::class, 'destroycreditsales'])->name('creditsales.destroy')->middleware('auth');
Route::get('/download_creditsales_pdf', [CreditSalesController::class, 'creditsales_pdf'])->name('creditsales.pdf')->middleware('auth');
Route::get('/export_creditsales_excel', [CreditSalesController::class, 'creditsales_excel'])->name('creditsales.excel')->middleware('auth');
//Cash Sales
Route::get('/cashsales', [CashSalesController::class, 'showcashsales'])->name('cashsales')->middleware('auth');
Route::post('/cashsales', [CashSalesController::class, 'storecashsales'])->name('cashsales.store')->middleware('auth');
Route::put('/cashsales/{cashsale}', [CashSalesController::class, 'updatecashsales'])->name('cashsales.update')->middleware('auth');
Route::delete('/cashsales/{cashsale}', [CashSalesController::class, 'destroycashsales'])->name('cashsales.destroy')->middleware('auth');
Route::get('/download_cashsales_pdf', [CashSalesController::class, 'cashsales_pdf'])->name('cashsales.pdf')->middleware('auth');
Route::get('/export_cashsales_excel', [CashSalesController::class, 'cashsales_excel'])->name('cashsales.excel')->middleware('auth');
//Card Sales
Route::get('/cardsales', [CardSalesController::class, 'createcardSales'])->name('cardsales')->middleware('auth');
Route::post('/cardsales', [CardSalesController::class, 'storecardSales'])->name('cardsales.store')->middleware('auth');
Route::put('/cardsales/{cardsale}', [CardSalesController::class, 'updatecardSales'])->name('cardsales.update')->middleware('auth');
Route::delete('/cardsales/{cardsale}', [CardSalesController::class, 'destroycardSales'])->name('cardsales.destroy')->middleware('auth');
Route::get('/download_cardsales_pdf', [CardSalesController::class, 'cardsales_pdf'])->name('cardsales.pdf')->middleware('auth');
Route::get('/export_cardsales_excel', [CardSalesController::class, 'cardsales_excel'])->name('cardsales.excel')->middleware('auth');
//Cash Receipt
Route::get('/cashreceipt', [CashReceiptController::class, 'showcashreceipt'])->name('cashreceipt')->middleware('auth');
Route::post('/cashreceipt', [CashReceiptController::class, 'storecashreceipt'])->name('cashreceipt.store')->middleware('auth');
Route::put('/cashreceipt/{cashreceipt}', [CashReceiptController::class, 'updatecashreceipt'])->name('cashreceipt.update')->middleware('auth');
Route::delete('/cashreceipt/{cashreceipt}', [CashReceiptController::class, 'destroycashreceipt'])->name('cashreceipt.destroy')->middleware('auth');
Route::get('/download_cashreceipt_pdf', [CashReceiptController::class, 'cashreceipt_pdf'])->name('cashreceipt.pdf')->middleware('auth');
Route::get('/export_cashreceipt_excel', [CashReceiptController::class, 'cashreceipt_excel'])->name('cashreceipt.excel')->middleware('auth');
//Cheque Receipt
Route::get('/chequereceipt', [ChequeReceiptController::class, 'showchequereceipt'])->name('chequereceipt')->middleware('auth');
Route::post('/chequereceipt', [ChequeReceiptController::class, 'storechequereceipt'])->name('chequereceipt.store')->middleware('auth');
Route::put('/chequereceipt/{chequereceipt}', [ChequeReceiptController::class, 'updatechequereceipt'])->name('chequereceipt.update')->middleware('auth');
Route::delete('/chequereceipt/{chequereceipt}', [ChequeReceiptController::class, 'destroychequereceipt'])->name('chequereceipt.destroy')->middleware('auth');
Route::get('/download_chequereceipt_pdf', [ChequeReceiptController::class, 'chequereceipt_pdf'])->name('chequereceipt.pdf')->middleware('auth');
Route::get('/export_chequereceipt_excel', [ChequeReceiptController::class, 'chequereceipt_excel'])->name('chequereceipt.excel')->middleware('auth');
//Cheque Payment
Route::get('/chequepayment', [ChequePaymentController::class, 'showchequepayment'])->name('chequepayment')->middleware('auth');
Route::post('/chequepayment', [ChequePaymentController::class, 'storechequepayment'])->name('chequepayment.store')->middleware('auth');
Route::put('/chequepayment/{chequepayment}', [ChequePaymentController::class, 'updatechequepayment'])->name('chequepayment.update')->middleware('auth');
Route::delete('/chequepayment/{chequepayment}', [ChequePaymentController::class, 'destroychequepayment'])->name('chequepayment.destroy')->middleware('auth');
Route::get('/download_chequepayment_pdf', [ChequePaymentController::class, 'chequepayment_pdf'])->name('chequepayment.pdf')->middleware('auth');
Route::get('/export_chequepayment_excel', [ChequePaymentController::class, 'chequepayment_excel'])->name('chequepayment.excel')->middleware('auth');
//Cash Payment
Route::get('/cashpayment', [CashPaymentController::class, 'showcashPayment'])->name('cashpayment')->middleware('auth');
Route::post('/cashpayment', [CashPaymentController::class, 'storecashPayment'])->name('cashpayment.store')->middleware('auth');
Route::put('/cashpayment/{cashpayment}', [CashPaymentController::class, 'updatecashPayment'])->name('cashpayment.update')->middleware('auth');
Route::delete('/cashpayment/{cashpayment}', [CashPaymentController::class, 'destroycashPayment'])->name('cashpayment.destroy')->middleware('auth');
Route::get('/download_cashpayment_pdf', [CashPaymentController::class, 'cashpayment_pdf'])->name('cashpayment.pdf')->middleware('auth');
Route::get('/export_cashpayment_excel', [CashPaymentController::class, 'cashpayment_excel'])->name('cashpayment.excel')->middleware('auth');
//Purchase
Route::get('/purchase', [PurchaseController::class, 'showpurchase'])->name('purchase')->middleware('auth');
Route::post('/purchase', [PurchaseController::class, 'storepurchase'])->name('purchase.store')->middleware('auth');
Route::post('/purchase/sample', [PurchaseController::class, 'storePurchaseSample'])->name('purchase.sample.store')->middleware('auth');
Route::get('/purchase/sample/preview', [PurchaseController::class, 'purchaseSamplePreview'])->name('purchase.sample.preview')->middleware('auth');
Route::put('/purchase/{purchase}', [PurchaseController::class, 'updatepurchase'])->name('purchase.update')->middleware('auth');
Route::delete('/purchase/{purchase}', [PurchaseController::class, 'destroypurchase'])->name('purchase.destroy')->middleware('auth');
Route::get('/download_purchase_pdf', [PurchaseController::class, 'purchase_pdf'])->name('purchase.pdf')->middleware('auth');
Route::get('/export_purchase_excel', [PurchaseController::class, 'purchase_excel'])->name('purchase.excel')->middleware('auth');
Route::get('/download_purchase_sample_pdf', [PurchaseController::class, 'purchase_sample_pdf'])->name('purchase.sample.pdf')->middleware('auth');
Route::get('/export_purchase_sample_excel', [PurchaseController::class, 'purchase_sample_excel'])->name('purchase.sample.excel')->middleware('auth');
//Generate Bill
Route::get('/generate-bill', [GenerateBillController::class, 'index'])->name('generate-bill.index')->middleware('auth');
Route::get('/generate-bill/preview', [GenerateBillController::class, 'preview'])->name('generate-bill.preview')->middleware('auth');
Route::get('/generate-bill/list', [GenerateBillController::class, 'list'])->name('generate-bill.list')->middleware('auth');
Route::get('/generate-bill/list/pdf', [GenerateBillController::class, 'listPdf'])->name('generate-bill.list.pdf')->middleware('auth');
Route::get('/generate-bill/list/excel', [GenerateBillController::class, 'listExcel'])->name('generate-bill.list.excel')->middleware('auth');
Route::post('/generate-bill', [GenerateBillController::class, 'store'])->name('generate-bill.store')->middleware('auth');
Route::get('/generate-bill/{bill}', [GenerateBillController::class, 'show'])->name('generate-bill.show')->middleware('auth');
Route::get('/generate-bill/{bill}/pdf', [GenerateBillController::class, 'pdf'])->name('generate-bill.pdf')->middleware('auth');
//dayFuel Register
Route::get('/day-fuel/filterbydate', [RegisterDayFuelFilterController::class, 'filterbydate'])->name('dayfuelregisterfilter')->middleware('auth');
Route::get('/download_day_fuel_register_pdf', [RegisterDayFuelFilterController::class, 'pdf'])->name('dayfuelregister.pdf')->middleware('auth');
Route::get('/export_day_fuel_register_excel', [RegisterDayFuelFilterController::class, 'excel'])->name('dayfuelregister.excel')->middleware('auth');
//Density Chart
Route::get('/density-chart', [DensityController::class, 'showdensityChart'])->name('density.chart')->middleware('auth');
Route::post('/density-chart/import', [DensityController::class, 'import'])->name('density.import.store')->middleware('auth');
Route::delete('/density-chart', [DensityController::class, 'destroyAll'])->name('density.destroy-all')->middleware('auth');
//Dip Parameter
Route::get('/dip-parameter', [DipparameterController::class, 'index'])->name('dipparameter.index')->middleware('auth');
Route::post('/dip-parameter/import', [DipparameterController::class, 'import'])->name('dipparameter.import.store')->middleware('auth');
Route::delete('/dip-parameter', [DipparameterController::class, 'destroyAll'])->name('dipparameter.destroy-all')->middleware('auth');

// Accounts Ledger (Report)
Route::get('/accounts/ledger', [AccountLedgerController::class, 'index'])->name('accounts.ledger')->middleware('auth');
Route::get('/accounts/ledger/pdf', [AccountLedgerController::class, 'pdf'])->name('accounts.ledger.pdf')->middleware('auth');
Route::get('/accounts/ledger/excel', [AccountLedgerController::class, 'excel'])->name('accounts.ledger.excel')->middleware('auth');
Route::get('/outstanding-debtors', [OutstandingDebtorsController::class, 'index'])->name('outstanding.debtors')->middleware('auth');
Route::get('/outstanding-debtors/pdf', [OutstandingDebtorsController::class, 'pdf'])->name('outstanding.debtors.pdf')->middleware('auth');
Route::get('/outstanding-debtors/excel', [OutstandingDebtorsController::class, 'excel'])->name('outstanding.debtors.excel')->middleware('auth');
Route::get('/stock-report', [StockReportController::class, 'index'])->name('stock-report.index')->middleware('auth');
Route::get('/stock-report/pdf', [StockReportController::class, 'pdf'])->name('stock-report.pdf')->middleware('auth');
Route::get('/stock-report/excel', [StockReportController::class, 'excel'])->name('stock-report.excel')->middleware('auth');
Route::get('/purchase-item-list', [RegisterPurchaseController::class, 'itemList'])->name('purchase-item-list.index')->middleware('auth');
Route::get('/purchase-item-list/pdf', [RegisterPurchaseController::class, 'itemListPdf'])->name('purchase-item-list.pdf')->middleware('auth');
Route::get('/purchase-item-list/excel', [RegisterPurchaseController::class, 'itemListExcel'])->name('purchase-item-list.excel')->middleware('auth');
Route::get('/advance-stock-register', [AdvanceStockRegisterController::class, 'index'])->name('advance-stock-register.index')->middleware('auth');
Route::get('/advance-stock-register/pdf', [AdvanceStockRegisterController::class, 'pdf'])->name('advance-stock-register.pdf')->middleware('auth');
Route::get('/advance-stock-register/excel', [AdvanceStockRegisterController::class, 'excel'])->name('advance-stock-register.excel')->middleware('auth');
Route::post('/advance-stock-register/opening-dip', [AdvanceStockRegisterController::class, 'storeOpeningDip'])->name('advance-stock-register.opening-dip.store')->middleware('auth');

//Credit Sales Register Routes
Route::get('/credit-sales/filterbydate',[RegisterCreditSalesFilterController::class, 'filterbydate'])->name('creditsalesregisterfilter')->middleware('auth');
Route::get('/download_credit_sales_register_pdf',[RegisterCreditSalesFilterController::class, 'pdf'])->name('creditsalesregister.pdf')->middleware('auth');
Route::get('/export_credit_sales_register_excel',[RegisterCreditSalesFilterController::class, 'excel'])->name('creditsalesregister.excel')->middleware('auth');

//Cash Sales Register Routes

Route::controller(RegisterCashSalesController::class)->group(function () {
    Route::get('/cash-sales-register', 'filterbydate')->name('cashsalesregisterfilter');
    Route::get('/cash-sales-register/pdf', 'pdf')->name('cashsalesregister.pdf');
    Route::get('/cash-sales-register/excel', 'excel')->name('cashsalesregister.excel');
});

// cash receipt register routes
Route::controller(RegisterCashReceiptController::class)->group(function(){
    Route::get('/cash-receipt-register', 'filterbydate')->name('RegisterCashReceiptFilter');
    Route::get('/cash-receipt-register/pdf', 'pdf')->name('RegisterCashReceiptFilter.pdf');
    Route::get('/cash-receipt-register/excel', 'excel')->name('RegisterCashReceiptFilter.excel');

});
// Cheque Receipt Register Routes

Route::prefix('cheque-receipt-register')->group(function () {
    Route::get('/', [RegisterChequeReceiptController::class, 'filterbydate'])->name('RegisterChequeReceiptFilter');
    Route::get('/pdf', [RegisterChequeReceiptController::class, 'pdf'])->name('RegisterChequeReceipt.pdf');
    Route::get('/excel', [RegisterChequeReceiptController::class, 'excel'])->name('RegisterChequeReceipt.excel');
});

// Cash Payment Register Routes
Route::controller(RegisterCashPaymentController::class)->group(function () {
    Route::get('/cash-payment-register', 'filterbydate')->name('RegisterCashPaymentFilter');
    Route::get('/cash-payment-register/pdf', 'pdf')->name('RegisterCashPaymentFilter.pdf');
    Route::get('/cash-payment-register/excel', 'excel')->name('RegisterCashPaymentFilter.excel');
});

// Cheque Payment Register Routes
Route::controller(RegisterChequePaymentController::class)->group(function () {
    Route::get('/cheque-payment-register', 'filterbydate')->name('RegisterChequePaymentFilter');
    Route::get('/cheque-payment-register/pdf', 'pdf')->name('RegisterChequePaymentFilter.pdf');
    Route::get('/cheque-payment-register/excel', 'excel')->name('RegisterChequePaymentFilter.excel');
});

// Purchase Register Routes
Route::controller(RegisterPurchaseController::class)->group(function () {
    Route::get('/purchase-register', 'filterbydate')->name('RegisterPurchaseFilter');
    Route::get('/purchase-register/pdf', 'pdf')->name('RegisterPurchaseFilter.pdf');
    Route::get('/purchase-register/excel', 'excel')->name('RegisterPurchaseFilter.excel');
    Route::get('/purchase-register/reference/{refNo}/pdf', 'referencePdf')->name('RegisterPurchaseFilter.reference.pdf');
    Route::get('/purchase-register/reference/{refNo}/excel', 'referenceExcel')->name('RegisterPurchaseFilter.reference.excel');
});

// Day Book Register Routes
Route::get('/day-book-register', [RegisterDayBookController::class, 'index'])->name('RegisterDayBook');
Route::get('/day-book-register/pdf', [RegisterDayBookController::class, 'pdf'])->name('Day_Book_Register_pdf.pdf');
Route::get('/day-book-register/excel', [RegisterDayBookController::class, 'excel'])->name('daybook.excel');

// Product Wise Sales Register
Route::get('/register/product-wise-sales', [RegisterProductWiseSalesController::class, 'filterbydate'])->name('RegisterProductWiseSales');
Route::get('/register/product-wise-sales/pdf', [RegisterProductWiseSalesController::class, 'pdf'])->name('Product_Wise_Sales_Register_pdf.pdf');
Route::get('/register/product-wise-sales/excel', [RegisterProductWiseSalesController::class, 'excel'])->name('productwisesales.excel');
