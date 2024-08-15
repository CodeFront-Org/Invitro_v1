<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Batch;
use App\Models\Card;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Apply filter if there  is any
        $product_filter=$request->product_filter;// means user is filtering a specific product
        $from=$request->from;
        $to=$request->to;
        if(!empty($product_filter)){//get data for that product
            $product_id=Product::where('name',$product_filter)->pluck('id')->first();
            $cards=Card::where('product_id',$product_id)->whereBetween('created_at', [$from, $to])->paginate(10);
            $data1=Card::where('product_id',$product_id)->whereBetween('created_at', [$from, $to])->orderBy('id', 'desc')->paginate(10);
            $data1=Card::where('product_id',$product_id)->whereBetween('created_at', [$from, $to])->orderBy('id', 'desc')->get();
        }elseif($from and $to){//Get data for all products within the range
            $cards=Card::whereBetween('created_at', [$from, $to])->paginate(10);
            $data1=Card::whereBetween('created_at', [$from, $to])->orderBy('id', 'desc')->paginate(10);
            $data2=Card::whereBetween('created_at', [$from, $to])->orderBy('id', 'desc')->get();
        }else{//get all data 
            $cards = Card::latest()->paginate(10);
            $data1=Card::orderBy('id', 'desc')->paginate(10);
            $data2=Card::orderBy('id', 'desc')->get();
        }


        $page_number=$request->page;

        if($page_number==1){
            $page_number=1;
        }elseif($page_number>1){
            $page_number=(($page_number-1)*10)+1;
        }else{
            $page_number=1;
        }

        $label="Stock Card";
        $data=array();//load data to be displayed in stoc cards
        $data3=[];// to be used for data in the excel sheet
        foreach($cards as $card){
            //Get The product name
            $product_name=Product::where('id',$card->product_id)->pluck('name')->first();
            array_push($data,[
                'item'=>$product_name,
                'size'=>$card->size,
                'at_hand'=>$card->at_hand,
                'out'=>$card->out,
                'in'=>$card->in,
                'balance'=>$card->balance,
                'user'=>$card->user,
                'remarks'=>$card->remarks,
                'date' => \Carbon\Carbon::parse($card->created_at)->format('jS F Y'),
                'id'=>$card->id,
            ]);
        }

        foreach($data2 as $card){
            //Get The product name
            $product_name=Product::where('id',$card->product_id)->pluck('name')->first();
            array_push($data3,[
                'item'=>$product_name,
                'size'=>$card->size,
                'at_hand'=>$card->at_hand,
                'out'=>$card->out,
                'in'=>$card->in,
                'balance'=>$card->balance,
                'user'=>$card->user,
                'remarks'=>$card->remarks,
                'date' => \Carbon\Carbon::parse($card->created_at)->format('jS F Y'),
                'id'=>$card->id,
            ]);
        }

        return view('app.stock_card',compact('label','data','data1','data3','page_number'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
            //Get product ID and Name of the user
            $product_id=Product::where('name',$request->name)->pluck('id')->first();
            $f_name=User::where('id',Auth::id())->pluck('first_name')->first();
            $l_name=User::where('id',Auth::id())->pluck('last_name')->first();
            $user=$f_name." ".$l_name;

            //Store the stock card
            $store=Card::create([
                'product_id'=>$product_id,
                'user'=>$user,
                'size'=>$request->size,
                'at_hand'=>$request->at_hand,
                'out'=>$request->out,
                'in'=>$request->in,
                'balance'=>$request->balance,
                'signature'=>$user,
                'remarks'=>$request->remarks,
            ]);

            if($store){
                session()->flash('message','Added successfully');
                return back();
            }else{
                session()->flash('error','An Error Occurred. Please try again later.');
                return back();
            }


    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Ensure the product entered first exists in the main table
        if(Product::where('name',$request->name)->exists()){
            //Get the product id
            $product_id=Product::where('name',$request->name)->pluck('id')->first();
            $update=Card::where('id',$id)->update([
                'product_id'=>$product_id,
                'size'=>$request->size,
                'at_hand'=>$request->at_hand,
                'out'=>$request->out,
                'in'=>$request->in,
                'balance'=>$request->balance,
                'remarks'=>$request->remarks,
            ]);
            if($update){
                session()->flash('message',"Data updated successfully.");
                return back();
            }else{    
                session()->flash('error','An error occurred. Please try again later');
                return back();
            }
        }else{    
            session()->flash('error','The Product does not exist');
        
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Card::find($id)->delete();
        return;
    }
}
