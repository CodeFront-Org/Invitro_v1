<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
use App\Models\User;
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
        $data=array();
        $approvals=Stock::all()->sortByDesc('id')->where('approve',1);
        foreach($approvals as $d){
            //Pick data from products table
            $name=Product::where('id',$d->product_id)->pluck('name')->first();
            $order_level=Product::where('id',$d->product_id)->pluck('order_level')->first();
            $quantity_type=Product::where('id',$d->product_id)->pluck('quantity_type')->first();
            if($quantity_type==0){$t='Carton(s)';}elseif($quantity_type==1){$t='Packets';}else{$t='Items';}
            //pick remainig data from stock table
            $id=$d->id;
            $user_id=$d->user_id;
            $f_name=User::where('id',$user_id)->pluck('first_name')->first();
            $l_name=User::where('id',$user_id)->pluck('last_name')->first();
            $staff_name=$f_name.' '.$l_name;
            $quantity=$d->quantity;
            $amount=$d->amount;
            $type=$d->type;
            $source=$d->source;
            $remarks=$d->remarks;
            $expiry_date=$d->expiry_date;
            $created_at=$d->created_at;
            //Pushing to data array structure
            array_push($data,[
                'id'=>$id,
                'product_id'=>$d->product_id,
                'name'=>$name,
                'quantity'=>$quantity.' '.$t,
                'amount'=>$amount,
                'order_level'=>$order_level,
                'source'=>$source,
                'staff_name'=>$staff_name,
                'date_in'=>$d->created_at,
                'expiry_date'=>$d->expiry_date,
                'remarks'=>$remarks
            ]);

        }
        return view('app.stock',compact('label','data'));
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
        $type=$request->type;
        if($type==0){//Store new stock
            $id=Auth::id();
            $name=$request->name;
            $o_level=$request->o_level;
            $q_type=$request->q_type;
            $product=Product::create([
                'user_id'=>$id,
                'name'=>$name,
                'quantity'=>$request->quantity,
                'order_level'=>$o_level,
                'quantity_type'=>$q_type
                ]);

            if($product){
                $product_id=$product->id;
                $stock=Stock::create([
                    'user_id'=>$id,
                    'product_id'=>$product_id,
                    'quantity'=>$request->quantity,
                    'quantity_type'=>$q_type,
                    'amount'=>$request->amount,
                    'type'=>0, //To know whether its new(0) or return(1) stock when reading data
                    'source'=>$request->source,
                    'remarks'=>$request->remarks,
                    'expiry_date'=>$request->e_date,
                ]);
            if($stock){
                //Log activity to file
                Log::channel('add_stock')->notice('New stock added. Name: '.$name.'. Added by '.Auth::user()->first_name.' Email: '.Auth::user()->email);
            }
            }else{
                return "error";
            }
        }elseif($type==1){//**************************** store restock  ********************************************//
            $id=Auth::id();
            $product_id=$request->name;
            $name=$request->name;
            $o_level=$request->o_level;
            $quantity=$request->quantity;
            //Get product quantity from db and increment
            $qty=Product::where('id',$product_id)->pluck('quantity')->first();
            $q_type=Product::where('id',$product_id)->pluck('quantity_type')->first();
            $product=Product::where('id',$product_id)->update([
                'name'=>$name,
                'order_level'=>$o_level,
                'quantity'=>$qty+$quantity,
                ]);

            if($product){
                $product_id=$product_id;
                $stock=Stock::create([
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
            if($stock){
                //Log activity to file
                Log::channel('add_stock')->notice('New stock added. Name: '.$name.'. Added by '.Auth::user()->first_name.' Email: '.Auth::user()->email);
            }
            }else{
                return "error";
            }
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
