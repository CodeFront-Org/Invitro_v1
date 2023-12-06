<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
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
}
