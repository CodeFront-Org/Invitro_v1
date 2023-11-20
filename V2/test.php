<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use App\Models\User;
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
                    continue;
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
            ///////////////////////////////////////////// End to the Algorithim to get re-order-level limits /////////////////////////////////////////////////////////////
        }        
        return $expiry_data;

        
        /////////////////////////////////// Send SMS for re-order level ///////////////////////////////////////////////////////////////////////
       
        // Create the initial message text
        $message='The Re-Order Level for the below product(s) have been exceeded.\n';
        $message .= '#    Products        Balance';

        // Loop through the data and append each product and balance
        foreach ($order_level_data as $key => $item) {
            $message .= '\n'.($key + 1)  . '.    '. $item['product_name'] . '        ' . $item['balance'];
        }
        $users=User::all()->where('role_type',1);
        foreach($users as $user){
            $user=User::find($user->id);
            $mobile=$user->contacts;
            //$this->send_sms($message,$mobile);
        }

        /////////////////////////////////// End to Send SMS for re-order level /////////////////////////////////////////////////////////////////////



        /////////////////////////////////// Send Email for re-order level ///////////////////////////////////////////////////////////////////////
        
        // Create the table for the email with inline styles for borders and text alignment
        $table = '<table style="border-collapse: collapse; width: 60%;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000; padding: 5px; text-align: center;">#</th>
                    <th style="border: 1px solid #000; padding: 5px; text-align: center;">Products</th>
                    <th style="border: 1px solid #000; padding: 5px; text-align: center;">Balance</th>
                </tr>
            </thead>
            <tbody>';

        // Loop through the order_level_data and add each product and balance as a row in the table
        foreach ($order_level_data as $key => $item) {
            $table .= '<tr>
                <td style="border: 1px solid #000; padding: 5px; text-align: center;">' . ($key + 1) . '.</td>
                <td style="border: 1px solid #000; padding: 5px; text-align: center;">' . $item['product_name'] . '</td>
                <td style="border: 1px solid #000; padding: 5px; text-align: center;">' . $item['balance'] . '</td>
            </tr>';
        }

        // Close the table
        $table .= '</tbody></table>';

        $users=User::all()->where('role_type',1);
        $link=env('APP_URL');
        $product_name=Product::where('id',$product_id)->pluck('name')->first();
        foreach($users as $user){
            $user=User::find($user->id);
            $name=$user->first_name;
            $recipient=$user->email;
            //Mail::to($recipient)->send(new OrderLevelNotification($link, $recipient,$name,$product_name));

        }

        /////////////////////////////////// End to Send Email for re-order level ///////////////////////////////////////////////////////////////////////
        


    }


    public function send_sms($message,$mobile){
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
            "message": "'.$message.'"
        }',
        CURLOPT_HTTPHEADER => array(
            'h_api_key:'.env('SMS_KEY'),
            'Content-Type: application/json'
        ),
        ));

      return $response = curl_exec($curl);
    }

}
