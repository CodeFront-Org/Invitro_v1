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
Route::get('/Roles_registration_url',[App\Http\Controllers\RolesRegistration::class,'index'])->name('Roles_Regestration_url');//Registering roles built in app

Route::get('/admin_register_url',function(){//Register admin
    $label='Admin Register';
    return view('auth.new_admin_register',compact('label'));
});

Route::get('/registration', function () {//Register customer
    return view('auth.register');
});
//*******************************end registration */

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');