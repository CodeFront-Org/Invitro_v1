@extends('layouts.app')

@section('content')
@if (session()->has('message'))
    <div id="toast" class="alert text-center alert-success alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ session('message') }}
    </div>
@endif

@if (session()->has('error'))
    <div id="toast" class="alert text-center alert-danger alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ session('error') }}
    </div>
@endif


        <div class="row mt-1">
            <div class="col-12">
                @role('staff')
            <button  style="background-color: #08228a9f;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
                    <i class='fa fa-plus' aria-hidden='true'></i>  New
                </button>
                @endrole
                <div class="card" style="border-radius:0px 15px 15px 15px;box-shadow: 2px 3px 3px 2px rgba(9, 107, 255, 0.179);">
                    <div class="card-body">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table style="font-family: 'Times New Roman', Times, serif;" class="table table-bordered nowrap text-center" id="datatable">
                                <thead class="table-light">
                                    
                                    @php
                                        $page=$page_number;
                                    @endphp
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Batches</th>
                                    <th>Quantity</th>
                                    <th>Destination</th>
                                    <th>Invoice</th>
                                    <th>Reciept</th>
                                    <th>Cash</th>
                                    <th>Staff Incharge</th>
                                    <th>Date</th>
                                    <th>Remarks</th>
                                    <th>Approval</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>


                                <tbody>
                                @foreach ($orders as $item)
                                    <tr>
                                        <td>{{$page}} </td>
                                        <td>{{$item['product_name']}}</td>
                                        <td>{{$item['batch_used']}}</td>
                                        <td>{{$item['quantity']}}</td>
                                        <td>{{$item['destination']}}</td>
                                        <td>{{$item['invoice']}}</td>
                                        <td>{{$item['receipt']}}</td>
                                        <td>{{$item['cash']}}</td>
                                        <td>{{$item['staff']}}</td>
                                        <td>{{$item['date']}}</td>
                                        <td class="text-left" style="min-width: 110px; max-width: 110px; overflow: hidden; font-size: 12px;">
                                                    {{$item['rmks']}}
                                        </td>
                                        <td>
                                        @if ($item['approve'] == 0)
                                            <span style="color:red">Pending</span>
                                        @else
                                            <span style="color:green">Approved</span>
                                        @endif
                                        </td>
                                        <td style='font-size:10px; text-align: center;'>
                                            <!--<button {{$item['approve']==1?"disabled":''}} type="button" style="background-color: #08228a9f;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-edit-1">
                                                <i class='fas fa-pen' aria-hidden='true'></i>
                                                </button>-->
                                            <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-txn-{{$item['id']}}">
                                                <i class='fas fa-newspaper' aria-hidden='true'></i>
                                                </button>
                                                <a href="{{route('/product-orders',['product_id'=>$item['product_id']])}}">
                                           <button type="button" style="background-color: #006fd6aa;color: white" class="btn btn-xs">
                                                <i class='fas fa-eye' aria-hidden='true'></i>
                                                </button>
                                            </a>
                                            @role('admin')
                                            <button type="button" onclick="del(this)" value="{{$item['order_id']}}" class="btn btn-danger btn-xs">
                                                <i class='fa fa-trash' aria-hidden='true'></i>
                                            </button>

                                            @endrole
                                        </td>
                                    </tr>
                                @php
                                    $page+=1;
                                @endphp
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination links -->
                        <div class="d-flex justify-content-end" style="margin-top: 20px;height:30%;height:1032%"> <!-- Adjust margin-top as needed -->
                            <div style="margin-right: 0; text-align: right; font-size: 14px; color: #555;">
                                {{ $data1->appends(request()->except('page'))->links('vendor.pagination.simple-bootstrap-4')}}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- end row -->


            <!-- Add New Order Modal -->

            <div id="con-close-modal-add-1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="newOrderForm12" method="post" action="{{route('/place-order')}}" onsubmit="document.getElementById('newaddbtn').disabled = true;">
                        @csrf
                        @method('get')
                        <input type="hidden" name="type" value="0">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Order</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                    <label for="field-11w" class="form-label">Product Name</label>
                                        @php
                                            use App\Models\Product;
                                            $products = Product::select('name')->where('approve',1)->get();

                                        @endphp

                                        <input type="text" list="regnoo" parsley-trigger="change" required class="form-control"
                                            id="p_name" name='name' autocomplete="off" placeholder="Search Product ..." aria-label="Recipient's username"
                                        />

                                        <datalist id="regnoo">
                                            @foreach ($products as $product)
                                                <option value="{{ $product->name }}">{{ $product->name }}</option>
                                            @endforeach
                                        </datalist>
                                </div>
                                </div>
                                <!--
                                <div class="col-md-6">
                                    <div class="mb-3">
                                    <label for="field-11w2" class="form-label">Batch Number</label>
                                    <select name="batch_id" class="form-control form-select" id="field-11w2" required>
                                        @foreach ($data as $item)
                                            <option value="{{$item['batch_id']}}">{{$item['batch_no']}}</option>
                                        @endforeach
                                        </select>
                                </div>
                                </div>-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Quantity</label>
                                        <input type="text" name="quantity" class="form-control" id="field-2l" placeholder="quantity" required>
                                    </div>
                                </div><!--
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2n" class="form-label">Amount</label>
                                        <input type="number" name="amount" class="form-control" id="field-2n" placeholder="amount" required>
                                    </div>
                                </div>-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Customer</label>
                                        <input type="text" name="destination" class="form-control" id="field-2l" placeholder="customer" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2n" class="form-label">Invoice Number</label>
                                        <input type="text" name="invoice" class="form-control" id="field-2n" placeholder="invoice number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Reciept</label>
                                        <input type="text" name="receipt" class="form-control" id="field-2l" placeholder="Receipt Number">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Cash Sale</label>
                                        <input type="text" name="cash" class="form-control" id="field-2l" placeholder="Sales Number">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Delivery Note</label>
                                        <input type="text" name="d_note" class="form-control" id="field-2l" placeholder="Delivery Note">
                                    </div>
                                </div>
                               <!-- <div class="col-md-12">
                                    <div class="mb-3">
                                    <label for="field-11w" class="form-label">Cash Payment</label>
                                    <select name="cash" class="form-control form-select" id="field-11w" required>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                </div>
                                </div>-->
                               <!-- <div class="col-md-6 mt-1 mb-3">
                                    <div class="form-check form-check-success">
                                        <input type="checkbox" name="cash" class="form-check-input check-btn" id="invalidCheck" required />
                                        <label class="form-check-label" for="invalidCheck">Cash Payment</label>

                                    </div>
                                </div>-->
                                <!--<div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Date</label>
                                        <input type="date" name="date" class="form-control" id="field-2l" placeholder="expiry date" required>
                                    </div>
                                </div>-->
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="field-2" class="form-label">Remarks</label>
                                        <textarea id="textarea" name="remarks" class="form-control" required maxlength="300" rows="3" placeholder="Your Remarks"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn rounded-pill p-1" id="newaddbtn" style="width: 100%; background-color: #08228a9f;color: white" type="submit">
                                    Submit
                            </button>
                            <button class="btn rounded-pill p-1" id="newaddloader" style="width: 100%; background-color: #08228a9f;color: white;display:none;" type="button">
                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                    Saving Data...
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div><!-- /.modal -->

            <!-- Return Stock Modal -->
@foreach ($orders as $item)
    <div id="con-close-modal-return-{{$item['id']}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{route('/return-stock')}}">
                @csrf
                @method('post')
                <input type="hidden" name="type" value="2">
                <input type="hidden" name="batch_id" value="{{$item['batch_id']}}">
                <input type="hidden" name="order_id" value="{{$item['id']}}">
                <div class="modal-header">
                    <h4 class="modal-title">Return Stock: Name-> {{$item['product_name']}}  Batch-> {{$item['batch']}}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="field-2l" class="form-label">Quantity</label>
                                <input type="text" name="quantity" class="form-control" id="field-2l" placeholder="quantity" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="field-2" class="form-label">Remarks</label>
                                <textarea id="textarea" class="form-control" required maxlength="300" rows="3" placeholder="Your Remarks"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn rounded-pill p-1" id="addbtn" style="width: 100%; background-color: #08228a9f;color: white" type="submit">
                            Submit
                    </button>
                    <button class="btn rounded-pill p-1" id="editloader" style="width: 100%; background-color: #08228a9f;color: white;display:none;" type="button">
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Saving Data...
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div><!-- /.modal -->
@endforeach

            <!-- View Transaction of Stock Modal -->
@foreach ($orders as $item1)
    <div id="con-close-modal-txn-{{$item1['id']}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="settiingsForm" method="post">
                @csrf
                @method('PATCH')
                <input type="hidden" name="type" value="0">
                <div class="modal-header">
                    <h4 class="modal-title">Order Breakdown</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="table-responsive">
                    <table  style="font-family: 'Times New Roman', Times, serif" class="table table-bordered nowrap text-center" id="datatable" class="table table-sm table-bordered dt-responsive nowrap text-center">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Batch/Lot No:</th>
                            <th>Qty Used</th>
                            <th>Balance</th>
                            <th>Expiry Date</th>
                        </tr>
                        </thead>


                        <tbody>
                        @php $count = 1 @endphp

                        @foreach ($view_data as $item)
                            @if ($item1['id'] == $item['id'])
                                <tr>
                                    <td>{{ $count }}.</td>
                                    <td>{{ $item['batch_no'] }}</td>
                                    <td>{{ $item['qty_used'] }}</td>
                                    <td>{{ $item['balance'] }}</td>
                                    <td>{{ $item['expiry_date'] }}</td>
                                </tr>
                                @php $count++ @endphp
                            @endif
                        @endforeach

                        </tbody>
                    </table>
                </div>
                </div>
                </form>
            </div>
        </div>
    </div><!-- /.modal -->
@endforeach



            <!-- Edit Order Modal -->

            <div id="con-close-modal-edit-1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="settiingsForm" method="post">
                        @csrf
                        @method('post')
                        <input type="hidden" name="type" value="0">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Order</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2n" class="form-label">Product</label>
                                        <input type="text" name="name" class="form-control" id="field-2n" placeholder="product name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Quantity</label>
                                        <input type="text" name="quantity" class="form-control" id="field-2l" placeholder="quantity" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2n" class="form-label">Amount</label>
                                        <input type="number" name="amount" class="form-control" id="field-2n" placeholder="amount" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Destination</label>
                                        <input type="text" name="destination" class="form-control" id="field-2l" placeholder="destination" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2n" class="form-label">Invoice Number</label>
                                        <input type="text" name="invoice" class="form-control" id="field-2n" placeholder="invoice number" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Receipt</label>
                                        <input type="text" name="receipt" class="form-control" id="field-2l" placeholder="Receipt Number" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Date</label>
                                        <input type="date" name="date" class="form-control" id="field-2l" placeholder="expiry date" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="field-2" class="form-label">Remarks</label>
                                        <textarea id="textarea" name="remarks" class="form-control" required maxlength="300" rows="3" placeholder="Your Remarks"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn rounded-pill p-1" id="addbtn" style="width: 100%; background-color: #08228a9f;color: white" type="submit">
                                    Submit
                            </button>
                            <button class="btn rounded-pill p-1" id="editloader" style="width: 100%; background-color: #08228a9f;color: white;display:none;" type="button">
                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                    Saving Data...
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div><!-- /.modal -->

@endsection

@section('scripts')
    <script>
    $(document).ready(function(){
//Add newOrderForm
$("#newOrderForm").on('submit',(e)=>{
e.preventDefault();
var btn=$("#newbtn");
var loader=$("#newloader")
btn.hide();
loader.show();
let data=$("#newOrderForm").serialize();
$.ajax({
    type: "POST",
    url: "/order",
    data: data,
    success: function (response) {
console.log(response)
if(response==504){
    btn.show();
    loader.hide();
    Swal.fire("Error!", "Product Does not exists", "error");
    return
}
if(response.status===404){//Means quantity exceeded
        btn.show();
        loader.hide();
        Swal.fire("Quantity Exceeded!", "Available Qty is: "+response.quantity, "error");
        return
}
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    toastr["success"]("", "Order Created Succesfully.")

    location.href='order'

    },
    error: function(res){ console.log(res)
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Try again later...", "error");
    }
});
})

// Edit settings Form
$(".settiingsEditForm").on('submit', function(e) {
  e.preventDefault();

  const form = $(this);
  var itemId = form.find('input[name="editsettingId"]').val();
  var btn = $("#editbtn" + itemId);
  var loader = $("#editloader" + itemId);
  btn.hide();
  loader.show();
  let data = form.serialize();
$.ajax({
    type: 'PATCH',
    url: '/#/' + itemId,
    data: data,
    success: function (response) { console.log(response)

                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    toastr["success"]("", "Settings Update Succesfully.")
        location.href='#'
    },
    error: function(res){ console.log(res)
        Swal.fire("Error!", "Try again later...", "error");
    }
});
})


    })
    </script>

    <script>

        //Deleting Settings
        function del(e){
        let id=e.value;
        var type=0;//For knowing deletion operation is coming from settings
        Swal.fire({
            title: "Confirm deletion",
            text: "You won't be able to revert this!",
            type: "error",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((t)=>{
        if(t.value){
                $.ajax({
                    type: "DELETE",
                    url: "order/"+id,
                    data:{
                        _token:"{{csrf_token()}}", id,type
                    },
                    success: function (response) { console.log(response)

                        Swal.fire("Deleted", "Successfully.", "success").then(()=>{
                        location.href='/order'})
                    },
                    error: function(res){console.log(res)
                        Swal.fire("Error!", "Try again later...", "error");
                    }
                });
            }
            })
        }
    </script>
@endsection
