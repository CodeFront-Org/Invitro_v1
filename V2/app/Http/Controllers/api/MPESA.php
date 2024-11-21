<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MPESA extends Controller
{
        public function lipaNaMpesaPassword()
    {
        //timestamp
        $timestamp = Carbon::rawParse('now')->format('YmdHms');
        //passkey
        $passKey =env("Pass_Key");
        $businessShortCOde =env("Short_Code");
        //generate password
        $mpesaPassword = base64_encode($businessShortCOde.$passKey.$timestamp);

        return $mpesaPassword;

    }


    public function newAccessToken()
    {
        $consumer_key=env("Consumer_Key");
        $consumer_secret=env("Consumer_Secret");
        $credentials = base64_encode($consumer_key.":".$consumer_secret);
        $url = env("Token_URL");


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic ".$credentials,"Content-Type:application/json"));
        curl_setopt($curl, CURLOPT_HEADER,false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        $access_token=json_decode($curl_response);
        curl_close($curl);

        return $access_token->access_token; //Likely to get error for not accessing this property. Just create a new app in daraje and use cred key and secret

    }



/*************************************************************** START FOR STK PUSH *********************************************************************** */

/************************************ STK PUSH Function *********************************************************************** */

    public function stkPush(Request $request)
    {
            $amount =1;//$request->amount;
            $user_id=1;//$request->user_id;
            $room_id=1;//$request->room_id;
            $phoneNumber ='254797965680';// $request->contact;
            $phoneNumber=substr($phoneNumber,-9); //Filter contacts to MPESA format
            $phoneNumber='254'.$phoneNumber;

            $url = env("STK_PUSH_URL");
            $curl_post_data = [
                'BusinessShortCode' =>env("Short_Code"),
                'Password' => $this->lipaNaMpesaPassword(),
                'Timestamp' => Carbon::rawParse('now')->format('YmdHms'),
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $amount,
                'PartyA' => $phoneNumber,
                'PartyB' => env("Short_Code"),
                'PhoneNumber' => $phoneNumber,
                'CallBackURL' => env("Callback_URL"),
                'ResponseType'=>'Completed',
                'AccountReference' => "Swerve Tech",
                'TransactionDesc' => "lipa Na M-PESA"
            ];


            $data_string = json_encode($curl_post_data);


            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->newAccessToken()));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            $curl_response = curl_exec($curl);
            return $curl_response;

     }



/************************************   Mpesa Response to process Callback url from stk push ******************************************* */

     public function MpesaRes(Request $request)
     {
        $response = json_decode($request->getContent());
        $response=json_encode($response);

        // Decode the JSON data into an associative array
        $data = json_decode($response, true);

        // Extract the ResultDesc value from the array
        $resultCode = $data['Body']['stkCallback']['ResultCode'];

        // Perform some action based on the response
        if($resultCode == 0) {// Payment successful, do something
        //Log all activity
        Log::channel('mpesa_stk')->notice("Mpesa Success: ".$resultCode." ".print_r($data,true));

        $MerchantRequestID=$data['Body']['stkCallback']['MerchantRequestID'];
        $callbackMetadata = $data['Body']['stkCallback']['CallbackMetadata']['Item'];
        foreach ($callbackMetadata as $item){
            if($item['Name'] == 'Amount') {
                $TransAmount = $item['Value'];
            }
            if($item['Name'] == 'MpesaReceiptNumber') {
                $InvoiceNumber = $item['Value'];
            }
            if($item['Name'] == 'Balance') {
                $OrgAccountBalance = $item['Value'];
            }
            if($item['Name'] == 'TransactionDate') {
                $TransTime = $item['Value'];
            }
            if($item['Name'] == 'PhoneNumber') {
                $MSISDN = $item['Value'];
            }
        }

        /************************************  Adding to Payments table *********************************************************************** */
        $phone_number=$MSISDN;

        /************************************   Generate a Receipts *********************************************************************** */



        }  /////end of if

        else { // Payment failed, do something else
        //Store a value in Track to redirect a user or throw an error message
        //Log ativity
        Log::channel('mpesa_stk')->notice("Mpesa Failed: " .$resultCode." ".print_r($data,true));

        } //end of else

     }

/*************************************************************** END FOR STK PUSH *********************************************************************** */




/*************************************************************** START FOR C2B or OFFline PAYBILL *********************************************************************** */


/************************************   Mpesa Response to process Callback url from C2B response ******************************************* */
public function c2b(Request $request){

    // $response = json_decode($request->getContent());
    // $response=json_encode($response);

    // Decode the JSON data into an associative array
    // $data = json_decode($response, true);
    $data = json_decode($request->getContent(), true);

    // Data gotten from url
    $TransactionType=$data['TransactionType'];
    $TransID=$data['TransID'];
    $TransTime=$data['TransTime'];
    $TransAmount=$data['TransAmount'];
    $BusinessShortCode=$data['BusinessShortCode'];
    $BillRefNumber=$data['BillRefNumber'];
    $InvoiceNumber=$data['InvoiceNumber'];
    $OrgAccountBalance=$data['OrgAccountBalance'];
    $ThirdPartyTransID=$data['ThirdPartyTransID'];
    $MSISDN=$data['MSISDN'];
    $FirstName=$data['FirstName'];
    $MiddleName=$data['MiddleName'];
    $LastName=$data['LastName'];

    $data_f=array();
    array_push($data_f,[
    'TransID'=>$TransID,
    'Amount'=>$TransAmount,
    'Account_Number'=>$BillRefNumber,
    'Org_Balance'=>$OrgAccountBalance,
    'Contact'=>$MSISDN,
    'FirstName'=>$FirstName,
    'MiddleName'=>$MiddleName,
    'LastName'=>$LastName
    ]);

    Log::channel('mpesa_c2b')->notice("Hit C2B callback Now");//return "Success";
    Log::channel('mpesa_c2b')->notice("Hit C2B callback".print_r($data_f,true));//return "Success";
}

/*************************************************************** END FOR C2B or OFFline PAYBILL*********************************************************************** */




}
