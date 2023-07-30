@extends('layouts.app')

@section('content')
        <div class="row mt-1">
            <div class="col-12">
            <button  style="background-color: #08228a9f;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
                  <i class='fa fa-plus' aria-hidden='true'></i>   Add Customer
                </button>
                <div class="card" style="border-radius:0px 15px 15px 15px;box-shadow: 2px 3px 3px 2px rgba(9, 107, 255, 0.179);">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-sm table-bordered dt-responsive nowrap text-center">
                                <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>


                                <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$item['first_name']}}</td>
                                        <td>{{$item['contacts']}}</td>
                                        <td>{{$item['email']}}</td>
                                        <td style='font-size:10px; text-align: center;'>
                                            <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-edit-{{$item['id']}}">
                                                <i class='fas fa-pen' aria-hidden='true'></i>
                                                </button>
                                            <button type="button" onclick="del(this)" value="{{$item['id']}}" class="btn btn-danger btn-xs">
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


            <!-- Add New Customer Modal -->

            <div id="con-close-modal-add-1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="addForm" method="post">
                        @csrf
                        @method('post')
                        <input type="hidden" name="type" value="1">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Customer</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="field-2n" class="form-label">Customer/Company Name</label>
                                        <input type="text" name="name" class="form-control" id="field-2n" placeholder="name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2c" class="form-label">Contacts</label>
                                        <input type="text" name="contacts" class="form-control" id="field-2c" placeholder="contacts" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2e" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" id="field-2e" placeholder="email" required>
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


            <!-- Edit Customer Modal -->

            <div id="con-close-modal-edit-1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="addForm" method="post">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="type" value="1">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Customer</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="field-2ne" class="form-label">Customer/Company Name</label>
                                        <input type="text" value="Martin" name="f_name" class="form-control" id="field-2ne" placeholder="first name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2ce" class="form-label">Contacts</label>
                                        <input type="text" value="0797965680" name="contacts" class="form-control" id="field-2ce" placeholder="contacts" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2ee" class="form-label">Email</label>
                                        <input type="email" value="martin@gmail.com" name="email" class="form-control" id="field-2ee" placeholder="email" required>
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




@endsection

@section('scripts')
    <script>
    $(document).ready(function(){
//Add settings Form
$("#addForm").on('submit',(e)=>{
e.preventDefault();
var btn=$("#addbtn");
var loader=$("#addloader")
btn.hide();
loader.show();
let data=$("#addForm").serialize();
$.ajax({
    type: "POST",
    url: "/users",
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
                    toastr["success"]("", "Customer Saved Succesfully.")
        location.href='/users'
    },
    error: function(res){
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
