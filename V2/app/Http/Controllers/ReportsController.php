<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Batch;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    //Generate products with or without batches
    public function productsWithBatch(){
        $label="Products Batches Report";

        $data=array();

        $productsWithNoBatch=Product::select('id','name', 'quantity', 'expire_days', 'order_level')->where('quantity','=',0)->where('approve',1)->get();

        $productsWithBatch = Product::select('id','name', 'quantity', 'expire_days', 'order_level')
                    ->where('approve', 1)
                    ->where('quantity', '>', 0)
                    ->get();
        $totalWithBatch=count($productsWithBatch);
        $totalWithNoBatch=count($productsWithNoBatch);

        foreach($productsWithBatch as $p){
            $pid=$p->id;
            $batches=count(Batch::all()->where('product_id',$pid));
            $qty=$p->quantity;
            $expire=$p->expire_days;
            $order_level=$p->order_level;
            $name=$p->name;

            array_push($data,[
                "name"=>$name,
                "batches"=>$batches,
                "qty"=>$qty,
                "expire"=>$expire,
                "order_level"=>$order_level
            ]);
        }

        return view('reports.products-with-batch',compact(
            'label',
            'totalWithBatch',
            'totalWithNoBatch',
            'data',
            'productsWithNoBatch'
        ));
    }

    //Generate products without batches
    public function productsWithoutBatch(){
        $label="Products Batches Report";

        $productsWithNoBatch=Product::select('id','name', 'quantity', 'expire_days', 'order_level')->where('quantity','=',0)->where('approve',1)->get();
        $totalWithNoBatch=count($productsWithNoBatch);

        return view('reports.products-without-batch',compact(
            'label',
            'totalWithNoBatch',
            'productsWithNoBatch'
        ));
    }

    public function productsAudited(Request $request){
        $label="Products Audited";

        $from=$request->from;
        $to=$request->to;

        if($from and $to){
            $data = Audit::select('id', 'user_id', 'product_id', 'status', 'comments', 'created_at')
            ->whereBetween('created_at', [$from, $to])
            ->get();
        }else{
            $data=Audit::select('id','user_id', 'product_id', 'status', 'comments','created_at')->get();
        }
    
        $productsAudited=[];
        
        foreach($data as $p){
            $f_name=User::withTrashed()->where('id',$p->user_id)->pluck('first_name')->first();
            $l_name=User::withTrashed()->where('id',$p->user_id)->pluck('last_name')->first();
            $staff=$f_name.' '.$l_name;
            $product=Product::where('id',$p->product_id)->pluck('name')->first();
            //Converting date to human reaable
            $date=$p->created_at;
            $date = $date->format('j, F, Y \a\t g:i A');
            array_push($productsAudited,[
                'id'=>$p->id,
                'staff'=>$staff,
                'product'=>$product,
                'status'=>$p->status,
                'comments'=>$p->comments,
                'date'=>$date,
            ]);
        }
        $totalAudited=count($productsAudited);

        return view('reports.products-audited',compact(
            'label',
            'totalAudited',
            'productsAudited',
            'from',
            'to'
        ));
    }

    public function productsNotAudited(Request $request){
        $label="Products Not Audited";

        $from=$request->from;
        $to=$request->to;

        if($from and $to){
            $data = Audit::select('id', 'user_id', 'product_id', 'status', 'comments', 'created_at')
            ->whereBetween('created_at', [$from, $to])
            ->get();
        }else{
            $data=Audit::select('id','user_id', 'product_id', 'status', 'comments','created_at')->get();
        }
    
        $productsNotAudited=[];

        //push products id that are audited already so that i can filter those that were not
        $id_data=[];
        foreach($data as $d){
            array_push($id_data,['id'=>$d->product_id]);
        }

        $productsNotAudited1=Product::whereNotIn('id', $id_data)->orderBy('name', 'asc')->get();

        //get last date audited and store it in the an array
        foreach($productsNotAudited1 as $p){
            $date=Audit::where('product_id',$p->id)->pluck('created_at')->first();
            if($date){
                $date = $date->format('j, F, Y \a\t g:i A');
            }else{
                $date='Not Audited Before';
            }
            $product=Product::where('id',$p->id)->pluck('name')->first();
            array_push($productsNotAudited,[
                'product'=>$product,
                'audit'=>$date
            ]);
        }
        $totalNotAudited=count($productsNotAudited);

        return view('reports.products-not-audited',compact(
            'label',
            'totalNotAudited',
            'productsNotAudited',
            'from',
            'to'
        ));
    }
}
