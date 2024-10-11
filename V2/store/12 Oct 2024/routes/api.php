<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\api\SMS;
use App\Http\Controllers\api\MPESA;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Authenticating api routes
Auth::routes();
//Api routes

//SMS Routes Both one and many sms
Route::post('/sms',[SMS::class,'send_sms'])->name('sms');
Route::post('/bulk-sms',[SMS::class,'bulk_sms'])->name('bulk-sms');

//Mpesa Routes 
//Route::get('/mpesa/lipaNaMpesaPassword',[MPESA::class,'lipaNaMpesaPassword'])->name('mpesa/lipaNaMpesaPassword');
//Route::get('/mpesa/newAccessToken',[MPESA::class,'newAccessToken'])->name('mpesa/newAccessToken');
Route::post('/mpesa/stk-push',[MPESA::class,'stkPush'])->name('mpesa/stk-push');
Route::get('/register-urls',[MPESA::class,'registerUrls'])->name('mpesa/register-urls');
Route::post('/mpesa/stk-callback-url',[MPESA::class,'MpesaRes']); //Callback url
Route::post('/c2b-callback-url',[MPESA::class,'c2b']); //Callback url
Route::post('/c2b-validation-url',[MPESA::class,'c2bv']); //Callback url
Route::post('/confirm-url',[MPESA::class,'confirmRes']); //Confirmation url
Route::post('/validation-url',[MPESA::class,'validateRes']); //Validations Url
