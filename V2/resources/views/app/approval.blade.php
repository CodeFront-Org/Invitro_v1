@extends('layouts.app')

@section('content')
@if (session()->has('message'))
    <div id="toast" class="alert text-center alert-success alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        Approved Successfully.
    </div>
@endif
        <div class="row mt-1">
            <div class="col-12">
            <button  style="background-color: #08228a9f;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
                     Approve New Stock
                </button>
                <div class="card" style="border-radius:0px 15px 15px 15px;box-shadow: 2px 3px 3px 2px rgba(9, 107, 255, 0.179);">
                <form action="{{route('approve.store')}}" method="post">
                    @csrf
                    @method("POST")
                    <input type="hidden" name="type" value="0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table  style="font-family: 'Times New Roman', Times, serif" class="table table-bordered nowrap text-center" id="datatable" class="table table-sm table-bordered dt-responsive nowrap text-center">
                                <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Batch</th>
                                    <th>Quantity</th>
                                    <th>level</th>
                                    <th>Source</th>
                                    <th>Staff</th>
                                    <th>Date In</th>
                                    <th>Expiry</th>
                                    <th>Remarks</th>
                                    <th>Approve</th>
                                </tr>
                                </thead>


                                <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{$loop->index+1}}. </td>
                                        <td>{{$item['name']}}</td>
                                        <td>{{$item['batch_no']}}</td>
                                        <td>{{$item['quantity']}}</td>
                                        <td>{{$item['order_level']}}</td>
                                        <td>{{$item['source']}}</td>
                                        <td>{{$item['staff_name']}}</td>
                                        <td>{{$item['date_in']->format('d F Y')}}</td>
                                        <td>{{$item['expiry_date']}}</td>
                                        <td class="text-left" style="min-width: 100px; max-width: 100px; overflow: hidden; font-size: 12px;">
                                                {{$item['remarks']}}
                                        </td>
                                        <td style="color:green">
                                            <input type="checkbox" class="custom-control-input" name="status[]" value="{{$item['id']}}">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info waves-effect waves-light">Save changes</button>
                    </div>
                    </form>
                </div>

            </div>
        </div> <!-- end row -->



<!-- Approve Orders -->
        <div class="row mt-1">
            <div class="col-12">
            <button  style="background-color: #08228a9f;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
                     Approve Orders
                </button>
                <div class="card" style="border-radius:0px 15px 15px 15px;box-shadow: 2px 3px 3px 2px rgba(9, 107, 255, 0.179);">
                <form action="{{route('approve.store')}}" method="post">
                    @csrf
                    @method("POST")
                    <input type="hidden" name="type" value="1">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table  style="font-family: 'Times New Roman', Times, serif" class="table table-bordered nowrap text-center" id="datatable" class="table table-sm table-bordered dt-responsive nowrap text-center">
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
                                    <th>View</th>
                                    <th>Approve</th>
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
                                        <td class="text-left" style="min-width: 130px; max-width: 130px; overflow: hidden; font-size: 12px;">
                                                {{$item['rmks']}}
                                        </td>
                                        <td style='font-size:10px; text-align: center;'>
                                            <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-txn-{{$item['id']}}">
                                                <i class='fas fa-newspaper' aria-hidden='true'></i>
                                                </button>
                                        </td>
                                        <td style="color:green">
                                            <input type="checkbox" class="custom-control-input" name="status[]" value="{{$item['id']}}">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info waves-effect waves-light">Save changes</button>
                    </div>
                    </form>
                </div>

            </div>
        </div> <!-- end row -->



<!-- Approve Returns
        <div class="row mt-1">
            <div class="col-12">
            <button  style="background-color: #08228a9f;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
                     Approve Return of Stock
                </button>
                <div class="card" style="border-radius:0px 15px 15px 15px;box-shadow: 2px 3px 3px 2px rgba(9, 107, 255, 0.179);">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table  style="font-family: 'Times New Roman', Times, serif" class="table table-bordered nowrap text-center" id="datatable" class="table table-sm table-bordered dt-responsive nowrap text-center">
                                <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Destination</th>
                                    <th>Remarks</th>
                                    <th>Approve</th>
                                </tr>
                                </thead>


                                <tbody>
                                    <tr>
                                        <td>1. </td>
                                        <td>Panadol</td>
                                        <td>3 Cartons</td>
                                        <td>KEMSA</td>
                                        <td class="text-left" style="min-width: 200px; max-width: 200px; overflow: hidden; font-size: 12px;">
                                                My remarks on entry of stock.
                                        </td>
                                        <td style="color:green">
                                            <input type="checkbox" class="custom-control-input" name="status[]" value="">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info waves-effect waves-light">Save changes</button>
                    </div>
                </div>

            </div>
        </div>--> <!-- end row -->



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



@endsection

@section('scripts')
    <script>
    $(document).ready(function(){
//Add settings Form
$("#settiingsForm").on('submit',(e)=>{
e.preventDefault();
var btn=$("#addbtn");
var loader=$("#addloader")
btn.hide();
loader.show();
let data=$("#settiingsForm").serialize();
$.ajax({
    type: "POST",
    url: "#",
    data: data,
    success: function (response) {

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
                    toastr["success"]("", "Settings Saved Succesfully.")
        location.href='#'
    },
    error: function(res){
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
