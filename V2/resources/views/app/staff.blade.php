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
            <button  style="background-color: #08228a9f;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
                  <i class='fa fa-plus' aria-hidden='true'></i>   Add Staff
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
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>


                                <tbody>
                                @foreach($data as $item)
                                @if ($item['id']!==Auth::id())
                                    <tr>
                                        <td>{{($loop->index)+1}} </td>
                                        <td>{{$item['first_name']}} {{$item['last_name']}}</td>
                                        <td>{{$item['contacts']}}</td>
                                        <td>{{$item['email']}}</td>
                                        @if ($item['role_type']==1)
                                            <td>Admin</td>
                                        @endif
                                        @if ($item['role_type']==2)
                                            <td>Store Keeper</td>
                                        @endif
                                        @if ($item['role_type']==3)
                                            <td>Stock Card</td>
                                        @endif
                                        @if ($item['role_type']==4)
                                            <td>Staff</td>
                                        @endif
                                        <td style='font-size:10px; text-align: center;'>
                                            <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-edit-{{$item['id']}}">
                                                <i class='fas fa-pen' aria-hidden='true'></i>
                                                </button>
                                            <button type="button" onclick="del(this)" value="{{$item['id']}}" class="btn btn-danger btn-xs">
                                                <i class='fa fa-trash' aria-hidden='true'></i>
                                            </button>

                                        </td>
                                    </tr>
                                @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- end row -->


            <!-- Add New Staff Modal -->

            <div id="con-close-modal-add-1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="staffForm" method="post">
                        @csrf
                        @method('post')
                        <input type="hidden" name="type" value="0">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Staff</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2n" class="form-label">First Name</label>
                                        <input type="text" name="f_name" class="form-control" id="field-2n" placeholder="first name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Last Name</label>
                                        <input type="text" name="l_name" class="form-control" id="field-2l" placeholder="last name" required>
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
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="field-11" class="form-label">Role</label>
                                    <select name="role" class="form-control form-select" id="field-11" required>
                                                <option value="0">Admin</option>
                                                <option value="1">Store Keeper</option>
                                                <option value="2">Stock Card</option>
                                        </select>
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


            <!-- Edit Staff Modal -->
@foreach ($data as $item)
    <div id="con-close-modal-edit-{{$item['id']}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form class="editForm" method="post">
                @csrf
                @method('PATCH')
                <input type="hidden" name="type" value="0">
                        <input type="hidden" name="editformId" id="editformId" value="{{$item['id']}}">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Staff</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="field-2ne" class="form-label">First Name</label>
                                <input type="text" value="{{$item['first_name']}}" name="f_name" class="form-control" id="field-2ne" placeholder="first name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="field-2le" class="form-label">Last Name</label>
                                <input type="text" value="{{$item['last_name']}}" name="l_name" class="form-control" id="field-2le" placeholder="last name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="field-2ce" class="form-label">Contacts</label>
                                <input type="text" value="{{$item['contacts']}}" name="contacts" class="form-control" id="field-2ce" placeholder="contacts" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="field-2ee" class="form-label">Email</label>
                                <input type="email" value="{{$item['email']}}" name="email" class="form-control" id="field-2ee" placeholder="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="field-11" class="form-label">Role</label>
                            <select name="role" class="form-control form-select" id="field-11" required>
                                        <option {{$item['role_type']==1?'selected':''}} value="0">Admin</option>
                                        <option {{$item['role_type']==2?'selected':''}} value="1">Store Keeper</option>
                                        <option {{$item['role_type']==3?'selected':''}} value="2">Stock Card</option>
                                </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn rounded-pill p-1" id="editbtn{{$item['id']}}" style="width: 100%; background-color: #08228a9f;color: white" type="submit">
                            Submit
                    </button>
                    <button class="btn rounded-pill p-1" id="editloader{{$item['id']}}" style="width: 100%; background-color: #08228a9f;color: white;display:none;" type="button">
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Saving Data...
                    </button>
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
//Add settiStaffngs Form
$("#staffForm").on('submit',(e)=>{
e.preventDefault();
var btn=$("#addbtn");
var loader=$("#addloader")
btn.hide();
loader.show();
let data=$("#staffForm").serialize();
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
                    toastr["success"]("", "Data Saved Succesfully.")
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
$(".editForm").on('submit', function(e) {
  e.preventDefault();

  const form = $(this);
  var itemId = form.find('input[name="editformId"]').val();
  var btn = $("#editbtn" + itemId);
  var loader = $("#editloader" + itemId);
  btn.hide();
  loader.show();
  let data = form.serialize();
$.ajax({
    type: 'PATCH',
    url: '/users/' + itemId,
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
                    toastr["success"]("", "Data Updated Succesfully.")
        location.href='/users'
    },
    error: function(res){ console.log(res)
        btn.show();
        loader.hide();
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
                    url: "users/"+id,
                    data:{
                        _token:"{{csrf_token()}}", id,type
                    },
                    success: function (response) { console.log(response)

                        Swal.fire("Deleted", "Successfully.", "success").then(()=>{
                        location.href='/users'})
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
