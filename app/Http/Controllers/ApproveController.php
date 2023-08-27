<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
use App\Models\User;
use App\Models\Batch;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

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
        $data=array();//stores approval for new stocks
        $orders=array();
/////////////////////////////// for new stock ////////////////////////////////////////////
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
            $type=$d->type;
            $source=$d->source;
            $remarks=$d->remarks;
            $created_at=$d->created_at;
            $b_id=$d->batch_id;
            $batch_no=Batch::where('id',$b_id)->pluck('batch_no')->first();
            $expiry_date=Batch::where('id',$b_id)->pluck('expiry_date')->first();
            $expiry_date = Carbon::createFromFormat('Y-m-d H:i:s', $expiry_date)->format('j F Y');
            //Pushing to data array structure
            array_push($data,[
                'id'=>$id,
                'name'=>$name,
                'batch_no'=>$batch_no,
                'quantity'=>$quantity.' '.$t,
                'order_level'=>$order_level,
                'source'=>$source,
                'staff_name'=>$staff_name,
                'date_in'=>$d->created_at,
                'expiry_date'=>$expiry_date,
                'remarks'=>$remarks
            ]);

        }
/////////////////////////////// for new order ////////////////////////////////////////////
        $data1=Order::all()->where('approve',0);
        foreach($data1 as $d){
            $p_id=$d->product_id;
            $batch_id=$d->batch_id;
            $batch_no=Batch::where('id',$batch_id)->pluck('batch_no')->first();
            $product_name=Product::where('id',$p_id)->pluck('name')->first();
            $quantity=$d->quantity;
            $destination=$d->destination;
            $invoice=$d->invoice;
            $rct=$d->receipt;
            $f_name=User::where('id',$d->user_id)->pluck('first_name')->first();
            $l_name=User::where('id',$d->user_id)->pluck('last_name')->first();
            $staff=$f_name." ".$l_name;
            $rmks=$d->remarks;
            $created_at=$d->created_at;
            array_push($orders,[
                'id'=>$d->id,
                'batch'=>$batch_no,
                'product_name'=>$product_name,
                'quantity'=>$quantity,
                'destination'=>$destination,
                'invoice'=>$invoice,
                'receipt'=>$rct,
                'staff'=>$staff,
                'rmks'=>$rmks,
                'date'=>$created_at,
            ]);
        }
        return view('app.approval',compact('label','data','orders'));
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
        if($type==0){//////////////////////////////////////////////////////////////////  Approve Stocks   //////////////////////////////////////////////////
            $data=$request->status;
            foreach($data as $item){
            //Approve Stock table
           $approve=Stock::where('id',$item)->update(['approve'=>1]);
        if($approve){
            //Log activity to file.. First get product name then save it to file
            $p_id=Stock::where('id',$item)->pluck('product_id')->first();
            $name=Product::where('id',$p_id)->pluck('name')->first();
            Log::channel('approve_stock')->notice($name.' stock appoved by '.Auth::user()->first_name.' '.Auth::user()->last_name.' Email: '.Auth::user()->email);
        }
            }
            session()->flash('message','success');
            return back();
        }elseif($type==1){///////////////////////////////////////////////////////////////  Approve orders ////////////////////////////////////////////////////
            $data=$request->status;
            foreach($data as $item){
//check if item is below order level
$qty=Order::where('id',$item)->pluck('quantity')->first();
$p_id=Order::where('id',$item)->pluck('product_id')->first();
$p_name=Product::where('id',$p_id)->pluck('name')->first();
$qty_db=Product::where('id',$p_id)->pluck('quantity')->first();
$order_level=Product::where('id',$p_id)->pluck('order_level')->first();
$new_qty=$qty_db-$qty;
if($new_qty<=$order_level){//below order level
    //Approve Order and update new product quantity
    $approve=Order::where('id',$item)->update(['approve'=>1]);
    Product::where('id',$p_id)->update(['quantity'=>$new_qty]);

    ////////////////////////////////////////////////////////send Email Notification  ////////////////////////////////////////////////
    $user=User::find(Auth::id());
    $name=$user->first_name;
    $product_name=$p_name;
    $recipient=$user->email;
    $current_order_qty=Product::where('id',$p_id)->pluck('quantity')->first();
    $link=env('APP_URL');
    Mail::to($recipient)->send(new ExpiryAlertMail($link, $recipient,$name,$product_name,$current_order_qty));

    //send SMS Notification To alert Order Limit
    $mobile=User::where('id',Auth::id())->pluck('contacts')->first();
    //$msg='Order Level for Product: '.$p_name.' has been reached.\n\nPlease Restock\nInvitro';
    $msg='The order level for '.$p_name.' has been reached. The current order quantity is  '.$current_order_qty.'\n\nPlease Restock\nInvitro';
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => env('SMS_URL'),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
        "mobile":"'.$mobile.'",
        "response_type": "json",
        "sender_name":"'.env('SENDER_NAME').'",
        "service_id": 0,
        "message": "'.$msg.'"
    }',
    CURLOPT_HTTPHEADER => array(
        'h_api_key:'.env('SMS_KEY'),
        'Content-Type: application/json'
    ),
    ));

    $response = curl_exec($curl);
}else{//Proceed to approval
    //Approve Order table
    $approve=Order::where('id',$item)->update(['approve'=>1]);
    Product::where('id',$p_id)->update(['quantity'=>$new_qty]);
}
        if($approve){
            //Log activity to file.. First get product name then save it to file
            $p_id=Order::where('id',$item)->pluck('product_id')->first();
            $name=Product::where('id',$p_id)->pluck('name')->first();
            Log::channel('approve_stock')->notice($name.' stock appoved by '.Auth::user()->first_name.' '.Auth::user()->last_name.' Email: '.Auth::user()->email);
        }
            }
            session()->flash('message','success');
            return back();
        }elseif($type==2){//////////////////////////////////////////////////////////Approve Re-turned stocks

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