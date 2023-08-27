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

use Carbon\Carbon;

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
        $data=array();//load data in 1st table before viewing transactions
        $data2=array();//load data to be viewed for transactions
        $data1=Product::all();//to be used to display some product during restocking

        foreach($data1 as $p){
            $product_name=$p->name;
            $p_id=$p->id;
            $order_level=$p->order_level;
            //pick from batches table
            $batch=Batch::all()->where('product_id',$p_id);
            $total_batch=count($batch);
            $total_qty_approved=Stock::where('approve',1)->where('product_id',$p_id)->sum('quantity');//total qty approved
            $total_qty_not_approved=Stock::where('approve',0)->where('product_id',$p_id)->sum('quantity');//total qty approved
            $qty_available=Batch::where('product_id',$p_id)->where('sold_out',0)->sum('quantity');
            //$total_qty=0;
            //Push data for table stock
            array_push($data,[
                'id'=>$p_id,
                'name'=>$product_name,
                'qty_available'=>$qty_available,
                'qty_not_approved'=>$total_qty_not_approved,
                'batch'=>$total_batch,
                'order_level'=>$order_level
            ]);
                //get data from stock table
                $stocks=Stock::all()->where('product_id',$p_id)->sortByDesc('id');
                foreach($stocks as $s){
                    $source=$s->source;
                    $date=$s->created_at;
                    $date = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('F jS Y');
                    $remarks=$s->remarks;
                    $approve=$s->approve;
                    $b_id=$s->batch_id;
                    $batch_qty=Batch::where('id',$b_id)->pluck('quantity')->first();
                    $batch_no=Batch::where('id',$b_id)->pluck('batch_no')->first();
                    $batch_id=Batch::where('id',$b_id)->pluck('id')->first();
                    $expiry=Batch::where('id',$b_id)->pluck('expiry_date')->first();
                    $expiry = Carbon::createFromFormat('Y-m-d H:i:s', $expiry)->format('F jS Y');
                    $sold=Batch::where('id',$b_id)->pluck('sold_out')->first();//Help know which batches have been sold out so as to not display them
                    //getting user name
                    $ff=$s->user_id;
                    $f=User::where('id',$ff)->pluck('first_name')->first();
                    $l=User::where('id',$ff)->pluck('last_name')->first();
                    $staff=$f." ".$l;
                    //push data for transaction
                    if($sold==0){
                        array_push($data2,[
                            'id'=>$s->id,
                            'product_id'=>$p_id,
                            'name'=>$product_name,
                            'quantity'=>$batch_qty,
                            'quantity_type'=>$s->quantity_type,
                            'batch_no'=>$batch_no,
                            'batch_id'=>$batch_id,
                            'source'=>$source,
                            'staff'=>$staff,
                            'date_in'=>$date,
                            'expiry'=>$expiry,
                            'remarks'=>$remarks,
                            'approve'=>$approve
                            ]);
                    }
                }



        }

//return $data2;
        return view('app.stock',compact('label','data','data1','data2'));
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
            $e_date=$request->e_date;
            // Start the transaction
            DB::beginTransaction();
try {
        // Create a new Batch instance
        $batch = new Batch([
            'batch_no' => $batch_no,
            'quantity' => $request->quantity,
            'expiry_date'=>$e_date,
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
                        ]);
//////////////////////////////////////////////////////    Send Email to check expiry of product and end alert //////////////////////////////////////////////////////////
                        $e_date = Carbon::parse($e_date); // Convert to a Carbon instance

                        $threeMonthsFromNow = Carbon::now()->addMonths(3);

                        if ($e_date->lessThan($threeMonthsFromNow)) {
                            // Expiry date is less than 3 months from now
                            return "send mail";
                        } else {
                            // Expiry date is more than or equal to 3 months from now
                            return "Dont send mail";
                        }

//////////////////////////////////////////////////////    Send Email to notify approval of new Product  //////////////////////////////////////////////////////////

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
            $quantity=$request->quantity;
            $batch_no=$request->batch_no;
            $e_date=$request->e_date;
            //Store in batch tables
            $batch=Batch::create(['batch_no'=>$batch_no,'product_id'=>$product_id,'quantity'=>$quantity,'expiry_date'=>$e_date]);
            if($batch){
                //Get product quantity from db and increment
                $qty=Product::where('id',$product_id)->pluck('quantity')->first();
                $q_type=Product::where('id',$product_id)->pluck('quantity_type')->first();
                $product=Product::where('id',$product_id)->update([
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
                    ]);
                if($stock){
                    //Log activity to file
                    Log::channel('add_stock')->notice('New stock added. Of Id: '.$product_id.'. Added by '.Auth::user()->first_name.' Email: '.Auth::user()->email);
//////////////////////////////////////////////////////    Send Email to check expiry of product and end alert //////////////////////////////////////////////////////////
                        $e_date = Carbon::parse($e_date); // Convert to a Carbon instance

                        $threeMonthsFromNow = Carbon::now()->addMonths(3);

                        if ($e_date->lessThan($threeMonthsFromNow)) {
                            // Expiry date is less than 3 months from now
                            return "send mail";
                        } else {
                            // Expiry date is more than or equal to 3 months from now
                            return "Dont send mail";
                        }
//////////////////////////////////////////////////////    Send Email to notify approval of new Product  //////////////////////////////////////////////////////////

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
        $type=$request->type;
        if($type==0){

        }elseif($type==1){// Edit stock details
        $e_date = $request->input('e_date');
        $rmks = $request->input('remarks');
        $batch_id=$request->batch_id;
        $batch_no=$request->batch_no;
        $stock_id=$request->batch_id;
        $product_id=$request->batch_id;
        $quantity=$request->quantity;
        $quantity_type=$request->q_type;
        $source=$request->source;
        $product_name=$request->name;

        //Extract Quantity in DB so as to correctly cummulate the all products quantity correctly
        $quantity_db=Batch::where('id',$batch_id)->pluck('quantity')->first();//initial batch quantity about to be changed 
        $init_product_qty=Product::where('id',$product_id)->pluck('quantity')->first();//Initial Total product quantity for all thos batches
        $quantity_change=$init_product_qty-$quantity_db+$quantity;//To be updated in product table

        if (is_null($e_date)) {
            // expiry_date is null just update batch number
            $batch=Batch::where('id',$batch_id)->update(['batch_no'=>$batch_no,'quantity'=>$quantity]);
        } else {
            // expiry_date has a value. update both
            $batch=Batch::where('id',$batch_id)->update(['batch_no'=>$batch_no,'quantity'=>$quantity,'expiry_date'=>$e_date]);
        }

        if($batch){
        //update Stock Table. first check if remarks is null
            if(is_null($rmks)){
                $stock=Stock::where('id',$stock_id)->update(['quantity'=>$quantity,'quantity_type'=>$quantity_type,'source'=>$source]);
                if($stock){
                    Product::where('id',$product_id)->update(['name'=>$product_name,'quantity'=>$quantity_change]);
                }
            }else{
                $stock=Stock::where('id',$stock_id)->update(['quantity'=>$quantity,'quantity_type'=>$quantity_type,'source'=>$source,'remarks'=>$rmks]);
                if($stock){
                    Product::where('id',$product_id)->update(['name'=>$product_name,'quantity'=>$quantity_change]);
                }
            }
        }else{
            return "501";//Batch Number should be unique
        }
            return "200";
        }
        elseif($type==2){// Edit order level of a product
            $o_level=$request->o_level;
            $product_id=$request->editorderId;
            $edit=Product::where("id",$product_id)->update(['order_level'=>$o_level]);
            if($edit){
                return "200";
            }else{return "500";}
        }
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