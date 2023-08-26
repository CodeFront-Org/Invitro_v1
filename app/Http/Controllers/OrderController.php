<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\Batch;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $label="Orders";
        $data=array();
        $orders=array();
        $products=Product::select('id','name')->get();
        $stocks=Stock::all()->where('approve',1);
        foreach($stocks as $s){
        //$product_id=$s->product_id;
        $batch_id=$s->batch_id;
        $stock_id=$s->id;
        // $product_name=Product::where('id',$product_id)->pluck('name')->first();
        // $product_qty=Product::where('id',$product_id)->pluck('quantity')->first();
        $batch_no=Batch::where('id',$batch_id)->pluck('batch_no')->first();
        array_push($data,[
            //'product_id'=>$product_id,
            //'product_name'=>$product_name,
            'stock_id'=>$stock_id,
            'batch_id'=>$batch_id,
            'batch_no'=>$batch_no
        ]);
        }
/////////////////////////////// for new order ////////////////////////////////////////////
        $data1=Order::all();
        foreach($data1 as $d){
            $p_id=$d->product_id;
            $batch_id=$d->batch_id;
            $batch_no=Batch::where('id',$batch_id)->pluck('batch_no')->first();
            $product_name=Product::where('id',$p_id)->pluck('name')->first();
            $quantity=$d->quantity;
            $destination=$d->destination;
            $invoice=$d->invoice;
            $approve=$d->approve;
            $rct=$d->receipt;
            $f_name=User::where('id',$d->user_id)->pluck('first_name')->first();
            $l_name=User::where('id',$d->user_id)->pluck('last_name')->first();
            $staff=$f_name." ".$l_name;
            $rmks=$d->remarks;
            $created_at=$d->created_at;
            array_push($orders,[
                'id'=>$d->id,
                'batch_id'=>$batch_id,
                'batch'=>$batch_no,
                'product_name'=>$product_name,
                'quantity'=>$quantity,
                'destination'=>$destination,
                'invoice'=>$invoice,
                'receipt'=>$rct,
                'staff'=>$staff,
                'rmks'=>$rmks,
                'approve'=>$approve,
                'date'=>$created_at,
            ]);
        }
//return $products;
        return view('app.order',compact('label','data','products','orders'));
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
        $product_id=$request->product_id;
        $batch_id=$request->batch_id;
        $quantity=$request->quantity;
        //$amount=$request->amount;
        $destination=$request->destination;
        $invoice=$request->invoice;
        $rct=$request->receipt;
        $rmks=$request->remarks;
        $order=Order::create([
        'user_id'=>Auth::id(),
        'batch_id'=>$batch_id,
        'product_id'=>$product_id,
        //'amount'=>$amount,
        'quantity'=>$quantity,
        'destination'=>$destination,
        'invoice'=>$invoice,
        'receipt'=>$rct,
        'remarks'=>$rmks,
        'approve'=>0,
        ]);
        if($order){
            return "200";
        }else{
            return '500';
        }
    }


public function return_stock(Request $request){
return $request;
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