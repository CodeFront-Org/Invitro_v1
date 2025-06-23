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
        <button id="excelbtn" type="button" class="btn btn-success"><i class="fa fa-file-excel bg-success"></i> excel </button>
                <div class="card" style="border-radius:0px 15px 15px 15px;box-shadow: 2px 3px 3px 2px rgba(9, 107, 255, 0.179);">
                    <div class="card-body">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table style="font-family: 'Times New Roman', Times, serif;" class="table table-bordered nowrap text-center" id="salestable">
                                <thead class="table-light">
                                    
                                    @php
                                        $page=1;
                                    @endphp
                                <tr>
                                    <th>#</th>
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

    
                                @foreach ($data1 as $item)
                                    <tr>
                                        <td>{{$page}} </td>
                                        <td>{{$item->batch_used}}</td>

                                        <td>{{$item->quantity}}</td>
                                        <td>{{$item->destination}}</td>
                                        <td>{{$item->invoice}}</td>
                                        <td>{{$item->receipt}}</td>
                                        <td>{{$item->cash}}</td>
                                        <td>{{$item->user_id}}</td>
                                        <td>{{$item->created_at}}</td>
                                        <td class="text-left" style="min-width: 110px; max-width: 110px; overflow: hidden; font-size: 12px;">
                                                    {{ $item->remarks }}
                                        </td>
                                        <td>
                                        @if ($item->approve == 0)
                                            <span style="color:red">Pending</span>
                                        @else
                                            <span style="color:green">Approved</span>
                                        @endif
                                        </td>
                                        <td style='font-size:10px; text-align: center;'>
                                            <!--<button {{$item['approve']==1?"disabled":''}} type="button" style="background-color: #08228a9f;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-edit-1">
                                                <i class='fas fa-pen' aria-hidden='true'></i>
                                                </button>-->
                                            <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-txn-{{$item->id}}">
                                                <i class='fas fa-newspaper' aria-hidden='true'></i>
                                                </button>
                                           <!-- <button type="button" style="background-color: #006fd6aa;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-return-{{$item->id}}">
                                                <i class='fas fa-minus-circle' aria-hidden='true'></i>
                                                </button>-->

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

    {{-- <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Batch/Lot No:</th>
                <th>Qty Used</th>
                <th>Balance</th>
                <th>Expiry Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($view_data as $item)
           
                <tr>
                    <td>{{ $item['id'] }}.</td>
                    <td>{{ $item['batch_no'] }}</td>
                    <td>{{ $item['qty_used'] }}</td>
                    <td>{{ $item['balance'] }}</td>
                    <td>{{ $item['expiry_date'] }}</td>
                </tr>
               
         
              @endforeach
        </tbody>
    </table> --}}
                  

                    <!-- View Transaction of Stock Modal -->
@foreach ($data1  as $item1)
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
                                <td>{{ $item['id'] }}.</td>
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

<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

<script type="text/javascript">
$(document).ready(function () {
    $("#excelbtn").click(function(){
        TableToExcel.convert(document.getElementById("salestable"), {
            name: "Invitro product sales report.xlsx",
            sheet: {
            name: "Sheet1"
            }
        });
        });
});
</script>


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
