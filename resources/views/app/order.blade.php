@extends('layouts.app')

@section('content')
        <div class="row mt-1">
            <div class="col-12">
            <button  style="background-color: #08228a9f;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
                    <i class='fa fa-plus' aria-hidden='true'></i>  New
                </button>
                <div class="card" style="border-radius:0px 15px 15px 15px;box-shadow: 2px 3px 3px 2px rgba(9, 107, 255, 0.179);">
                    <div class="card-body">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table style="font-family: 'Times New Roman', Times, serif;" class="table table-bordered nowrap text-center" id="datatable">
                                <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Batch</th>
                                    <th>Quantity</th>
                                    <th>Destination</th>
                                    <th>Invoice</th>
                                    <th>Reciept</th>
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
                                        <td>{{$loop->index+1}} </td>
                                        <td>{{$item['product_name']}}</td>
                                        <td>{{$item['batch']}}</td>
                                        <td>{{$item['quantity']}}</td>
                                        <td>{{$item['destination']}}</td>
                                        <td>{{$item['invoice']}}</td>
                                        <td>{{$item['receipt']}}</td>
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
                                            <button {{$item['approve']==1?"disabled":''}} type="button" style="background-color: #08228a9f;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-edit-1">
                                                <i class='fas fa-pen' aria-hidden='true'></i>
                                                </button>
                                            <button type="button" style="background-color: #006fd6aa;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-return-{{$item['id']}}">
                                                <i class='fas fa-minus-circle' aria-hidden='true'></i>
                                                </button>
                                            <button type="button" onclick="del(this)" value="" class="btn btn-danger btn-xs">
                                                <i class='fa fa-trash' aria-hidden='true'></i>
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- end row -->


            <!-- Add New Order Modal -->

            <div id="con-close-modal-add-1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="newOrderForm" method="post">
                        @csrf
                        @method('post')
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
                                    <select name="product_id" class="form-control form-select" id="field-11w" required>
                                        @foreach ($products as $item)
                                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                                        @endforeach
                                        </select>
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                    <label for="field-11w2" class="form-label">Batch Number</label>
                                    <select name="batch_id" class="form-control form-select" id="field-11w2" required>
                                        @foreach ($data as $item)
                                            <option value="{{$item['batch_id']}}">{{$item['batch_no']}}</option>
                                        @endforeach
                                        </select>
                                </div>
                                </div>
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
                                        <label for="field-2l" class="form-label">Destination</label>
                                        <input type="text" name="destination" class="form-control" id="field-2l" placeholder="destination" required>
                                    </div>
                                </div>
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
                            <button class="btn rounded-pill p-1" id="newbtn" style="width: 100%; background-color: #08228a9f;color: white" type="submit">
                                    Submit
                            </button>
                            <button class="btn rounded-pill p-1" id="newloader" style="width: 100%; background-color: #08228a9f;color: white;display:none;" type="button">
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
                    url: "settings/"+id,
                    data:{
                        _token:"{{csrf_token()}}", id,type
                    },
                    success: function (response) { console.log(response)

                        Swal.fire("Deleted", "Successfully.", "success").then(()=>{
                        location.href='#'})
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
