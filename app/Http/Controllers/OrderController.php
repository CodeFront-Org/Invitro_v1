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

use Carbon\Carbon;

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
        //To get data for batches
        $count=0; //to help filter similar products so as to have the FIFO on batches
        $batches=Batch::where('sold_out', 0)->orderBy('expiry_date', 'asc')->get();
        foreach($batches as $b){
            $p_id=$b->product_id;
            if($count==$p_id){//Similar product skip
            continue;
            }else{
                array_push($data,[
                    'batch_id'=>$b->id,
                    'batch_no'=>$b->batch_no
                ]);
            }
            $count=$p_id;
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
                'date'=>$created_at->format("F j Y"),
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
        $destination=$request->destination;
        $invoice=$request->invoice;
        $rct=$request->receipt;
        $rmks=$request->remarks;
        //Checking if quantity ordered is enough by using the batch
        $qty_db=Batch::where('id',$batch_id)->pluck('quantity')->first();//Initial quantity that was placed on recording of product
        $qty_sold=Batch::where('id',$batch_id)->pluck('sold')->first();// quantity that has been sold
        $available_qty=$qty_db-$qty_sold;
        if($quantity>$available_qty){//Order has exceeded limit
            return response()->json([
                'quantity' => $available_qty,
                'status' => 404
        ]);
        }else{//proceed to complete order
            $order=Order::create([
            'user_id'=>Auth::id(),
            'batch_id'=>$batch_id,
            'product_id'=>$product_id,
            'quantity'=>$quantity,
            'destination'=>$destination,
            'invoice'=>$invoice,
            'receipt'=>$rct,
            'remarks'=>$rmks,
            'approve'=>0,
            ]);
            if($order){
                //Update batch table
                $new_qty=$quantity+$qty_sold;//New sold value in batch table
                $c=$quantity-$available_qty;
                if($c==0){//meaning everything is sold out
                Batch::where('id',$batch_id)->update(['sold'=>$new_qty,'sold_out'=>1]);
                }else{
                Batch::where('id',$batch_id)->update(['sold'=>$new_qty]);
                }
///////////////////////////////////////////////////////////send Email for new Order  /////////////////////////////////////////////////////////////
                return "200";
            }else{
                return '500';
            }
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