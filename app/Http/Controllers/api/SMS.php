<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SMS extends Controller
{
    public function send_sms(Request $request){
    $mobile=$request->mobile;
    $msg='Message from Laravel Api.\n\nRegards\nSwerve Tech';

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
    //Extracting data that can be used in db storage or algorithms
    $responseData = json_decode($response, true);

    $status_code = $responseData[0]['status_code'];
    $status_desc = $responseData[0]['status_desc'];
    $message_id = $responseData[0]['message_id'];
    $mobile_number = $responseData[0]['mobile_number'];
    $network_id = $responseData[0]['network_id'];
    $message_cost = $responseData[0]['message_cost'];
    $credit_balance = $responseData[0]['credit_balance'];
    //Storing data in a data array
    $data=array();
    array_push($data,["status"=>$status_code,"mobile"=>$mobile_number,"network_id"=>$network_id,"cost"=>$message_cost,"balance"=>$credit_balance]);

    //Log sms Information
    Log::channel('sms')->notice("SMS Sent: ===".print_r($data,true));
    return $data;
    }

    public function bulk_sms(){ //Just use send_sms and then loop with forloop
        return "Bulk SMS";
    }
}