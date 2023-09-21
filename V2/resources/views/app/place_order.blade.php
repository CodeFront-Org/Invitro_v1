@extends('layouts.app')

@section('content')
        <div class="row mt-1">
            <div class="col-12">
                <div class="card" style="border-radius:0px 15px 15px 15px;box-shadow: 2px 3px 3px 2px rgba(9, 107, 255, 0.179);">
                    <div class="card-body">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <form action="{{route('/complete-order')}}" method="POST">
                            @method('GET')
                            <input type="hidden" name="product_id" value="{{$product_id}}" />
                            <input type="hidden" name="total_quantity" value="{{$tot_qty}}" />
                            <input type="hidden" name="destination" value="{{$destination}}" />
                            <input type="hidden" name="invoice" value="{{$invoice}}" />
                            <input type="hidden" name="receipt" value="{{$receipt}}" />
                            <input type="hidden" name="d_note" value="{{$d_note}}" />
                            <input type="hidden" name="cash" value="{{$cash}}" />
                            <input type="hidden" name="remarks" value="{{$remarks}}" />
                        <table style="font-family: 'Times New Roman', Times, serif;" class="table table-bordered nowrap text-center" id="datatable">
                                <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Batch/Lot No:</th>
                                    <th>Available</th>
                                    <th>Used</th>
                                    <th>Balance</th>
                                    <th>Expiry Date</th>
                                </tr>
                                </thead>


                                <tbody>
                                    @foreach($data as $item)

                                        <input type="hidden" name="batch_id[]" value="{{ $item['batch_id'] }}" />
                                        <input type="hidden" name="data_rem[]" value="{{ $item['remaining'] }}" />

                                        <tr>
                                            <td>{{$loop->index+1}}.</td>
                                            <td>{{$item['batch_no']}}</td>
                                            <td>{{$item['quantity']}}</td>
                                            <td>{{$item['used']}}</td>
                                            <td>{{$item['remaining']}}</td>
                                            <td>{{$item['expiry_date']}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        <div class="modal-footer1">
                            <button class="btn p-1" id="newbtn" style="background-color: #08228a9f;color: white" type="submit">
                                    Submit
                            </button>
                            <button class="btn p-1" id="newloader" style="background-color: #08228a9f;color: white;display:none;" type="button">
                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                    Saving Data...
                            </button>
                        </div>
                        </form>
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- end row -->


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
