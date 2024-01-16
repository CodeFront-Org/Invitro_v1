<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function code(Request $request){

        $mobile="254797965680";
        $msg="Hey test";
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
        echo $response;
    }
}
