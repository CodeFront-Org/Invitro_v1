<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $label="Stocks";
        return view('app.stock',compact('label'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id=Auth::id();
        $name=$request->name;
        $o_level=$request->o_level;
        $q_type=$request->q_type;
        $product=Product::create([
            'user_id'=>$id,
            'name'=>$name,
            'order_level'=>$o_level,
            'quantity_type'=>$q_type
            ]);

        if($product){
            $product_id=$product->id;
            Stock::create([
                'user_id'=>$id,
                'product_id'=>$product_id,
                'quantity'=>$request->quantity,
                'quantity_type'=>$q_type,
                'amount'=>$request->amount,
                'type'=>0, //To know whetehr its new(0) or return(1) stock when reading data
                'source'=>$request->source,
                'remarks'=>$request->remarks,
                'expiry_date'=>$request->e_date,
            ]);
        }else{
            return "error";
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}