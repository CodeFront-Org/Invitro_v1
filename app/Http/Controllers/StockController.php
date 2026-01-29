<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
use App\Models\User;
use App\Models\Batch;
use App\Models\Restock;
use App\Models\Audit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use App\Mail\NewProductAlertMail;
use App\Mail\OrderLevelAlertMail;
use App\Mail\ExpiryAlertMail;
use App\Mail\ProductCreationMail;
use Illuminate\Support\Facades\Mail;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function index(Request $request)
    {
        // --- Pagination setup ---
        $page_number = max(1, (int) $request->get('page', 1));

        // --- Filters ---
        $name = $request->get('name');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        $label = "Stocks";

        // --- Build Product query with relationships ---
        $query = Product::with([
            'batches' => function ($q) {
                $q->where('sold_out', 0)->where('approved', 1);
            },
            'stocks' => function ($q) use ($from_date, $to_date) {
                // Apply date filters to Stock transactions
                if ($from_date && $to_date) {
                    $q->whereBetween('created_at', [$from_date, $to_date]);
                } elseif ($from_date) {
                    $q->whereDate('created_at', '>=', $from_date);
                } elseif ($to_date) {
                    $q->whereDate('created_at', '<=', $to_date);
                }
                $q->latest()->take(10);
            },
            'stocks.batch',
            'stocks.user' => function ($q) {
                $q->withTrashed();
            }
        ])
            ->where('approve', 1);

        // --- Apply product name filter ---
        if ($name) {
            $query->where('name', 'LIKE', "%{$name}%");
        }

        // --- Fetch paginated products (NOW 50 per page) ---
        $products = $query->orderBy('name', 'asc')
            ->paginate(50) // ← changed from 5 to 50
            ->appends($request->query());

        // --- Prepare arrays for stock summary and transactions ---
        $data = [];
        $data2 = [];

        foreach ($products as $product) {
            $p_id = $product->id;
            $product_name = $product->name;
            $order_level = $product->order_level;

            // --- Batch stats ---
            $batches = $product->batches;
            $total_batch = $batches->count();
            $tot_qty = $batches->sum('quantity');
            $sold_qty = $batches->sum('sold');
            $qty_available = $tot_qty - $sold_qty;

            // --- Stock stats ---
            $total_qty_not_approved = Stock::where('approve', 0)
                ->where('product_id', $p_id)
                ->sum('quantity');

            $e_period = $product->expire_days;
            $alert = $order_level > $qty_available ? 1 : 0;

            // --- Push summary data ---
            $data[] = [
                'id' => $p_id,
                'name' => $product_name,
                'qty_available' => $qty_available,
                'qty_not_approved' => $total_qty_not_approved,
                'batch' => $total_batch,
                'order_level' => $order_level,
                'e_period' => $e_period,
                'alert' => $alert
            ];

            // --- Push stock transaction data ---
            foreach ($product->stocks as $s) {
                $batch = $s->batch;
                $user = $s->user;

                if (!$batch)
                    continue;

                $expiry = optional($batch->expiry_date)
                    ? Carbon::parse($batch->expiry_date)->format('F jS Y')
                    : null;

                $staff = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));

                $data2[] = [
                    'id' => $s->id,
                    'batch_id' => $batch->id,
                    'product_id' => $p_id,
                    'name' => $product_name,
                    'quantity' => $batch->quantity,
                    'quantity_type' => $s->quantity_type,
                    'batch_no' => $batch->batch_no,
                    'source' => $s->source,
                    'staff' => $staff,
                    'date_in' => $s->created_at,
                    'expiry' => $expiry,
                    'remarks' => $s->remarks,
                    'approve' => $s->approve
                ];
            }
        }

        // --- Audit data ---
        $audits = Audit::latest()->get();

        // --- Return to Blade view ---
        return view('app.stock', [
            'label' => $label,
            'data' => $data,
            'data1' => $products,
            'data2' => $data2,
            'audits' => $audits,
            'page_number' => $page_number
        ]);
    }










    public function showLandingCostOLD(Request $request)
    {
        $label = "Landing Cost";
        $page_number = $request->page;

        if ($page_number == 1) {
            $page_number = 1;
        } elseif ($page_number > 1) {
            $page_number = (($page_number - 1) * 10) + 1;
        } else {
            $page_number = 1;
        }
        $label = "Landing Cost";

        //  $a_available=DB::select("SELECT SUM(quantity)-SUM(sold) as 'available' FROM batches where product_id=$p_id and deleted_at IS NULL AND sold_out <> 1;")[0]->available;

        $batches = DB::select('SELECT 
        batches.id,
        products.name,
        product_id,
        batch_no,
        (batches.quantity-batches.sold) as quantity,
        cost as landing_cost,
        (batches.quantity-batches.sold) * batches.cost as stock_value FROM `batches` 
        left join products on products.id=batches.product_id
        where  batches.deleted_at IS NULL
        AND products.approve=1
        ORDER BY `product_id` DESC');
        //dd($batches);

        return view('app.landingcost', compact('batches', 'page_number', 'label'));
    }

    public function showLandingCost(Request $request)
    {
        $label = "Landing Cost";
        $perPage = 10;

        // Filters
        $productName = $request->input('product_name');
        $batchNo = $request->input('batch_no');
        $productId = $request->input('product_id');

        // Build query
        $query = DB::table('batches')
            ->select(
                'batches.id',
                'products.name',
                'batches.product_id',
                'batch_no',
                'batches.created_at as created_at',
                'stocks.origin as origin',
                DB::raw('(batches.quantity - batches.sold) as quantity'),
                DB::raw('batches.cost as landing_cost'),
                DB::raw('(batches.quantity - batches.sold) * batches.cost as stock_value')
            )
            ->leftJoin('products', 'products.id', '=', 'batches.product_id')
            ->leftJoin('stocks', 'stocks.batch_id', '=', 'batches.id')
            ->whereNull('batches.deleted_at')
            ->where('products.approve', 1)
            ->orderByDesc('batches.product_id');

        // Apply filters
        if ($productName) {
            $query->where('products.name', 'LIKE', "%$productName%");
        }

        if ($batchNo) {
            $query->where('batches.batch_no', 'LIKE', "%$batchNo%");
        }

        if ($productId) {
            $query->where('batches.product_id', $productId);
        }
        // Date Range Filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();
            $query->whereBetween('batches.created_at', [$startDate, $endDate]);
        }

        // Paginate
        $batches = $query->paginate($perPage)->withQueryString();


        return view('app.landingcost', compact('batches', 'label'));
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
        $type = $request->type;
        if ($type == 12) {//configured new changes
            $id = Auth::id();
            $name = $request->name;
            $product_name = $name;
            $o_level = $request->o_level;
            $e_period = $request->e_period;
            $ref_no = $request->ref_no;

            $product = Product::create([
                'user_id' => $id,
                'name' => $name,
                'ref_no' => $ref_no,
                'order_level' => $o_level,
                'expire_days' => $e_period,
            ]);
            if ($product) {
                //send email notification to admin for new product creation
                $users = User::all()->where('role_type', 1);
                $link = env('APP_URL');
                foreach ($users as $user) {
                    $user = User::find($user->id);
                    $name = $user->first_name;
                    $recipient = $user->email;
                    Mail::to($recipient)->send(new ProductCreationMail($link, $recipient, $name, $product_name));
                }
                return "200";
            }
        }
        if ($type == 0) {//Store new stock
            $id = Auth::id();
            $name = $request->name;
            $o_level = $request->o_level;
            $q_type = $request->q_type;
            $batch_no = $request->batch_no;
            $e_date = $request->e_date;
            $origin = $request->origin;
            // Start the transaction
            DB::beginTransaction();
            try {
                // Create a new Batch instance
                $batch = new Batch([
                    'batch_no' => $batch_no,
                    'quantity' => $request->quantity,
                    'expiry_date' => $e_date,
                ]);
                // Save the batch to the database
                if ($batch->save()) {
                    $product = Product::create([
                        'user_id' => $id,
                        'name' => $name,
                        'ref_no' => $request->ref_no ?? Null,
                        'quantity' => $request->quantity,
                        'order_level' => $o_level,
                        'quantity_type' => $q_type
                    ]);
                    if ($product) {
                        // Commit the transaction if everything is successful
                        DB::commit();//Save Batch info
                        //update product id number in batch file
                        Batch::where('id', $batch->id)->update(['product_id' => $product->id]);
                        $product_id = $product->id;
                        $stock = Stock::create([
                            'user_id' => $id,
                            'product_id' => $product_id,
                            'batch_id' => $batch->id,
                            'quantity' => $request->quantity,
                            'sold' => 0,//indicate that 0 have been sold since its new stock
                            'quantity_type' => $q_type,
                            'type' => 0, //To know whether its new(0) or return(1) stock when reading data
                            'source' => $request->source,
                            'remarks' => $request->remarks,
                        ]);
                        //////////////////////////////////////////////////////    Send Email to check expiry of product and end alert //////////////////////////////////////////////////////////
                        $e_date = Carbon::parse($e_date); // Convert to a Carbon instance

                        $threeMonthsFromNow = Carbon::now()->addMonths(3);

                        if ($e_date->lessThan($threeMonthsFromNow)) {
                            // Expiry date is less than 3 months from now
                            $user = User::find(Auth::id());
                            $name = $user->first_name;
                            $product_name = $request->name;
                            $recipient = $user->email;
                            $expiry = $$request->e_date;
                            $batch_no = $request->batch_no;
                            $current_order_qty = Product::where('id', $product_id)->pluck('quantity')->first();
                            $link = env('APP_URL');
                            //get all admin to be sent the mail
                            $users = User::all()->where('role_type', 1);
                            foreach ($users as $user) {
                                $user = User::find($user->id);
                                $name = $user->first_name;
                                $recipient = $user->email;
                                // Mail::to($recipient)->send(new ExpiryAlertMail($link, $recipient,$name,$product_name,$expiry,$batch_no));
                            }

                            ///////////////////////////////////////////  Send SMS Too  //////////////////////////////////////////////////////////////////////
                            $users = User::all()->where('role_type', 1);
                            foreach ($users as $user) {
                                $user = User::find($user->id);
                                $mobile = $user->contacts;
                                //$mobile=User::where('id',Auth::id())->pluck('contacts')->first();
                                //$msg='Order Level for Product: '.$p_name.' has been reached.\n\nPlease Restock\nInvitro';
                                //$mobile=$user->contacts;
                                $msg = 'The Expiry date for Product: ' . $p_name . ' is approaching in the next 3 months.\nRegards\nInvitro';
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
                            }

                        } else {
                            // Expiry date is more than or equal to 3 months from now
                            echo "Dont send mail";
                        }

                        //////////////////////////////////////////////////////    Send Email to notify approval of new Product  //////////////////////////////////////////////////////////
                        // PAUSE EMAIL SENDING
                        /*               
                            $user=User::find(Auth::id());
                            $name=$user->first_name;
                            $product_name=$request->name;
                            $recipient=$user->email;
                            $expiry=$request->e_date;
                            $link=env('APP_URL');
                            //get all admin to be sent the mail
                            $users=User::all()->where('role_type',1);
                            foreach($users as $user){
                            $user=User::find($user->id);
                            $name=$user->first_name;
                            $recipient=$user->email;
                            Mail::to($recipient)->send(new NewProductAlertMail($link, $recipient,$name,$product_name,$expiry,$batch_no));
                            }
                        */


                    } else {
                        echo "101";//Error fo duplication for product name
                        // Handle any exceptions that occurred during the transaction
                        DB::rollback();
                        return "101";
                    }
                } else {
                    echo "100";//batch number already exists
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
                // Handle any exceptions that occurred during the transaction
                DB::rollback();
                // Optionally, you can log the error or perform any other necessary actions
            }



        } elseif ($type == 1) {//**************************** store restock  ********************************************//
            $id = Auth::id();
            $name = $request->name;
            //check if product with given name exists
            $check = Product::where('name', $name)->exists();
            if (!$check) {//Product does not exist
                return '404';
            }
            $product_id = Product::where('name', $name)->pluck('id')->first();
            $quantity = $request->quantity;
            $batch_no = $request->batch_no;
            $invoice = $request->invoice;
            $d_note = $request->d_note;
            $e_date = $request->e_date;
            $expires = $request->expires;
            $cost = $request->cost;
            $origin = $request->origin;
            //Store in batch tables
            $batch = Batch::create([
                'batch_no' => $batch_no,
                'product_id' => $product_id,
                'cost' => $cost,
                'quantity' => $quantity,
                'expiry_date' => $expires == 1 ? $e_date : '3024-11-01 00:00:00',
            ]);
            if ($batch) {
                //Get product quantity from db and increment
                $qty = Product::where('id', $product_id)->pluck('quantity')->first();
                $q_type = Product::where('id', $product_id)->pluck('quantity_type')->first();
                $product = Product::where('id', $product_id)->update([
                    'quantity' => $qty + $quantity,
                ]);

                if ($product) {
                    $product_id = $product_id;
                    $stock = Stock::create([
                        'user_id' => $id,
                        'product_id' => $product_id,
                        'batch_id' => $batch->id,
                        'quantity' => $request->quantity,
                        'quantity_type' => $q_type,
                        'invoice' => $invoice,
                        'delivery_note' => $d_note,
                        'sold' => 0,//indicate that 0 have been sold since its new stock
                        'type' => 0, //To know whetehr its new(0) or return(1) stock when reading data
                        'source' => $request->source,
                        'remarks' => $request->remarks,
                        'origin' => $origin,
                        'approve' => 0,
                    ]);
                    if ($stock) {
                        // Populate Restock Log
                        Restock::create([
                            'user_id' => $id,
                            'product_id' => $product_id,
                            'quantity' => $quantity,
                            'source' => $request->source,
                            'landing_cost' => $cost,
                            'invoice_number' => $invoice,
                            'delivery_note' => $d_note,
                            'remarks' => $request->remarks,
                            'restock_date' => Carbon::now(),
                        ]);

                        //Log activity to file
                        Log::channel('add_stock')->notice('New stock added. Of Id: ' . $product_id . '. Added by ' . Auth::user()->first_name . ' Email: ' . Auth::user()->email);
                        //////////////////////////////////////////////////////    Send Email to check expiry of product and end alert //////////////////////////////////////////////////////////
                        $e_date = Carbon::parse($e_date); // Convert to a Carbon instance
                        $e_period = Product::where('id', $product_id)->pluck('expire_days')->first();
                        $expiryDate = Carbon::parse($e_period);

                        // Get today's date
                        $today = Carbon::now();

                        // Calculate the difference in days
                        $daysDifference = $today->diffInDays($e_date);
                        if ($daysDifference < $e_period) {
                            $user = User::find(Auth::id());
                            $name = $user->first_name;
                            $product_name = Product::where('id', $product_id)->pluck('name')->first();
                            $recipient = $user->email;
                            $expiry = $request->e_date;
                            $current_order_qty = Product::where('id', $product_id)->pluck('quantity')->first();
                            $link = env('APP_URL');
                            $batch_no = $batch->batch_no;
                            $users = User::all()->where('role_type', 1);
                            foreach ($users as $user) {
                                $user = User::find($user->id);
                                $name = $user->first_name;
                                $recipient = $user->email;
                                Mail::to($recipient)->send(new ExpiryAlertMail($link, $recipient, $name, $product_name, $expiry, $batch_no));
                            }


                            ///////////////////////////////////////////  Send SMS   //////////////////////////////////////////////////////////////////////
                            $users = User::all()->where('role_type', 1);
                            foreach ($users as $user) {
                                $user = User::find($user->id);
                                $mobile = $user->contacts;
                                //$mobile=User::where('id',Auth::id())->pluck('contacts')->first();
                                //$msg='Order Level for Product: '.$p_name.' has been reached.\n\nPlease Restock\nInvitro';
                                //$mobile=$user->contacts;
                                $msg = 'The Expiry date for Product: ' . $product_name . ' is approaching in the next ' . $daysDifference . ' days.\nRegards\nInvitro';
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
                            }

                        } else {
                            return "Not Expired";
                        }

                        //////////////////////////////////////////////////////    Send Email to notify approval of new Product  //////////////////////////////////////////////////////////
                        //PAUSE EMAIL SENDING
                        /*
                            $user=User::find(Auth::id());
                            $name=$user->first_name;
                            $product_name=Product::where('id',$product_id)->pluck('name')->first();
                            $recipient=$user->email;
                            $expiry=$e_date;
                            $batch_no=$batch->batch_no;
                            $link=env('APP_URL');
                            $users=User::all()->where('role_type',1);
                            foreach($users as $user){
                                $user=User::find($user->id);
                                $name=$user->first_name;
                                $recipient=$user->email;
                                Mail::to($recipient)->send(new NewProductAlertMail($link, $recipient,$name,$product_name,$expiry,$batch_no));
                            }
                         */


                    }
                } else {
                    return "error";
                }
            } else {
                throw new \Exception('Batch exist');
            }

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
        $type = $request->type;
        if ($type == 0) {

        } elseif ($type == 1) {// Edit stock details
            $e_date = $request->input('e_date');
            $rmks = $request->input('remarks');
            $batch_id = $request->batch_id;
            $batch_no = $request->batch_no;
            $stock_id = $request->batch_id;
            $product_id = $request->batch_id;
            $quantity = $request->quantity;
            $quantity_type = $request->q_type;
            $source = $request->source;
            //$product_name=$request->name;

            //Extract Quantity in DB so as to correctly cummulate the all products quantity correctly
            $quantity_db = Batch::where('id', $batch_id)->pluck('quantity')->first();//initial batch quantity about to be changed
            $init_product_qty = Product::where('id', $product_id)->pluck('quantity')->first();//Initial Total product quantity for all thos batches
            $quantity_change = $init_product_qty - $quantity_db + $quantity;//To be updated in product table

            if (is_null($e_date)) {
                // expiry_date is null just update batch number
                $batch = Batch::where('id', $batch_id)->update(['batch_no' => $batch_no, 'quantity' => $quantity]);
            } else {
                // expiry_date has a value. update both
                $batch = Batch::where('id', $batch_id)->update(['batch_no' => $batch_no, 'quantity' => $quantity, 'expiry_date' => $e_date]);
            }

            if ($batch) {
                //update Stock Table. first check if remarks is null
                if (is_null($rmks)) {
                    $stock = Stock::where('id', $stock_id)->update(['quantity' => $quantity, 'quantity_type' => $quantity_type, 'source' => $source]);
                    if ($stock) {
                        Product::where('id', $product_id)->update(['quantity' => $quantity_change]);
                    }
                } else {
                    $stock = Stock::where('id', $stock_id)->update(['quantity' => $quantity, 'quantity_type' => $quantity_type, 'source' => $source, 'remarks' => $rmks]);
                    if ($stock) {
                        Product::where('id', $product_id)->update(['quantity' => $quantity_change]);
                    }
                }
            } else {
                return "501";//Batch Number should be unique
            }
            return "200";
        } elseif ($type == 2) {// Edit order level of a product
            $o_level = $request->o_level;
            $e_period = $request->e_period;
            $p_name = $request->p_name;
            $product_id = $request->editorderId;
            $edit = Product::where("id", $product_id)->update(['order_level' => $o_level, 'expire_days' => $e_period, 'name' => $p_name]);
            if ($edit) {
                return "200";
            } else {
                return "500";
            }
        } elseif ($type == 3) {//Store the stock Audit Information
            $status = $request->status;
            $comments = $request->comments;
            $product_id = $id;
            $audit = Audit::create([
                'user_id' => Auth::id(),
                'product_id' => $product_id,
                'status' => $status,
                'comments' => $comments
            ]);

            if ($audit) {
                return "200";
            } else {
                return "500";
            }

        } elseif ($type == 4) {// Edit product details coming from approve section
            $o_level = $request->o_level;
            $e_period = $request->e_period;
            $p_name = $request->p_name;
            $product_id = $request->editorderId;
            $edit = Product::where("id", $product_id)->update(['order_level' => $o_level, 'expire_days' => $e_period, 'name' => $p_name]);
            if ($edit) {
                return "200";
            } else {
                return "500";
            }

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $type = $request->type;
        if ($type == 0) {//deleting product
            Product::find($id)->delete();
        } elseif ($type == 1) {//deleting a batch
            $id = $request->id;
            $qty = Batch::where('id', $id)->pluck('quantity')->first();
            $sold = Batch::where('id', $id)->pluck('sold')->first();
            $p_id = Batch::where('id', $id)->pluck('product_id')->first();
            $amt = $qty - $sold;
            $p_amt = Product::where('id', $p_id)->pluck('quantity')->first();
            $new_qty = $p_amt - $amt;
            Product::where('id', $p_id)->update(['quantity' => $new_qty]);
            Batch::find($id)->delete();
            Stock::where('batch_id', $id)->delete();
        }

    }



    public function store_ref(Request $request)
    {
        $name = $request->name;
        $update = Product::where('name', $name)->update(['ref_no' => $request->ref_no]);
        if ($update) {
            return "Reference Number Updated Successfully.";
        } else {
            return "500";
        }
    }
}
