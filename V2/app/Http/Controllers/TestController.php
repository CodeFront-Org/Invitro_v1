<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function code(Request $request){
        $batches=Batch::all();
        $data=[];
        foreach($batches as $b){
            $e_date=$b->expiry_date;
            $e_date=strtotime($e_date);//converting the expiry date to seconds in unix time
            $now=time();//getting current time in seconds using unix php time
            $range=($e_date-$now)/86400; //Getting days due to expire or past expiry and divide by 86400 to convert to days
            $p_id=$b->product_id;
            return$expire_alert=Product::where('id',$p_id)->pluck('expire_days')->first();
            $check=$range-strtotime($expire_alert);
            $days_due_expiry=abs(round($check));
            //check if product has reached expiry alert level
            $c=($e_date-$now)/86400;
            if($c<$expire_alert){// is within the expiry period
                echo "Expiry Period for batch_no ".$b->batch_no."<br>";
            }else{//not within the expiry period
                echo "Not Expiry Period for batch_no ".$b->batch_no."<br>";
            }
            if($check>0){// Not expired
                echo "Not Expired: in $days_due_expiry batch ".$b->batch_no."<br>";
            }else{//Expired
                echo "Expired: in $days_due_expiry batch  ".$b->batch_no."<br>";
            }
            //return $range;
        }
       // return $batch;
    }
}
