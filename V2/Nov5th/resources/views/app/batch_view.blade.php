@extends('layouts.app')

@section('content')
@if (session()->has('error'))
    <div id="toast" class="alert text-center alert-danger alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ session('error') }}
    </div>
@endif
        <div class="row mt-1">
            <div class="col-12">
         
                <div class="card" style="border-radius:0px 15px 15px 15px;box-shadow: 2px 3px 3px 2px rgba(9, 107, 255, 0.179);">
                    <div class="card-body">
                        <div class="table-responsive">
                            
                            <form action='/batch-view' >
                                    @csrf
                               

                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name='item_search'  style='border-radius: 1rem;' class="form-control form-rounded" placeholder="Search..." >
                               

                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" style="border-radius: 1rem; background-color: #08228a9f;color: white" class="btn btn-xs" >
                                            <i class='fas fa-search h5 text-white' aria-hidden='true'></i> Search
                                        </button>

                                    </div>
                                  </div>


                            </form>

                            <br> <br>
                            <table  style="font-family: 'Times New Roman', Times, serif" class="table table-bordered nowrap text-center" id="datatable" class="table table-sm table-bordered dt-responsive nowrap text-center">
                                <thead class="table-light">

                                    @php
                                        $page=$page_number;
                                    @endphp
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Batch no</th> 
                                    <th>Stocked</th> 
                                    <th>Sold</th> 
                                    <th>Balance</th>
                                    <th >Expiry Date</th>
                
                                </tr>
                                </thead>


                                <tbody>
                                @foreach ($stock_array as $item)
                                            <tr>
                                            <td>{{$item['id']}} </td>
                                            <td>{{$item['product_name']}}</td>
                                            <td>{{$item['batch_no']}}</td>
                                            <td>{{$item['Stocked']}} </td> 
                                            <td>{{$item['sold']}}</td>
                                            <td>{{$item['Balance']}}</td>
                                            <td>{{$item['expiry_date']}}</td>
                                          
                                     
                                            
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
                               
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- end row -->

        


@endsection

@section('scripts')
    <script>
    $(document).ready(function(){
//Add Stock Form
$("#addForm").on('submit',(e)=>{
e.preventDefault();
var btn=$("#addbtn");
var loader=$("#addloader")
btn.hide();
loader.show();
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
                    toastr["success"]("", "Product Saved Succesfully. Waiting Admin Approval")
        btn.show();
        loader.hide();
        location.href='/stock'
    },
    error: function(res){  console.log(res)
    if(res=='101'){

        Swal.fire("Error!", "Product already exists", "error");
    }else if(res==100){
        swal.fire("Error","Product exist.","error");
    }
    else{
        Swal.fire("Error!", "Product exist.", "error");}
        btn.show();
        loader.hide();
    }
});
})






// Edit changeExpiryDate 
$(".changeExpiryDate").on('submit', function(e) {
  e.preventDefault();
  alert('very nice!')
  const form = $(this);
  var itemId = form.find('input[name="changeExpiryDate"]').val();
  var btn = $("#changeExpiryDate" + itemId);
  var loader = $("#changeExpiryDate" + itemId);
/*
  btn.hide();
 */

 loader.show();


x=prompt(1,form.serialize());
 


$.post("save_ExpiryDate",form.serialize(), function(data, status){
    alert("Data: " + data );
    alert("Status: " + status);

    if(data==200){
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
            toastr["success"]("", "Data Updated Succesfully.")
            location.href='/batch-edit'
                btn.show();
                loader.hide();
        }else{
                btn.show();
                loader.hide();
                Swal.fire("Error!", "Try again later..." +  response , "error");
    }


  });


  /* 
    $.ajax({
        type: 'POST',
        url: '/save_ExpiryDate',
        data: form.serialize(),
        success: function (response) { 
            alert('2222220000');
            console.log(response);alert('1000');
            if(response==200){
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
                                            toastr["success"]("", "Data Updated Succesfully.")
                                            location.href='/batch-edit'
                                                btn.show();
                                                loader.hide();
                                        }else{
                                                btn.show();
                                                loader.hide();
                                                Swal.fire("Error!", "Try again later..." +  response , "error");
                                        }
        },
        error: function(res){ console.log(res)
            btn.show();
            loader.hide();
            Swal.fire("Error!", "Try again later...", "error");
        }
    });
*/

})
















// Edit OrderLevel Form
$(".orderlevelForm").on('submit', function(e) {
  e.preventDefault();

  const form = $(this);
  var itemId = form.find('input[name="editorderId"]').val();
  var btn = $("#editorderbtn" + itemId);
  var loader = $("#editorderloader" + itemId);
  btn.hide();
  loader.show();
  let data = form.serialize();
$.ajax({
    type: 'PATCH',
    url: '/stock/' + itemId,
    data: data,
    success: function (response) { console.log(response)
if(response==200){
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
    toastr["success"]("", "Data Updated Succesfully.")
    location.href='/stock'
        btn.show();
        loader.hide();
}else{
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Try again later...", "error");
}
    },
    error: function(res){ console.log(res)
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Try again later...", "error");
    }
});
})


// Edit Audit Form
$(".editaudit1").on('submit', function(e) {
  e.preventDefault();

  const form = $(this);
  var itemId = form.find('input[name="editorderId"]').val();
  var btn = $("#editauditbtn" + itemId);
  var loader = $("#editauditloader" + itemId);
  btn.hide();
  loader.show();
  let data = form.serialize();
$.ajax({
    type: 'PATCH',
    url: '/stock/' + itemId,
    data: data,
    success: function (response) {
console.log(response)
if(response==200){
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
    toastr["success"]("", "Data Updated Succesfully.")
    location.href='/stock'
        btn.show();
        loader.hide();
}else{
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Try again later...", "error");
}
    },
    error: function(res){ console.log(res)
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Try again later...", "error");
    }
});
})



// Edit Stocks Form
$(".editstockForm").on('submit', function(e) {
  e.preventDefault();

  const form = $(this);
  var itemId = form.find('input[name="editstockId"]').val();
  var btn = $("#editstockbtn" + itemId);
  var loader = $("#editstockloader" + itemId);
  btn.hide();
  loader.show();
  let data = form.serialize();
$.ajax({
    type: 'PATCH',
    url: '/stock/' + itemId,
    data: data,
    success: function (response) { console.log(response)
if(response==200){
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
    toastr["success"]("", "Data Updated Succesfully.")
    location.href='/stock'
        btn.show();
        loader.hide();
}if(response==501){
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Batch Number should be Unique", "error");
    }
    },
    error: function(res){ console.log(res)
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Try again later...", "error");
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
    url: "/stock", 
    data: data,
    success: function (response) { console.log(response)

        if(response==404){
            btn.show();
            loader.hide();
            Swal.fire("Error!", "Product Does not exists", "error");
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
                    toastr["success"]("", "Stock Saved Succesfully.")
        location.href='/stock'
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



    // Edit Stocks Transaction
$(".stockTrx").on('submit', function(e) {
  e.preventDefault();
alert("Hey");return
  const form = $(this);
  var itemId = form.find('input[name="editstockId"]').val();
  var btn = $("#editstockbtn" + itemId);
  var loader = $("#editstockloader" + itemId);
  btn.hide();
  loader.show();
  let data = form.serialize();
$.ajax({
    type: 'PATCH',
    url: '/stock/' + itemId,
    data: data,
    success: function (response) { console.log(response)
if(response==200){
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
    toastr["success"]("", "Data Updated Succesfully.")
    location.href='/stock'
        btn.show();
        loader.hide();
}if(response==501){
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Batch Number should be Unique", "error");
    }
    },
    error: function(res){ console.log(res)
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Try again later...", "error");
    }
});
})




    </script>

    <script>

        //Deleting Product
        function del_product(e){
        let id=e.value;
        var type=0;//For knowing deletion operation is coming from settings

        Swal.fire({
            title: "Confirm deletion",
            text: "All batches linked to this product will also be deleted. You won't be able to revert this!",
            type: "error",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((t)=>{
        if(t.value){
                $.ajax({
                    type: "DELETE",
                    url: "stock/"+id,
                    data:{
                        _token:"{{csrf_token()}}", id,type
                    },
                    success: function (response) { //alert(response)

                        Swal.fire("Deleted", "Successfully.", "success").then(()=>{
                        location.href='/stock'})
                    },
                    error: function(res){console.log(res)
                        Swal.fire("Error!", "Try again later...", "error");
                    }
                });
            }
            })
        }


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
    
    
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    $("#excelbtn").click(function(){
        TableToExcel.convert(document.getElementById("salestable"), {
            name: "Invitro Products without batches.xlsx",
            sheet: {
            name: "Sheet1"
            }
        });
        });
});
</script>
@endsection
