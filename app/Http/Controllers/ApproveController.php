<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
use App\Models\User;

class ApproveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $label="Approvals";
        $data=array();
        $approvals=Stock::all()->sortByDesc('id')->where('approve',0);
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

        return view('app.approval',compact('label','data'));
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
        if($type==0){//Approve Stocks
            $data=$request->status;
            foreach($data as $item){
            //Approve Stock table
            Stock::where('id',$item)->update(['approve'=>1]);

            }
            session()->flash('message','success');
            return back();
        }elseif($type==1){//Approve orders

        }elseif($type==2){//Approve Re-turned stocks

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