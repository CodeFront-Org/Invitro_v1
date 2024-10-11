@extends('layouts.adminto')

@section('content')
@if (session()->has('message'))
    <div id="toast" class="alert text-center alert-success alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ session('message') }}
    </div>
@endif
        <div class="row mt-1">
            <div class="col-12">
                <button  style="background-color: #23d3d3;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
                         <i class='fa fa-plus' aria-hidden='true'></i> New
                    </button>
                   <a href="{{route('/vendor-gallery')}}"> <button  style="background-color: #23d3d3;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
                            Gallery
                        </button></a>
                        @if ($list_type=='list')
                        <a href="{{route('/vendor-uploads',['list'=>'grid'])}}"> <button  style="background-color: #23d3d3;color: white" type="button" class="btn right">
                               <i class="mdi mdi-view-dashboard-outline"></i>  Grid view
                             </button></a>
                        @else
                        <a href="{{route('/vendor-uploads',['list'=>'list'])}}"> <button  style="background-color: #23d3d3;color: white" type="button" class="btn right">
                               <i class="fa fa-list"></i>  List view
                             </button></a>
                        @endif
           <!-- <button  style="background-color: #08228a9f;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-restock-1">
                  <i class='fas fa-filter' aria-hidden='true'></i>  Filter
                </button> -->
        </div>
    </div>
                <div class="card" style="border-radius:0px 15px 15px 15px;box-shadow: 2px 3px 3px 2px rgba(9, 107, 255, 0.179);">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table  style="font-family: 'Times New Roman', Times, serif" class="table nowrap text-center" id="datatable" class="table table-sm table-bordered dt-responsive nowrap text-center">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>images</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <!--<th>Expiry</th>-->
                                    <th>Actions</th>
                                </tr>
                                </thead>


                                <tbody>
                                    @foreach ($data as $item)                            
                                        <tr>
                                            <td>{{$loop->index+1}}.</td>
                                            <td>{{$item['name']}}</td>
                                            <td>{{$item['category']}}</td>
                                            <td>{{$item['images']}}</td>
                                            <td>{{$item['price']}}</td>
                                            <td>
                                                
                                                @if ($item['paid']==0)
                                                <a href="{{ route('pay.index', ['category' => $item['category'], 'product_id' => $item['product_id']]) }}">

                                                    <span class="text-danger">Click to Activate</span>
                                                </a>
                                                @else
                                                   <span class="text-success">Active</span>
                                                @endif
                                            
                                            
                                            </td>
                                            {{-- <!--<td>{{$item['expire']}}</td>--> --}}
                                            <td style='font-size:10px; text-align: center;'>
                                                <button type="button" style="background-color: #23d3d3;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-edit-">
                                                    <i class='fas fa-pen' aria-hidden='true'></i>
                                                    </button>
                                                <button type="button" onclick="del(this)" value="{{$item['product_id']}}" class="btn btn-danger btn-xs">
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


            <!-- Add New Product Modal -->

            <div id="con-close-modal-add-1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="addForm" action="{{route('vendors.store')}}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
                        @csrf
                        @method('post')
                        <input type="hidden" name="type" value="0">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Product</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-11w" class="form-label">Category</label>
                                        <select name="category" class="form-control form-select" id="field-11w" required onchange="updatePrice()">
                                            
                                            <option>Select Category</option>
                                            @foreach ($categories as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @foreach ($categories as $item)
                                    <input type="hidden" id="price{{$item->id}}" value="{{$item->price}}">
                                @endforeach
                                <div class="col-md-6">
                                    <div class="mb-3 mb-xl-0">
                                        <label for="inputGroupFile04" class="form-label">Category Charges</label>
                                        <input class="form-control" disabled required name="price" type="text" id="priceInput1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 mb-xl-0">
                                        <label for="inputGroupFile04" class="form-label"> Price</label>
                                        <input class="form-control" required placeholder="Set Product Price" name="product_price" type="number" id="priceInput11">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 mb-xl-0">
                                        <label for="inputGroupFile04" class="form-label"> Name</label>
                                        <input class="form-control" required placeholder="Set Product name" name="product_name" type="text" id="priceInput11">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 mb-xl-0 mt-3">
                                        <label for="inputGroupFile04" class="form-label"> Image<span class="text-danger">*</span></label>
                                        <input class="form-control" required name="product_img" type="file" id="priceInput">
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="mb-3 mb-xl-0">
                                        <label for="inputGroupFile04" class="form-label">Other Image</label>
                                        <input class="form-control" name="other_img1" type="file" id="inputGroupFile04">
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="mb-3 mb-xl-0">
                                        <label for="inputGroupFile04" class="form-label">Other Image</label>
                                        <input class="form-control" name="other_img2" type="file" id="inputGroupFile04">
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="mb-3 mb-xl-0">
                                        <label for="inputGroupFile04" class="form-label">Other Image</label>
                                        <input class="form-control" name="other_img3" type="file" id="inputGroupFile04">
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">

                                    <div class="mb-3">
                                        <label for="field-11w" class="form-label">Payment Method</label>
                                        <select name="payment_type" class="form-control form-select" id="field-11w" required>
                                                <option value="0">My Wallet</option>
                                                <option value="1">Mpesa STK</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-1">
                                    <div class="mb-3">
                                        <label for="field-2" class="form-label">Description</label>
                                        <textarea id="textarea" name="desc" class="form-control" required maxlength="3000" rows="3" placeholder="Product Description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn rounded-pill p-1" id="addbtn" style="width: 100%; background-color: #08228a9f;color: white" type="submit">
                                    Submit
                            </button>
                            <button class="btn rounded-pill p-1" id="addloader" style="width: 100%; background-color: #08228a9f;color: white;display:none;" type="button">
                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                    Saving Data...
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div><!-- /.modal -->


            @foreach ($data as $item)
            
                        <!-- Edit Product Modal -->
                            <div id="con-close-modal-edit-" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form class="editForm" action="{{route('vendors.update',$item['product_id'])}}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="type" value="0">
                                                <input type="hidden" name="prod_id" id="editProductId" value="">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Edit Product</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="field-2n" class="form-label"> Name</label>
                                                        <input type="text" value="{{$item['name']}}" name="name" class="form-control" id="field-2n" placeholder="name" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="field-2l" class="form-label">Price</label>
                                                        <input type="number" value="{{$item['price']}}" name="price" class="form-control" id="field-2l" placeholder="Price in Ksh" required>
                                                    </div>
                                                </div>
            
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="field-2" class="form-label">Description</label>
                                                        <textarea id="textarea" value="goo" name="desc" class="form-control" required maxlength="3000" rows="3" placeholder="Your Remarks">{{$item['desc']}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn rounded-pill p-1" class="editbtn" style="width: 100%; background-color: #08228a9f;color: white" type="submit">
                                                    Submit
                                            </button>
                                            <button class="btn rounded-pill p-1" class="editloader" style="width: 100%; background-color: #08228a9f;color: white;display:none;" type="button">
                                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                                    Saving Data...
                                            </button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div><!-- /.modal -->
                
            @endforeach




            <!-- Filter Product Modal

            <div id="con-close-modal-restock-1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="restockForm" method="post">
                        @csrf
                        @method('post')
                        <input type="hidden" name="type" value="1">
                        <div class="modal-header">
                            <h4 class="modal-title">Filter vendors By Category</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                    <label for="field-11w" class="form-label">Product Category</label>
                                    <select name="name" class="form-control form-select" id="field-11w" required>
                                        <option value=""></option>


                                        </select>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn rounded-pill p-1" id="restockbtn" style="width: 100%; background-color: #08228a9f;color: white" type="submit">
                                    Submit
                            </button>
                            <button class="btn rounded-pill p-1" id="restockloader" style="width: 100%; background-color: #08228a9f;color: white;display:none;" type="button">
                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                    Saving Data...
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>.modal -->


@endsection

@section('scripts')
    <script>
    $(document).ready(function(){
//Add Product Form
$("#addForm").on('submit',(e)=>{
//e.preventDefault();
var btn=$("#addbtn");
var loader=$("#addloader")
btn.hide();
loader.show();
return
let data=$("#addForm").serialize();
$.ajax({
    type: "POST",
    url: "/stock",
    data: data,
    success: function (response) { console.log(response)
 if(response==103){
        swal.fire("Error","Batch or product exist.","error"); btn.show();
        loader.hide();
        return;
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
                    toastr["success"]("", "Stock Saved Succesfully.")
        btn.show();
        loader.hide();
        location.href='/stock'
    },
    error: function(res){  console.log(res)
    if(res=='101'){

        Swal.fire("Error!", "Product Number already exists", "error");
    }else if($res==100){
        swal.fire("Error","Batch number exist.","error");
    }
    else{
        Swal.fire("Error!", "Try again later...", "error");}
        btn.show();
        loader.hide();
    }
});
})

//Add Re-stock Form
$("#restockForm").on('submit',(e)=>{
e.preventDefault();
var btn=$("#restockbtn");
var loader=$("#restockloader")
btn.hide();
loader.show();
let data=$("#restockForm").serialize();
$.ajax({
    type: "POST",
    url: "/vendors",
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
                    toastr["success"]("", "Status Saved Succesfully.")
        location.href='/vendors'
    },
    error: function(res){ console.log(res)
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Try again later...", "error");
    }
});
})

// Edit Product Form
$(".editForm").on('submit', function(e) {
  //e.preventDefault();
  const form = $(this);
  var itemId = form.find('input[name="editProductId"]').val();
  var btn = $("#editbtn" + itemId);
  var loader = $("#editloader" + itemId);
  btn.hide();
  loader.show();
    return
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
                    url: "vendors/"+id,
                    data:{
                        _token:"{{csrf_token()}}", id,type
                    },
                    success: function (response) { console.log(response)

                        Swal.fire("Deleted", "Successfully.", "success").then(()=>{
                        location.href='/vendor-uploads'})
                    },
                    error: function(res){console.log(res)
                        Swal.fire("Error!", "Try again later...", "error");
                    }
                });
            }
            })
        }
    </script>



<script>
    function updatePrice() {
        // Get the selected category value
        var categoryId = document.getElementById("field-11w").value;
        var priceID="price"+categoryId;
        var price=document.getElementById(priceID).value

        // Update the price input field
        var priceInput = document.getElementById("priceInput1");
        priceInput.value = price;
    }
</script>

@endsection