<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
use App\Models\User;
use App\Models\Batch;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $data1=Product::all();//to be used to display some product during restocking
        $approvals=Stock::all()->sortByDesc('id')->where('approve',1);
        foreach($approvals as $d){
            //Pick data from products table
            $name=Product::where('id',$d->product_id)->pluck('name')->first();
            $order_level=Product::where('id',$d->product_id)->pluck('order_level')->first();
            $quantity_type=Product::where('id',$d->product_id)->pluck('quantity_type')->first();
            $quantity=Product::where('id',$d->product_id)->pluck('quantity')->first();
            if($quantity_type==0){$t='Carton(s)';}elseif($quantity_type==1){$t='Packets';}else{$t='Items';}
            //pick remainig data from stock table
            $id=$d->id;
            $user_id=$d->user_id;
            $f_name=User::where('id',$user_id)->pluck('first_name')->first();
            $l_name=User::where('id',$user_id)->pluck('last_name')->first();
            $staff_name=$f_name.' '.$l_name;
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
                'order_level'=>$order_level,
                'source'=>$source,
                'staff_name'=>$staff_name,
                'date_in'=>$d->created_at,
                'expiry_date'=>$d->expiry_date,
                'remarks'=>$remarks
            ]);

        }
        return view('app.stock',compact('label','data','data1'));
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
            $batch_no=$request->batch_no;
            // Start the transaction
            DB::beginTransaction();
try {
        // Create a new Batch instance
        $batch = new Batch([
            'batch_no' => $batch_no,
            'quantity' => $request->quantity,
        ]);
        // Save the batch to the database
        if($batch->save()){
            $product=Product::create([
                    'user_id'=>$id,
                    'name'=>$name,
                    'quantity'=>$request->quantity,
                    'order_level'=>$o_level,
                    'quantity_type'=>$q_type
                    ]);
                if($product){
                // Commit the transaction if everything is successful
                DB::commit();//Save Batch info
                //update product id number in batch file
                Batch::where('id',$batch->id)->update(['product_id'=>$product->id]);
                    $product_id=$product->id;
                        $stock=Stock::create([
                            'user_id'=>$id,
                            'product_id'=>$product_id,
                            'batch_id'=>$batch->id,
                            'quantity'=>$request->quantity,
                            'sold'=>0,//indicate that 0 have been sold since its new stock
                            'quantity_type'=>$q_type,
                            'type'=>0, //To know whether its new(0) or return(1) stock when reading data
                            'source'=>$request->source,
                            'remarks'=>$request->remarks,
                            'expiry_date'=>$request->e_date,
                        ]);

                } else {echo "101";//Error fo duplication for product name
                    // Handle any exceptions that occurred during the transaction
                    DB::rollback();
                    return "101";
                }
        }else{echo "100";//batch number already exists
                }
} catch (\Exception $e) {echo "103";
    // Handle any exceptions that occurred during the transaction
    DB::rollback();
    // Optionally, you can log the error or perform any other necessary actions
}



        }elseif($type==1){//**************************** store restock  ********************************************//
            $id=Auth::id();
            $product_id=$request->name;
            $o_level=$request->o_level;
            $quantity=$request->quantity;
            $batch_no=$request->batch_no;
            //Store in batch tables
            $batch=Batch::create(['batch_no'=>$batch_no,'product_id'=>$product_id,'quantity'=>$quantity]);
            if($batch){
                //Get product quantity from db and increment
                $qty=Product::where('id',$product_id)->pluck('quantity')->first();
                $q_type=Product::where('id',$product_id)->pluck('quantity_type')->first();
                $product=Product::where('id',$product_id)->update([
                    'order_level'=>$o_level,
                    'quantity'=>$qty+$quantity,
                    ]);

                if($product){
                    $product_id=$product_id;
                    $stock=Stock::create([
                        'user_id'=>$id,
                        'product_id'=>$product_id,
                        'batch_id'=>$batch->id,
                        'quantity'=>$request->quantity,
                        'quantity_type'=>$q_type,
                        'sold'=>0,//indicate that 0 have been sold since its new stock
                        'type'=>0, //To know whetehr its new(0) or return(1) stock when reading data
                        'source'=>$request->source,
                        'remarks'=>$request->remarks,
                        'approve'=>0,
                        'expiry_date'=>$request->e_date,
                    ]);
                if($stock){
                    //Log activity to file
                    Log::channel('add_stock')->notice('New stock added. Of Id: '.$product_id.'. Added by '.Auth::user()->first_name.' Email: '.Auth::user()->email);
                }
                }else{
                    return "error";
                }
            }else{
                throw new \Exception('Batch exist');
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
