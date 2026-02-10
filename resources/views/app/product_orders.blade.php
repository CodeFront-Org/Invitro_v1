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


    <div class="row mt-3 mb-3">
        <div class="col-12 d-flex justify-content-end">
            <button id="excelbtn" type="button" class="btn btn-success">
                <i class="mdi mdi-file-excel me-1"></i> Export to Excel
            </button>
        </div>
    </div>
    <div class="card shadow-md">
        <div class="card-body">
            <form method="GET" action="{{ route('order.index') }}" class="row g-3 mb-4 pb-3 border-bottom">
                <div class="col-md-3">
                    <label for="from" class="form-label fw-bold">From Date</label>
                    <input type="date" name="from" id="from" value="{{ request('from') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="to" class="form-label fw-bold">To Date</label>
                    <input type="date" name="to" id="to" value="{{ request('to') }}" class="form-control">
                </div>
                <div class="col-md-4 align-self-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('order.index', ['product_id' => request('product_id')]) }}"
                            class="btn btn-secondary">
                            <i class="mdi mdi-refresh me-1"></i> Reset
                        </a>
                    </div>
                </div>
                <input type="hidden" name="product_id" value="{{ request('product_id') }}">
            </form>




            <div class="table-responsive">
                <table class="table table-hover align-middle text-center" id="salestable">
                    <thead>

                        @php
                            $page = 1;
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
                                <td class="text-left"
                                    style="min-width: 110px; max-width: 110px; overflow: hidden; font-size: 12px;">
                                    {{ $item->remarks }}
                                </td>
                                <td>
                                    @if ($item->approve == 0)
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="badge bg-success">Approved</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                            data-bs-target="#con-close-modal-txn-{{$item->id}}" title="Order Breakdown">
                                            <i class="mdi mdi-history"></i>
                                        </button>
                                        @role('admin')
                                        <button type="button" onclick="del(this)" value="{{$item['order_id']}}"
                                            class="btn btn-danger" title="Delete Order">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                            @php
                                $page += 1;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination links -->
            <div class="d-flex justify-content-center mt-4">
                {{ $data1->appends(request()->except('page'))->links('vendor.pagination.simple-bootstrap-4')}}
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
    @foreach ($data1 as $item1)
        <div id="con-close-modal-txn-{{$item1['id']}}" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
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
                                <table class="table table-hover align-middle text-center" id="datatable">
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
            $("#excelbtn").click(function () {
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
        $(document).ready(function () {
            //Add newOrderForm
            $("#newOrderForm").on('submit', (e) => {
                e.preventDefault();
                var btn = $("#newbtn");
                var loader = $("#newloader")
                btn.hide();
                loader.show();
                let data = $("#newOrderForm").serialize();
                $.ajax({
                    type: "POST",
                    url: "/order",
                    data: data,
                    success: function (response) {
                        console.log(response)
                        if (response == 504) {
                            btn.show();
                            loader.hide();
                            Swal.fire("Error!", "Product Does not exists", "error");
                            return
                        }
                        if (response.status === 404) {//Means quantity exceeded
                            btn.show();
                            loader.hide();
                            Swal.fire("Quantity Exceeded!", "Available Qty is: " + response.quantity, "error");
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

                        location.href = 'order'

                    },
                    error: function (res) {
                        console.log(res)
                        btn.show();
                        loader.hide();
                        Swal.fire("Error!", "Try again later...", "error");
                    }
                });
            })

            // Edit settings Form
            $(".settiingsEditForm").on('submit', function (e) {
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
                        toastr["success"]("", "Settings Update Succesfully.")
                        location.href = '#'
                    },
                    error: function (res) {
                        console.log(res)
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
                        url: "order/" + id,
                        data: {
                            _token: "{{csrf_token()}}", id, type
                        },
                        success: function (response) {
                            console.log(response)

                            Swal.fire("Deleted", "Successfully.", "success").then(() => {
                                location.href = '/order'
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