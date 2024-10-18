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
        //Apply filter if there 
        $product_filter=$request->product_filter;// means user is filtering a specific product
        $from=$request->from;
        $to=$request->to;
        $check=[];//check is_atHand
        $to = Carbon::parse($to)->addDay()->toDateString();
        if(!empty($product_filter)){//get data for that product
            $product_id=Product::where('name',$product_filter)->pluck('id')->first();
            $cards=Card::where('product_id',$product_id)->whereBetween('created_at', [$from, $to])->paginate(10);
            $data1=Card::where('product_id',$product_id)->whereBetween('created_at', [$from, $to])->orderBy('id', 'desc')->paginate(10);
            $data2=Card::where('product_id',$product_id)->whereBetween('created_at', [$from, $to])->orderBy('id', 'desc')->get();
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

        $product_prices=[];
        foreach($cards as $card){
            //Get The product name
            $product_name=Product::where('id',$card->product_id)->pluck('name')->first();
            array_push($data,[
                'item'=>$product_name,
                'product_id'=>$card->product_id,
                'size'=>$card->size,
                'at_hand'=>$card->at_hand,
                'out'=>$card->out,
                'in'=>$card->in,
                'balance'=>$card->balance,
                'user'=>$card->user,
                'remarks'=>$card->remarks,
                'date' => $card->created_at,
                'id'=>$card->id,
            ]);
            //Check if atHand
            $isCheck=$card->is_at_hand;
            if($isCheck==1){
                array_push($check,[
                    'product_id'=>$card->product_id,
                    'status'=>true,
                ]);
            }

            //To control product qty display when entering new field
            $c=Card::where('product_id',$card->product_id)->where('is_at_hand',1)->pluck('at_hand')->first();
            $isAtHand=Card::where('product_id',$card->product_id)->pluck('is_at_hand')->first();
            $s=Card::where('product_id',$card->product_id)->sum('in');
            $o=Card::where('product_id',$card->product_id)->sum('out');
            $sum=$c+$s-$o;
            array_push($product_prices,[
                'id'=>$card->product_id,
                'name'=>Product::where('id',$card->product_id)->pluck('name')->first(),
                'quantity'=>Product::where('id',$card->product_id)->pluck('quantity')->first(),
                'at_hand'=>$sum,
                'is_at_hand'=>$isAtHand
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
                'date' =>$card->created_at,
                'id'=>$card->id,
            ]);
        }

        // //$product_prices=Product::select('id','name','quantity')->get();
        // $ps=Product::select('id','name','quantity')->get();
        // $product_prices=[];
        // foreach($ps as $p){
        //     $c=Card::where('product_id',$p->id)->where('is_at_hand',1)->pluck('at_hand')->first();
        //     $isAtHand=Card::where('product_id',$p->id)->pluck('is_at_hand')->first();
        //     $s=Card::where('product_id',$p->id)->sum('in');
        //     $o=Card::where('product_id',$p->id)->sum('out');
        //     $sum=$c+$s-$o;
        //     array_push($product_prices,[
        //         'id'=>$p->id,
        //         'name'=>$p->name,
        //         'quantity'=>$p->quantity,
        //         'at_hand'=>$sum,
        //         'is_at_hand'=>$isAtHand
        //     ]);
        // }

        return view('app.stock_card',compact('label','data','data1','data3','page_number','product_prices','check'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        try {
            //Get product ID and Name of the user
            $product_id=Product::where('name',$request->name)->pluck('id')->first();
            $f_name=User::where('id',Auth::id())->pluck('first_name')->first();
            $l_name=User::where('id',Auth::id())->pluck('last_name')->first();
            $user=$f_name." ".$l_name;

            //Store the stock card
            $store=Card::create([
                'product_id'=>$product_id,
                'user'=>$user,
                //'size'=>$request->size,
                'at_hand'=>$request->at_hand,
                'out'=>$request->out,
                'in'=>$request->in,
                'balance'=>$request->balance,
                'signature'=>$user,
                'remarks'=>$request->remarks,
            ]);

            if($store){
                //Check if IsAtHand is inserted
                $check=Card::where('product_id',$product_id)->where('is_at_hand',1)->exists();
                if(!$check){
                    Card::where('id',$store->id)->update(['is_at_hand'=>1]);   
                }
                return "ok";
            }else{
                session()->flash('error','An Error Occurred. Please try again later.');
                return back();
            }
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            return back();
            
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
