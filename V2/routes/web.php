<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/reset',function(){
    return 200;
})->name('reset');

Auth::routes();
Route::post('/new_admin_reg', [App\Http\Controllers\Auth\NewAdminRegister::class,'register'])->name('new_admin_reg');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//Card controller
Route::resource('/cards',App\Http\Controllers\CardsController::class);
Route::get('/fetch-qty',[App\Http\Controllers\CardsController::class,'fetch_qty'])->name('fetch-qty');

// Application Routes
Route::resource('/users',App\Http\Controllers\UserController::class);
Route::get('/customers', [App\Http\Controllers\UserController::class, 'customers'])->name('customers');
Route::resource('/stock',App\Http\Controllers\StockController::class);
Route::resource('/batch-edit',App\Http\Controllers\BatchController::class);

Route::resource('/stock-card',App\Http\Controllers\StockcardsController::class);

Route::get('/batch-view',[App\Http\Controllers\BatchController::class, 'viewBatches']);

Route::post('/save_ExpiryDate',[App\Http\Controllers\BatchController::class,'changeExpiryDate']);

Route::resource('/order',App\Http\Controllers\OrderController::class);
Route::resource('/audits',App\Http\Controllers\AuditController::class);
Route::post('/return-stock', [App\Http\Controllers\OrderController::class, 'return_stock'])->name('/return-stock');
Route::get('/place-order', [App\Http\Controllers\OrderController::class, 'place_order'])->name('/place-order');
Route::get('/product-orders', [App\Http\Controllers\OrderController::class, 'product_orders'])->name('/product-orders');

Route::get('/complete-order', [App\Http\Controllers\OrderController::class, 'complete_order'])->name('/complete-order');



Route::resource('/approve',App\Http\Controllers\ApproveController::class);

Route::resource('/profile',App\Http\Controllers\ProfileController::class);
Route::Post('/pswUpdate',[App\Http\Controllers\ProfileController::class,'pswUpdate'])->name('pswUpdate');
Route::get('/product-details',[App\Http\Controllers\SearchController::class,'product'])->name('/product-details');

// Reports routes 
Route::get('/with-batch',[App\Http\Controllers\ReportsController::class,'productsWithBatch'])->name('/with-batch');
Route::get('/without-batch',[App\Http\Controllers\ReportsController::class,'productsWithoutBatch'])->name('/without-batch');
Route::get('/audited',[App\Http\Controllers\ReportsController::class,'productsAudited'])->name('/audited');
Route::get('/not-audited',[App\Http\Controllers\ReportsController::class,'productsNotAudited'])->name('/not-audited');
Route::get('/expired',[App\Http\Controllers\ReportsController::class,'productsExpired'])->name('/expired');
Route::get('/due-expiry',[App\Http\Controllers\ReportsController::class,'productsExpired'])->name('/due-expiry');
Route::get('/sales',[App\Http\Controllers\ReportsController::class,'sales'])->name('/sales');
Route::get('/sales-details',[App\Http\Controllers\ReportsController::class,'sales_details'])->name('/sales-details');//to narrow down to one product sales report
Route::get('/reorder-level',[App\Http\Controllers\ReportsController::class,'productsExpired'])->name('/reorder-level');

//  Route to test code before implementing
Route::get('/code',[App\Http\Controllers\TestController::class,'code'])->name('/code');
Route::get('/cron',[App\Http\Controllers\CronController::class,'cron'])->name('cron');