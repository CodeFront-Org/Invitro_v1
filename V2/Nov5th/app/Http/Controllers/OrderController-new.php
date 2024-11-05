<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\Orders;
use App\Models\Batch;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Mail\NewOrderAlertMail;
use App\Mail\OrderLevelNotification;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page_number=$request->page;

        if($page_number==1){
            $page_number=1;
        }elseif($page_number>1){
            $page_number=(($page_number-1)*30)+1;
        }else{
            $page_number=1;
        }
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
        $data1=Order::orderByDesc('id')->paginate(30);
        foreach($data1 as $d){
            $p_id=$d->product_id;
            $order_id=$d->id;
            $batch_id=$d->batch_id;
            $batch_no=Batch::where('id',$batch_id)->pluck('batch_no')->first();
            $product_name=Product::where('id',$p_id)->pluck('name')->first();
            $quantity=$d->quantity;
            $destination=$d->destination;
            $invoice=$d->invoice;
            $approve=$d->approve;
            $batch_used=$d->batch_used;
            $rct=$d->receipt;
            $f_name=User::withTrashed()->where('id',$d->user_id)->pluck('first_name')->first();
            $l_name=User::withTrashed()->where('id',$d->user_id)->pluck('last_name')->first();
            $staff=$f_name." ".$l_name;
            $rmks=$d->remarks;
            $created_at=$d->created_at;
            array_push($orders,[
                'id'=>$d->id,
                'product_id'=>$p_id,
                'batch_id'=>$batch_id,
                'order_id'=>$order_id,
                'batch'=>$batch_no,
                'batch_used'=>$batch_used,
                'product_name'=>$product_name,
                'quantity'=>$quantity,
                'destination'=>$destination,
                'invoice'=>$invoice,
                'receipt'=>$rct,
                'staff'=>$staff,
                'rmks'=>$rmks,
                'approve'=>$approve,
                'date'=>$created_at,//->format("F j Y"),
            ]);
        }

            //Get data for view data transactions
            $view_data=array();
            $views=Orders::all();
            foreach($views as $v){
                $batch_no=Batch::where('id',$v->batch_id)->pluck('batch_no')->first();
                $expiryDate = Batch::where('id', $v->batch_id)->pluck('expiry_date')->first();
                $e_date = \Carbon\Carbon::parse($expiryDate)->format("jS F Y");
                array_push($view_data,[
                    'id'=>$v->order_id,
                    'product_id'=>$v->product_id,
                    'batch_id'=>$v->batch_id,
                    'batch_no'=>$batch_no,
                    'init_qty'=>$v->init_qty,
                    'qty_used'=>$v->quantity_used,
                    'balance'=>$v->balance,
                    'expiry_date'=>$e_date,
                    ]);
            }
//return $view_data;
        return view('app.order',compact('label','data','products','orders','view_data','page_number','data1'));
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
        $d_note=$request->d_note;
        $cash=$request->cash;
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
            'delivery_note'=>$d_note,
            'cash'=>$cash,
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
//////////////////////////////////////////////////////    Send Email to notify approval of new Order  //////////////////////////////////////////////////////////
                $user=User::find(Auth::id());
                $name=$user->first_name;
                $product_name=Product::where('id',$product_id)->pluck('name')->first();
                $recipient=$user->email;
                $quantity=$quantity;
                $destination=$destination;
                $batch_no=Batch::where('id',$batch_id)->pluck('batch_no')->first();
                $link=env('APP_URL');
                //get all admin to be sent the mail
                $users=User::all()->where('role_type',1);
                foreach($users as $user){
                    $user=User::find($user->id);
                    $name=$user->first_name;
                    $recipient=$user->email;
                    Mail::to($recipient)->send(new NewOrderAlertMail($link, $recipient,$name,$product_name,$batch_no,$quantity,$destination));
                 }

                return "200";
            }else{
                return '500';
            }
        }
    }


    public function place_order(Request $request){

        $name=$request->name;
        $total_quantity=$request->quantity;
        $tot_qty=$request->quantity;
        $destination=$request->destination;
        $invoice=$request->invoice;
        $receipt=$request->receipt;
        $d_note=$request->d_note;
        $cash=$request->cash;
        $remarks=$request->remarks;

        //check if product with given name exists
        $check=Product::where('name',$name)->exists();
        if(!$check){//Product does not exist
            // Error message
            session()->flash('error', 'Product '.$name.' does not exists in your stocks. Please Check for typo and try again...');
            return back();
        }
        $product_id=Product::where('name',$name)->pluck('id')->first();
        $product_name=Product::where('id',$product_id)->pluck('name')->first();
        $label='Place Order For '.$product_name;

        //Check if total quantity orders is enough
        $sum=Product::where('id', $product_id)->sum('quantity');
        if($sum<$total_quantity){//alert that the total available quantity is below ordered quantity
            // Error message
            session()->flash('error', 'Quantity Exceeded! The available quantity is '.$sum.'.');
            return back();
        }

        //Check if someone had ordered beyond oreder level and its still in review by admin for approval
        $check=Product::where('id', $product_id)->pluck('is_order_level')->first();
        if($check==1){// Product is under review by the admin. Waiting approval.
            // Error message
            session()->flash('error', 'Product is under review by the admin. Waiting approval. Contact Admin for more info');
            return back();
        }

        //Determine whether admin has approved for below order level so as to raise flag for order_level.
        $check=Product::where('id',$product_id)->pluck('is_order_level')->first();
        if($check != 2){
            //check if order level is exceeded
            $prod=Product::find($product_id);
            $order_level=$prod->order_level;
            $current_qty=$prod->quantity;
            $qty=$request->quantity;
            $check=$current_qty-$qty;
            if($check<=$order_level){//Order Level has been exceeded
                // Update to product table
                Product::where('id',$product_id)->update([
                    'is_order_level'=>1,
                    'staff_order_level'=>Auth::id(),
                    'ordered_qty'=>$qty
                ]);

                // Send email and sms to Admin
///////////////////////////////////////////  Send SMS //////////////////////////////////////////////////////////////////////
                $users=User::all()->where('role_type',1);
                foreach($users as $user){
                    $user=User::find($user->id);
                    $mobile=$user->contacts;
                    //$mobile=User::where('id',Auth::id())->pluck('contacts')->first();
                    //$msg='Order Level for Product: '.$p_name.' has been reached.\n\nPlease Restock\nInvitro';
                    //$mobile=$user->contacts;
                    $msg='The Re-Order level for '.$product_name.' has been exceeded. Visit the Portal for more actions.\nRegards\nInvitro';
                    $curl = curl_init();
                                
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => env('TEXT_SMS_URL'),
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS => json_encode([
                        "apikey" => env('TEXT_SMS_KEY'),
                        "partnerID" => env('TEXT_SMS_PARTNERID'),
                        "mobile" => $mobile,
                        "message" => $msg,
                        "shortcode" => env('TEXT_SMS_SHORTCODE'),
                        "pass_type" => "plain",
                    ]),
                      CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Cookie: PHPSESSID=tv20bebn1qa6m9u57aa8du200c'
                      ),
                    ));
                    
                    $response = curl_exec($curl);
                    
                    curl_close($curl);

                 }
                //send email notification to admin for order level limit alert
                $users=User::all()->where('role_type',1);
                $link=env('APP_URL');
                $product_name=Product::where('id',$product_id)->pluck('name')->first();
                foreach($users as $user){
                    $user=User::find($user->id);
                    $name=$user->first_name;
                    $recipient=$user->email;
                    Mail::to($recipient)->send(new OrderLevelNotification($link, $recipient,$name,$product_name));

                }

                session()->flash('error','Re-Order Level has been Exceeded. A notification has been sent to the admin. Contact admin for further details.');
                return back();
            }
            
        }

        //Determine whether admin has approved for below order level so as to raise flag for order_level.
        $check=Product::where('id',$product_id)->pluck('is_order_level')->first();
        if($check == 2){
            //check if admin has approved and ensure quantity entered is the one specified by admin
            $admin_qty=Product::where('id',$product_id)->pluck('allowed_qty')->first();
            $qty=$request->quantity;
            if($admin_qty != $qty){// Quantity not equal
                session()->flash('error','Total quantity allowed for order by the admin is '.$admin_qty);
                return back();
            }
        }

        //Algorithim Starts
        $count=0;
        $data=array();//To store all info of the batches to be involved
        $batches=Batch::select('id','batch_no','quantity','sold','expiry_date')->where('product_id', $product_id)->where('sold_out',0)->where('approved',1)->orderBy('expiry_date', 'asc')->get();

        foreach($batches as $b){
            $batch_id=$b->id;
            $total_batch_qty=$b->quantity-$b->sold;
            $batch_no=$b->batch_no;
            //$expiry_date=$b->expiry_date;
            $expiryDate = Batch::where('id', $batch_id)->pluck('expiry_date')->first();
            $expiry_date = \Carbon\Carbon::parse($expiryDate)->format("jS F Y");
            //Check if the batch can give out the expected quantity if not then remove all its items and proceed to another batch in the loop till tot quantity arder is reached
            if($total_quantity==0){// Terminate operation since all orders are completed
                break;
            }
            if($total_quantity>$total_batch_qty){//return "Hey1";//remove and proceed to next batch so remaining will be 0(zero)
                $remaining=0;
                $used=$total_batch_qty;
                $total_quantity-=$total_batch_qty;
                array_push($data,[
                    'batch_id'=>$batch_id,
                    'batch_no'=>$batch_no,
                    'quantity'=>$total_batch_qty,
                    'used'=>$used,
                    'remaining'=>$remaining,
                    'expiry_date'=>$expiry_date
                    ]);
                    continue;
            }elseif($total_quantity==$total_batch_qty){//return "Hey2";// Have exactly what is left. so remaining will be 0(zero)
                $remaining=0;
                $used=$total_batch_qty;
                $total_quantity=0;
                array_push($data,[
                    'batch_id'=>$batch_id,
                    'batch_no'=>$batch_no,
                    'quantity'=>$total_batch_qty,
                    'used'=>$used,
                    'remaining'=>$remaining,
                    'expiry_date'=>$expiry_date
                    ]);
                    continue;

            }elseif($total_quantity<$total_batch_qty){//return "Hey3";// remove and dont proceed to next batch since that batch can give all.
                $remaining=$total_batch_qty-$total_quantity;
                $used=$total_quantity;
                $total_quantity=0;
                array_push($data,[
                    'batch_id'=>$batch_id,
                    'batch_no'=>$batch_no,
                    'quantity'=>$total_batch_qty,
                    'used'=>$used,
                    'remaining'=>$remaining,
                    'expiry_date'=>$expiry_date
                    ]);
                break;
            }
        }
        // Resetting the reorder level so that when someone orders again after reaching the limit they just can proceed
        Product::where("id",$product_id)->update(["is_order_level"=>0]);
        
        return view('app.place_order',compact(
            'label',
            'data',
            'product_id',
            'total_quantity',
            'tot_qty',
            'destination',
            'invoice',
            'receipt',
            'd_note',
            'cash',
            'remarks'
            ));
    }



    public function complete_order(Request $request){
        $product_id=$request->product_id;
        $quantity=$request->total_quantity;
        $destination=$request->destination;
        $invoice=$request->invoice;
        $rct=$request->receipt;
        $d_note=$request->d_note;
        $cash=$request->cash;
        $rmks=$request->remarks;
        $batchIds = $request->input('batch_id');
        $dataRemValues = $request->input('data_rem');

        //Get number of batches used
        $batchCount = count($batchIds);
        //First create order
            $order=Order::create([
            'user_id'=>Auth::id(),
            'product_id'=>$product_id,
            'batch_used'=>$batchCount,
            'quantity'=>$quantity,
            'destination'=>$destination,
            'invoice'=>$invoice,
            'receipt'=>$rct,
            'delivery_note'=>$d_note,
            'cash'=>0,
            'remarks'=>$rmks,
            'approve'=>0,
            ]);
        //Update relevant table affected by the order
        if($order){ //order created succesfully
            //iterate through the batches to be used
            foreach ($batchIds as $index => $batchId) {
            $batch_id = $batchId;
            $rem = $dataRemValues[$index];
            //Get the quantity used per batch
            $init_qty=Batch::where('id',$batch_id)->pluck('quantity')->first();
            $init_sold=Batch::where('id',$batch_id)->pluck('sold')->first();
            $qty_used=($init_qty-$init_sold)-$rem;

            //update batch_orders table
            Orders::create([
                'product_id'=>$product_id,
                'order_id'=>$order->id,
                'batch_id'=>$batch_id,
                'init_qty'=>$init_qty,
                'quantity_used'=>$qty_used,
                'balance'=>$rem
                ]);

            //Update Batch table. First determine whether a batch will be sold out
            $tot_used=$init_sold+$qty_used;
            $check=$init_qty-$tot_used;
            Batch::where('id',$batch_id)->update([
                'sold'=>$tot_used,
                'sold_out'=>$check==0?'1':'0',
                ]);

            //Update Products Table
            $prod_qty=Product::where('id',$product_id)->pluck('quantity')->first();
            $new_qty=$prod_qty-$qty_used;
            Product::where('id',$product_id)->update(['quantity'=>$new_qty]);

        //////////////////////////////////////////////////////    Send Email to notify approval of new Order  //////////////////////////////////////////////////////////
            $product_name=Product::where('id',$product_id)->pluck('name')->first();
            $quantity=$qty_used;
            $destination=$destination;
            $batch_no=Batch::where('id',$batch_id)->pluck('batch_no')->first();
            $link=env('APP_URL');
            //get all admin to be sent the mail
            $users=User::all()->where('role_type',1);
            foreach($users as $user){
                $user=User::find($user->id);
                $name=$user->first_name;
                $recipient=$user->email;
                Mail::to($recipient)->send(new NewOrderAlertMail($link, $recipient,$name,$product_name,$batch_no,$quantity,$destination));
             }
            }
        }
            session()->flash('message','Order Completed Successfully. Waiting for admin approval.');
            // Call the index() function so as to load the order page
            //return $this->index();
            return redirect()->route('order.index');
    }


    public function product_orders(Request $request){
        $product_id=$request->product_id;
        if(!$product_id){//make sure valid product id
            return back();
        }
        $product_name=Product::where('id',$product_id)->pluck('name')->first();
        $label=$product_name." Orders";
        $orders=[];
        $data1=Order::where('product_id',$product_id)->orderByDesc('id')->paginate(30);
        foreach($data1 as $d){
            $p_id=$d->product_id;
            $order_id=$d->id;
            $batch_id=$d->batch_id;
            $batch_no=Batch::where('id',$batch_id)->pluck('batch_no')->first();
            $product_name=Product::where('id',$p_id)->pluck('name')->first();
            $quantity=$d->quantity;
            $destination=$d->destination;
            $invoice=$d->invoice;
            $approve=$d->approve;
            $batch_used=$d->batch_used;
            $rct=$d->receipt;
            $f_name=User::withTrashed()->where('id',$d->user_id)->pluck('first_name')->first();
            $l_name=User::withTrashed()->where('id',$d->user_id)->pluck('last_name')->first();
            $staff=$f_name." ".$l_name;
            $rmks=$d->remarks;
            $created_at=$d->created_at;
            array_push($orders,[
                'id'=>$d->id,
                'batch_id'=>$batch_id,
                'order_id'=>$order_id,
                'batch'=>$batch_no,
                'batch_used'=>$batch_used,
                'product_name'=>$product_name,
                'quantity'=>$quantity,
                'destination'=>$destination,
                'invoice'=>$invoice,
                'receipt'=>$rct,
                'staff'=>$staff,
                'rmks'=>$rmks,
                'approve'=>$approve,
                'date'=>$created_at,//->format("F j Y"),
            ]);
        }

        
            //Get data for view data transactions
            $view_data=array();
            //$views=Orders::all();
            $views=Orders::all()->where('product_id',$product_id);
            foreach($views as $v){
                $batch=Batch::findOrFail($v->batch_id);
                $batch_no=$batch->batch_no;
                $expiryDate=$batch->expiry_date;
                // $batch_no=Batch::where('id',$v->batch_id)->pluck('batch_no')->first();
                // $expiryDate = Batch::where('id', $v->batch_id)->pluck('expiry_date')->first();
                $e_date = Carbon::parse($expiryDate)->format("jS F Y");
                array_push($view_data,[
                    'id'=>$v->order_id,
                    'product_id'=>$v->product_id,
                    'batch_id'=>$v->batch_id,
                    'batch_no'=>$batch_no,
                    'init_qty'=>$v->init_qty,
                    'qty_used'=>$v->quantity_used,
                    'balance'=>$v->balance,
                    'expiry_date'=>$e_date,
                    ]);
            }

            //return $data1;

        return view('app.product_orders',compact('label', 'orders','data1','view_data'));
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
        //// This is code that does rollback for the orders made and resulted in an error or needed editing.
        //// Preferred rollback over editing to avoid some conflicting processes
        $order_id=$id;
        $product_id=Order::where('id',$id)->pluck('product_id')->first();
        $orders=Orders::all()->where('order_id',$id);
        foreach($orders as $o){
            $batch_id=$o->batch_id;
            $qty_used=$o->quantity_used;
            $balance=$o->balance;

            //update Batch and Product table. Rollbacking the previou order made
            $sold=Batch::where('id',$batch_id)->pluck('sold')->first();
            $qty=Product::where('id',$product_id)->pluck('quantity')->first();
            Batch::where('id',$batch_id)->update([
                'sold'=>$sold-$qty_used,
                'sold_out'=>0,
                ]);
            Product::where('id',$product_id)->update([
                'quantity'=>$qty+$qty_used
                ]);

        }
        //Delete Order and batch_orders tables.
        Order::where('id',$order_id)->delete();
        Orders::where('order_id',$order_id)->delete();

    }
}
