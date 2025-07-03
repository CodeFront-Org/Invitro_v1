<?php

namespace App\Http\Controllers;

use App\Mail\DueExpiry;
use App\Mail\Expired;
use App\Mail\Reorder;
use App\Models\Batch;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Audit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use Carbon\Carbon;
use App\Mail\NewProductAlertMail;

class CronController extends Controller
{
        public function cron(Request $request){
                $expiry_data=array();//To hold data for expiry products
                $order_level_data=array();//To hold data for Re-order_level
                $expired=array();
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
                    $batches=Batch::select('id','batch_no','product_id','quantity','expiry_date')->where('product_id',$product_id)->where('approved',1)->where('sold_out',0)->get();
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
                /*  */
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
                //return $data;
                // Create the table for the email with inline styles for borders and text alignment
                $expired_table = "<h3 style='color:red'>Expired</h3>";
                $expired_table .= '<table style="border-collapse: collapse; width: 60%;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #000; padding: 5px; text-align: center;">#</th>
                            <th style="border: 1px solid #000; padding: 5px; text-align: center;">Product</th>
                            <th style="border: 1px solid #000; padding: 5px; text-align: center;">Batch Number</th>
                            <th style="border: 1px solid #000; padding: 5px; text-align: center;">Expired On</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach ($expired as $key => $item) {
                            $table .= '<tr>
                            <td style="border: 1px solid #000; padding: 5px; text-align: center;">'.($key+1).'</td>
                            <td style="border: 1px solid #000; padding: 5px; text-align: center;">' . $item['product_name'] . '</td>
                            <td style="border: 1px solid #000; padding: 5px; text-align: center;">'.$item['batch_no'].'</td>
                            <td style="border: 1px solid #000; padding: 5px; text-align: center;">'.$item['expiry_date'].'</td>

                        </tr>';
                        
                        //$track=$item['product_name'];
                    }
            
                    // Close the table
                $expired_table .= '</tbody></table>';

                // $users=User::all()->where('role_type',1);
                // $link=env('APP_URL');
                // $product_name=Product::where('id',$product_id)->pluck('name')->first();
                // foreach($users as $user){
                //     $user=User::find($user->id);
                //     $name=$user->first_name;
                //     $recipient=$user->email;
                //     Mail::to($recipient)->send(new Expired($link, $recipient,$name,$table));
        
                // }
             
                 
                $table = '<table style="border-collapse: collapse; width: 60%;">
                <thead>
                    <tr>
                        <th colspan="2" style="border: 1px solid #000; padding: 5px; text-align: center;font-weight:bolder;font-size:20px">Product Batches With Their Expiry - Table</th>
                    </tr>
                </thead>
                <tbody>';
                // Loop through the order_level_data and add each product and balance as a row in the table
                $track="";
                foreach ($data as $key => $item) {
                    if($track!=$item['product_name'] and $item['check']==0){
                        $table .= '<tr colspan="3">
                        <td colspan="3" style="border: 1px solid #000; padding: 5px; text-align: center;font-weight:bolder;font-size:20px">' . $item['product_name'] . '</td>
                    </tr>';
                    }
                    if($item['check']==0){
                        $table .= '<tr>
                            <td style="border: 1px solid #000; padding: 5px; text-align: center;">Batch No:  ' . $item['batch_no'] . '</td>
                            <td style="border: 1px solid #000; padding: 5px; text-align: center;">Expires in:  ' . $item['expires_in'] . ' days</td>
                        </tr>';
                    }
                    $track=$item['product_name'];
                }

                // Close the table
                 $table .= '</tbody></table>'.$expired_table;

               // $users=User::all()->where('role_type',1)->where('role_type',6);
                $users=DB::select("SELECT * FROM `users` where alert=6 and deleted_at is Null;");
                $link=env('APP_URL');
               
                $product_name=Product::where('id',$product_id)->pluck('name')->first();
                foreach($users as $user){
                    $user=User::find($user->id);
                    $name=$user->first_name;
                    $recipient=$user->email;
                    Mail::to($recipient)->send(new DueExpiry($link, $recipient,$name,$table));

                }
               
                return $table;
        
                /////////////////////////////////// End to Send Email for Expiry alert ///////////////////////////////////////////////////////////////////////




                /////////////////////////////////// Send SMS for re-order level //////////////////////////////////////////////////////////////////////////////

                // Create the initial message text
                $message='The Expiry Alert for the below product(s) have been Reached.\n';
                $message .= '#  Product     Batch_No:       Days';

                // Loop through the data and append each product and balance
                foreach ($data as $key => $item) {
                    $message .= '\n'.($key + 1)  . '.    '. $item['product_name'] . '    '. $item['batch_no'] . '      ' . $item['expires_in'];
                }
                $users=User::all()->where('role_type',1);
                foreach($users as $user){
                    $user=User::find($user->id);
                    $mobile=$user->contacts;
                    $this->send_sms($message,$mobile);
                }
                //----------------------------------------------- End to Send SMS for re-order level --------------------------------------------------------/////////////////////////////////




                
                /////////////////////////////////// Send SMS for re-order level //////////////////////////////////////////////////////////////////////////////
            
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
                    Mail::to($recipient)->send(new Reorder($link, $recipient,$name,$table));

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
