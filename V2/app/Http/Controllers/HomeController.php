<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $label='Dashboard';

        $products=count(Product::all()->where("approve",1));
        $users=count(User::all());
        $orders=Order::all()->where("approve",1);

        return view('app.home',compact(
            'label',
            'products',
            'users',
            'orders',
        ));
    }
}