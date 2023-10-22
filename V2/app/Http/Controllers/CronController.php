<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
use App\Models\User;
use App\Models\Batch;
use App\Models\Audit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use App\Mail\NewProductAlertMail;

class CronController extends Controller
{
    public function cron(Request $request){
//Get all products
$products=Product::select('id','name','expire_days','quantity')->where('approve',1)->get();
foreach($products as $p){//loop through all products in the db
$p_id=$p->id;

}
    }
}
