<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(){
        $expiry_data=array();//To hold data for expiry products
        $order_level_data=array();//To hold data for Re-order_level
        $data=array();//To be used to store data temporarily
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
            $batches=Batch::select('id','batch_no','quantity','expiry_date')->where('product_id',$product_id)->where('approved',1)->where('sold_out',0)->get();
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
                if($daysDifference<$expire_days){
                    //Store Batch details in data array
                    array_push($data,[
                        'batch_no'=>$batch_no,
                        'expires_in'=>$daysDifference,
                        'expiry_date'=>$expiry_date_format
                    ]);
                }else{
                    //continue;
                }
                ///////////////////////////////////////////// End To Expiry alert Algorithim Data to be stored later ///////////////////////////////////////////////
            }
            // Store all the data to be used for expiry alert
            array_push($expiry_data,[
                'product_name'=>$product_name,
                'data'=>$data
            ]);
            $data=[];//empty the data array so as to pick data for the next product

           ///////////////////////////////////////////// Algorithim to get re-order-level limits /////////////////////////////////////////////////////////////
           if($product_qty<$order_level){//Order Level has been exceeded
                array_push($order_level_data,[
                    'product_name'=>$product_name,
                    'balance'=>$product_qty,
                ]);
            } 
        }

        return $order_level_data;

    }
}
