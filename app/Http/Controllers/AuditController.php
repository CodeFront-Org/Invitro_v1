<?php

namespace App\Http\Controllers;

use App\Models\CardsAudit;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $label="Audits::Tab";

        //Apply filter if there 
        $product_filter=$request->product_filter;// means user is filtering a specific product
        $from=$request->from;
        $to=$request->to;
        $check=[];//check is_atHand
        $to = Carbon::parse($to)->addDay()->toDateString();
        if(!empty($product_filter)){//get data for that product
            $product_id=Product::where('name',$product_filter)->pluck('id')->first();
            $data1=CardsAudit::all()->where('product_id',$product_id);
            $data2=CardsAudit::where('product_id',$product_id)->orderBy('id', 'asc')->paginate(10);
        }elseif($from and $to){//Get data for all products within the range
            $product_id=Product::where('name',$product_filter)->pluck('id')->first();
            $data1=CardsAudit::whereBetween('created_at', [$from, $to])->get();
            $data2=CardsAudit::whereBetween('created_at', [$from, $to])->paginate(10);
        }else{//get all data 
            $data1=CardsAudit::all();
            $data2=CardsAudit::orderBy('id', 'asc')->paginate(10);
        }

        $page_number=$request->page;

        if($page_number==1){
            $page_number=1;
        }elseif($page_number>1){
            $page_number=(($page_number-1)*10)+1;
        }else{
            $page_number=1;
        }

        $data=[];
        foreach($data1 as $d){
            $date=Carbon::parse($d->audit_date);
            $date=$date->format('j F Y');
            array_push($data,[
                'product_id'=>$d->product_id,
                'product'=>Product::where('id',$d->product_id)->pluck('name')->first(),
                'qty'=>$d->qty,
                'status'=>$d->status,
                'date'=>$date,
                'staff'=>User::where('id',$d->user_id)->pluck('first_name')->first()." ".User::where('id',$d->user_id)->pluck('last_name')->first(),
                'rmks'=>$d->comments
            ]);
        }

        return view('audits.audits',compact('label','data','data2','page_number'));
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
            CardsAudit::create([
                'user_id'=>Auth::id(),
                'product_id'=>Product::where('name',$request->product_name)->pluck('id')->first(),
                'qty'=>$request->qty,
                'status'=>$request->status,
                'audit_date'=>$request->date,
                'comments'=>$request->rmks
            ]);

            return "ok";
        } catch (\Throwable $th) {
            throw $th;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
