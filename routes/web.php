<?php

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
//*****************Routes for registering Roles, Permission and Admin on first creation of the app***//
Route::get('/Roles_registration_url',[App\Http\Controllers\Auth\RolesRegistration::class,'index'])->name('Roles_Regestration_url');//Registering roles built in app

Route::get('/admin_register_url',function(){//Register admin
    $label='Admin Register';
    return view('auth.new_admin_register',compact('label'));
});

Route::get('/registration', function () {//Register customer
    return view('auth.register');
});
//*******************************end registration */

//***************** Reset Routes ******************/
Route::post('/reset', [App\Http\Controllers\Auth\ResetController::class,'send_link'])->name('reset');//to send reset link
Route::get('/reset-password/{id}/{token}', [App\Http\Controllers\Auth\ResetController::class,'index'])->name('reset-password');//to load reset psw page
Route::post('/reset-psw', [App\Http\Controllers\Auth\ResetController::class,'reset'])->name('reset-psw');//to send reset link
//************* End reset routes *******************/

Auth::routes();
Route::post('/new_admin_reg', [App\Http\Controllers\Auth\NewAdminRegister::class,'register'])->name('new_admin_reg');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Application Routes
Route::resource('/users',App\Http\Controllers\UserController::class);
Route::get('/customers', [App\Http\Controllers\UserController::class, 'customers'])->name('customers');
Route::resource('/stock',App\Http\Controllers\StockController::class);
Route::resource('/order',App\Http\Controllers\OrderController::class);
Route::post('/return-stock', [App\Http\Controllers\OrderController::class, 'return_stock'])->name('/return-stock');
Route::resource('/approve',App\Http\Controllers\ApproveController::class);
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'profileUpdate'])->name('profile');
//Route to test email blade temp
Route::get('/test-email',function(){
return view('emails.expiry');
});