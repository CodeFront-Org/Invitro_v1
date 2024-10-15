<?php

namespace App\Http\Controllers;

use App\Models\CardsAudit;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $label="Audits";

        $page_number=$request->page;

        if($page_number==1){
            $page_number=1;
        }elseif($page_number>1){
            $page_number=(($page_number-1)*10)+1;
        }else{
            $page_number=1;
        }

        $data1=CardsAudit::all();
        $data2=CardsAudit::orderBy('id', 'desc')->paginate(10);
        $data=[];
        foreach($data1 as $d){
            array_push($data,[
                'product_id'=>$d->product_id,
                'product'=>Product::where('id',$d->product_id)->pluck('name')->first(),
                'qty'=>$d->qty,
                'status'=>$d->status,
                'date'=>$d->audit_date,
                'staff'=>User::where('id',$d->user_id)->pluck('first_name'),
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
        //
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
