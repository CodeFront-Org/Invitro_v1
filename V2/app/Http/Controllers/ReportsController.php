<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Batch;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{

//////////////////////////////////////////////////////////////// Products With Batches //////////////////////////////////////////////////
    //Generate products with or without batches
    public function productsWithBatch(){
        $label="Products Batches Report";

        $data=array();

        $productsWithNoBatch=Product::select('id','name', 'quantity', 'expire_days', 'order_level')->where('quantity','=',0)->where('approve',1)->get();

        $productsWithBatch = Product::select('id','name', 'quantity', 'expire_days', 'order_level')
                    ->where('approve', 1)
                    ->where('quantity', '>', 0)
                    ->get();
        $totalWithBatch=count($productsWithBatch);
        $totalWithNoBatch=count($productsWithNoBatch);

        foreach($productsWithBatch as $p){
            $pid=$p->id;
            $batches=count(Batch::all()->where('product_id',$pid));
            $qty=$p->quantity;
            $expire=$p->expire_days;
            $order_level=$p->order_level;
            $name=$p->name;

            array_push($data,[
                "name"=>$name,
                "batches"=>$batches,
                "qty"=>$qty,
                "expire"=>$expire,
                "order_level"=>$order_level
            ]);
        }

        return view('reports.products-with-batch',compact(
            'label',
            'totalWithBatch',
            'totalWithNoBatch',
            'data',
            'productsWithNoBatch'
        ));
    }


//////////////////////////////////////////////////////////////// Products Without Batches //////////////////////////////////////////////////
    //Generate products without batches
    public function productsWithoutBatch(){
        $label="Products Batches Report";

        $productsWithNoBatch=Product::select('id','name', 'quantity', 'expire_days', 'order_level')->where('quantity','=',0)->where('approve',1)->get();
        $totalWithNoBatch=count($productsWithNoBatch);

        return view('reports.products-without-batch',compact(
            'label',
            'totalWithNoBatch',
            'productsWithNoBatch'
        ));
    }


//////////////////////////////////////////////////////////////// Products Audited ///////////////////////////////////////////////////////////
    public function productsAudited(Request $request){
        $label="Products Audited";

        $from=$request->from;
        $to=$request->to;

        if($from and $to){
            $data = Audit::select('id', 'user_id', 'product_id', 'status', 'comments', 'created_at')
            ->whereBetween('created_at', [$from, $to])
            ->get();
        }else{
            $data=Audit::select('id','user_id', 'product_id', 'status', 'comments','created_at')->get();
        }
    
        $productsAudited=[];
        
        foreach($data as $p){
            $f_name=User::withTrashed()->where('id',$p->user_id)->pluck('first_name')->first();
            $l_name=User::withTrashed()->where('id',$p->user_id)->pluck('last_name')->first();
            $staff=$f_name.' '.$l_name;
            $product=Product::where('id',$p->product_id)->pluck('name')->first();
            //Converting date to human reaable
            $date=$p->created_at;
            $date = $date->format('j, F, Y \a\t g:i A');
            array_push($productsAudited,[
                'id'=>$p->id,
                'staff'=>$staff,
                'product'=>$product,
                'status'=>$p->status,
                'comments'=>$p->comments,
                'date'=>$date,
            ]);
        }
        $totalAudited=count($productsAudited);

        return view('reports.products-audited',compact(
            'label',
            'totalAudited',
            'productsAudited',
            'from',
            'to'
        ));
    }


//////////////////////////////////////////////////////////////// Not Audited Products //////////////////////////////////////////////////
    public function productsNotAudited(Request $request){
        $label="Products Not Audited";

        $from=$request->from;
        $to=$request->to;

        if($from and $to){
            $data = Audit::select('id', 'user_id', 'product_id', 'status', 'comments', 'created_at')
            ->whereBetween('created_at', [$from, $to])
            ->get();
        }else{
            $data=Audit::select('id','user_id', 'product_id', 'status', 'comments','created_at')->get();
        }
    
        $productsNotAudited=[];

        //push products id that are audited already so that i can filter those that were not
        $id_data=[];
        foreach($data as $d){
            array_push($id_data,['id'=>$d->product_id]);
        }

        $productsNotAudited1=Product::whereNotIn('id', $id_data)->orderBy('name', 'asc')->get();

        //get last date audited and store it in the an array
        foreach($productsNotAudited1 as $p){
            $date=Audit::where('product_id',$p->id)->pluck('created_at')->first();
            if($date){
                $date = $date->format('j, F, Y \a\t g:i A');
            }else{
                $date='Not Audited Before';
            }
            $product=Product::where('id',$p->id)->pluck('name')->first();
            array_push($productsNotAudited,[
                'product'=>$product,
                'audit'=>$date
            ]);
        }
        $totalNotAudited=count($productsNotAudited);

        return view('reports.products-not-audited',compact(
            'label',
            'totalNotAudited',
            'productsNotAudited',
            'from',
            'to'
        ));
    }

    
//////////////////////////////////////////////////////////////// Products Expired ///////////////////////////////////////////////////////////
    public function productsExpired(Request $request){
        $type=$request->type;
        $label="Expired Products";

        $from=$request->from;
        $to=$request->to;

        $expiry_data=array();//To hold data for expiry products
        $order_level_data=array();//To hold data for Re-order_level
        $expired=array();
        $due_expiry=[];
        $data=[];
        //Get expired batches
        $products=Product::select('id','name','quantity','expire_days','order_level')->where('approve',1)->get();
        foreach($products as $p){
            //Get the fields for products table
            $product_name=$p->name;
            $product_id=$p->id;
            $product_qty=$p->quantity;
            $expire_days=$p->expire_days;
            $order_level=$p->order_level;
            //Get all batches associated to that product using id

            if($from and $to){
                $batches=Batch::select('id','batch_no','product_id','quantity','expiry_date')->where('product_id',$product_id)
                ->where('approved',1)->where('sold_out',0)->whereBetween('expiry_date', [$from, $to])->get();
            }else{
                $batches=Batch::select('id','batch_no','product_id','quantity','expiry_date')->where('product_id',$product_id)->where('approved',1)->where('sold_out',0)->get();
            }
        
            foreach($batches as $b){
                //Get batch fields
                $batch_id=$b->id;
                $batch_no=$b->batch_no;
                $batch_qty=$b->quantity;
                $expiry_date=$b->expiry_date;
                $expiry_date_format=Carbon::createFromFormat('Y-m-d H:i:s', $expiry_date)->format('j F Y');
                /////////////////////////////////////// Algorithim to Calculating the expiry date ////////////////////////////////////////////
                $date_to_expire=Carbon::parse($expiry_date);//Convert to carbon instance
                $today = Carbon::now();// Get today's date
                $daysDifference = $today->diffInDays($date_to_expire); // Calculate the difference in days



                
                ////////////////Injected the new algorithim

                $e_date=$b->expiry_date;
                $e_date=strtotime($e_date);//converting the expiry date to seconds in unix time
                $now=time();//getting current time in seconds using unix php time
                $range=($e_date-$now)/86400; //Getting days due to expire or past expiry and divide by 86400 to convert to days
                $p_id=$b->product_id;
                $expire_alert=Product::where('id',$p_id)->pluck('expire_days')->first();
                $check=$range-strtotime($expire_alert);
                $days_due_expiry=abs(round($check));
                //check if product has reached expiry alert level
                $c=($e_date-$now)/86400;
                if($c<$expire_alert){// is within the expiry period
                    if($c>0){
                        //Store Batch details in data array
                        array_push($data,[
                            'product_name'=>$product_name,
                            'batch_no'=>$batch_no,
                            'expires_in'=>$daysDifference,
                            'expiry_date'=>$expiry_date_format,
                            'check'=>0, //meaning not yet expired
                        ]);
                    }
                   // echo "Expiry Period for batch_no ".$b->batch_no."<br>";
                }else{//not within the expiry period
                   // echo "Not Expiry Period for batch_no ".$b->batch_no."<br>";
                }
                if($check>0){// Not expired
                    //echo "Not Expired: in $days_due_expiry batch ".$b->batch_no."<br>";
                }else{//Expired
                    //Store Batch details in data array
                    array_push($data,[
                        'product_name'=>$product_name,
                        'batch_no'=>$batch_no,
                        'expires_in'=>$daysDifference,
                        'expiry_date'=>$expiry_date_format,
                        'check'=>1 //meaning it expired
                    ]);
                   // echo "Expired: in $days_due_expiry batch  ".$b->batch_no."<br>";
                }

                ////////////////////////////////////

                ///////////////////////////////////////////// End To Expiry alert Algorithim Data to be stored later ///////////////////////////////////////////////
            } 
            
            // Store all the data to be used for expiry alert
            //first check if the data submitted is empty so as to skip to the other iteration
            if(!empty($data)){
                array_push($expiry_data,[
                    'product_name'=>$product_name,
                    'data'=>$data
                ]);
            }
         $expiry_data=$data;
           // $data=[];
           ///////////////////////////////////////////// Algorithim to get re-order-level limits /////////////////////////////////////////////////////////////
           if($product_qty<$order_level){//Order Level has been exceeded
                array_push($order_level_data,[
                    'product_name'=>$product_name,
                    'balance'=>$product_qty,
                ]);
            } 
            ///////////////////////////////////////////// End to the Algorithim to get re-order-level limits /////////////////////////////////////////////////////////////
        }        

        /////////////////////////////////// Send Email for Expiry alert  ///////////////////////////////////////////////////////////////////////
        

        //store expired products
        foreach($data as $d){
            $x=$d['check'];
            if($x==1){
                array_push($expired,[
                    'product_name'=>$d['product_name'],
                    'batch_no'=>$d['batch_no'],
                    'expires_in'=>$d['expires_in'],
                    'expiry_date'=>$d['expiry_date']
                ]);
            }
        }

        //store Due expiry products
        foreach($data as $d){
            $x=$d['check'];
            if($x==0){
                array_push($due_expiry,[
                    'product_name'=>$d['product_name'],
                    'batch_no'=>$d['batch_no'],
                    'expires_in'=>$d['expires_in'],
                    'expiry_date'=>$d['expiry_date']
                ]);
            }
        }

        if($type==0){
            $totalExpired=count($expired);
            return view('reports.expired',compact('label','expired','totalExpired','from','to'));
        }elseif($type==1){
            return $due_expiry;
        }else{
            return back();
        }
        

    }


}
