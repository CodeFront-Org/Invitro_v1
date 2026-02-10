@extends('layouts.app')

@section('content')
    @if (session()->has('error'))
        <div id="toast" class="alert text-center alert-danger alert-dismissible w-100 fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ session('error') }}
        </div>
    @endif
    <div class="row mt-3 mb-3">
        <div class="col-12 d-flex justify-content-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
                <i class="mdi mdi-plus-circle me-1"></i> Add Customer
            </button>
        </div>
    </div>
    <div class="card shadow-md">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center" id="datatable">
                    <thead>
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
                                <td>{{$loop->index + 1}}. </td>
                                <td>{{$item['first_name']}}</td>
                                <td>{{$item['contacts']}}</td>
                                <td>{{$item['email']}}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#con-close-modal-edit-{{$item['id']}}" title="Edit Customer">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                        <button type="button" onclick="del(this)" value="{{$item['id']}}" class="btn btn-danger"
                                            title="Delete Customer">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </div>
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

    <div id="con-close-modal-add-1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
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
                                    <input type="text" name="name" class="form-control" id="field-2n" placeholder="name"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="field-2c" class="form-label">Contacts</label>
                                    <input type="text" name="contacts" class="form-control" id="field-2c"
                                        placeholder="contacts" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="field-2e" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="field-2e" placeholder="email"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary w-100" id="addbtn" type="submit">
                            <i class="mdi mdi-check-circle me-1"></i> Submit
                        </button>
                        <button class="btn btn-secondary w-100" id="addloader" style="display:none;" type="button">
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Saving Data...
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.modal -->


    <!-- Edit Customer Modal -->
    @foreach ($data as $item)
        <div id="con-close-modal-edit-{{$item['id']}}" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form class="editForm" method="post">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="type" value="1">
                        <input type="hidden" name="editformId" id="editformId" value="{{$item['id']}}">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Customer</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="field-2ne" class="form-label">Customer/Company Name</label>
                                        <input type="text" value="{{$item['first_name']}}" name="f_name" class="form-control"
                                            id="field-2ne" placeholder="first name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2ce" class="form-label">Contacts</label>
                                        <input type="text" value="{{$item['contacts']}}" name="contacts" class="form-control"
                                            id="field-2ce" placeholder="contacts" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2ee" class="form-label">Email</label>
                                        <input type="email" value="{{$item['email']}}" name="email" class="form-control"
                                            id="field-2ee" placeholder="email" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary w-100" id="editbtn{{$item['id']}}" type="submit">
                                <i class="mdi mdi-check-circle me-1"></i> Submit
                            </button>
                            <button class="btn btn-secondary w-100" id="editloader{{$item['id']}}" style="display:none;"
                                type="button">
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
        $(document).ready(function () {
            //Add settings Form
            $("#addForm").on('submit', (e) => {
                e.preventDefault();
                var btn = $("#addbtn");
                var loader = $("#addloader")
                btn.hide();
                loader.show();
                let data = $("#addForm").serialize();
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
                        location.href = '/customers'
                    },
                    error: function (res) {
                        btn.show();
                        loader.hide();
                        Swal.fire("Error!", "Try again later...", "error");
                    }
                });
            })

            // Edit settings Form
            $(".editForm").on('submit', function (e) {
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
                    success: function (response) {
                        console.log(response)

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
                        toastr["success"]("", "Customer Updated Succesfully.")
                        location.href = '/customers'
                    },
                    error: function (res) {
                        console.log(res)
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
        function del(e) {
            let id = e.value;
            var type = 0;//For knowing deletion operation is coming from settings

            Swal.fire({
                title: "Confirm deletion",
                text: "You won't be able to revert this!",
                type: "error",
                showCancelButton: !0,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((t) => {
                if (t.value) {
                    $.ajax({
                        type: "DELETE",
                        url: "users/" + id,
                        data: {
                            _token: "{{csrf_token()}}", id, type
                        },
                        success: function (response) {
                            console.log(response)

                            Swal.fire("Deleted", "Successfully.", "success").then(() => {
                                location.href = '/customers'
                            })
                        },
                        error: function (res) {
                            console.log(res)
                            Swal.fire("Error!", "Try again later...", "error");
                        }
                    });
                }
            })
        }
    </script>
@endsection