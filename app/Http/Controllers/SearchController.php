<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Stock;
use App\Models\Product;
use App\Models\User;
use App\Models\Batch;
use App\Models\Audit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function product(Request $request){//search for products
            $name=$request->name;
            //check if product with given name exists
            $check=Product::where('name',$name)->exists();
            if(!$check){//Product does not exist
                // Error message
                session()->flash('error', 'Product '.$name.' - does not exists in your stocks. Please Check for typo and try again...');
                return back();
            }
            $label='Product Details';
            $data=array();//load data in 1st table before viewing transactions
            $data2=array();//load data to be viewed for transactions
            $data1=Product::select('id','name','order_level')->where('name',$name)->get();//to be used to display some product during restocking

            foreach($data1 as $p){
                $product_name=$p->name;
                $p_id=$p->id;
                $product_id=$p_id;
                $order_level=$p->order_level;
                //pick from batches table
                $batch=Batch::all()->where('product_id',$p_id)->where('sold_out',0);
                $total_batch=count($batch);
                //$a_vail=Product::where('id',$p_id)->first()->quantity;
                $total_qty_approved=Stock::where('approve',1)->where('product_id',$p_id)->sum('quantity');//total qty approved
                $total_qty_not_approved=Stock::where('approve',0)->where('product_id',$p_id)->sum('quantity');//total qty approved
                $qty_available=Batch::where('product_id',$p_id)->where('sold_out',0)->where('approved',1)->sum('quantity');
				

				
				 //$sold_sql=Batch::where('product_id',$p_id)->sum('sold')->toSql();
				//$tot_sql=Batch::where('product_id',$p_id)->where('approved',1)->sum('quantity')->toSql();
				
                $sold=Batch::where('product_id',$p_id)->sum('sold');
                $tot=Batch::where('product_id',$p_id)->where('approved',1)->sum('quantity');
                //$a_available=DB::select("SELECT SUM(quantity)-SUM(sold) as 'available' FROM batches where product_id=$p_id;")->first()->pluck("available");
               
               // $a_available_array=DB::select("SELECT SUM(quantity)-SUM(sold) as 'available' FROM batches where product_id=$p_id and deleted_at IS NULL;");
                $a_available=DB::select("SELECT SUM(quantity)-SUM(sold) as 'available' FROM batches where product_id=$p_id and deleted_at IS NULL AND sold_out <> 1;")[0]->available;;
               // $a_available= $a_available_array[0]->available;
                //$a_available= $a_available_array[0]->available;
                //$a_available=  json_encode($a_available_array[0]['available']); //->available;

                //$a_available=  $a_available_array[0]['available']; //->available;
                //$a_available=0;
                $e_period=Product::where('id',$p_id)->pluck('expire_days')->first();

                if($order_level>$qty_available){
                    $alert=1;
                }else{
                    $alert=0;
                }

                //$total_qty=0;
                //Push data for table stock
                //'qty_available'=> $qty_available,
                //'qty_available'=>$tot-$sold,

				array_push($data,[
                    'id'=>$p_id,
                    'name'=>$product_name,
                    'ref_no'=>Product::find($p_id)->ref_no ?? 'Null',
                  'a_available'=>$a_available,
                  'qty_available'=>$a_available,
                   'qty_not_approved'=>$total_qty_not_approved,
                    'batch'=>$total_batch,
                    'order_level'=>$order_level,
                    'e_period'=>$e_period,
                    'alert'=>$alert
                ]);
			 /*
				  array_push($data,[
                    'id'=>$p_id,
                    'name'=>$product_name.'-'.$p_id,
                    'qty_available'=>$a_vail,
                    'qty_not_approved'=>$total_qty_not_approved,
                    'batch'=>$total_batch,
                    'order_level'=>$order_level,
                    'e_period'=>$e_period,
                    'alert'=>$alert
                ]);
					*/
				
				
				
				
                    //get data from stock table
                    $stocks=Stock::all()->where('product_id',$p_id)->sortByDesc('id');
                    foreach($stocks as $s){
                        $source=$s->source;
                        $date=$s->created_at;
                        //$date=Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('F jS Y');
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
                        $f=User::withTrashed()->where('id',$ff)->pluck('first_name')->first();
                        $l=User::withTrashed()->where('id',$ff)->pluck('last_name')->first();
                        $staff=$f." ".$l;
                        //push data for transaction
                        if($sold==0 or $sold==1){
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

            $audits=Audit::all()->sortByDesc('id');

            //return $data2;
            return view('app.product_details',compact('label','data','data1','data2','audits','product_id'));
        }


        function autocomplete(Request $request)
        {
            $term = $request->search;
           return Product::where('name', 'LIKE', '%' . $term . '%')->where('approve',1)->take(10)->get();
           
        }
}
