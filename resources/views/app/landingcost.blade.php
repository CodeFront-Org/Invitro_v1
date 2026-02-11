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
            <a href="{{ route('landing.cost.export', request()->query()) }}" class="btn btn-success">
                <i class="mdi mdi-file-excel me-1"></i> Export to Excel
            </a>
        </div>
    </div>
    <div class="card shadow-md">
        <div class="card-body">


            <form method="GET" action="{{ route('landing.cost') }}" class="row g-3 mb-4 pb-3 border-bottom">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Product Name</label>
                    <input type="text" name="product_name" class="form-control" placeholder="Product Name"
                        value="{{ request('product_name') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Batch No</label>
                    <input type="text" name="batch_no" class="form-control" placeholder="Batch No"
                        value="{{ request('batch_no') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">From Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">To Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3 align-self-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('landing.cost') }}" class="btn btn-secondary">
                            <i class="mdi mdi-refresh me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Product ID</th>
                            <th>Batch No</th>
                            <th>Quantity</th>
                            <th>Landing Cost</th>
                            <th>Stock Value</th>
                            <th>Origin</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($batches as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->product_id }}</td>
                                <td>{{ $item->batch_no }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Ksh {{ number_format($item->landing_cost, 2) }}</td>
                                <td><strong>Ksh {{ number_format($item->stock_value, 2) }}</strong></td>
                                <td>{{ $item->origin }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editLandingCostModal-{{ $item->id }}" title="Edit Landing Cost">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $batches->links('vendor.pagination.simple-bootstrap-4') }}
            </div>

        </div>
    </div>

    <!-- Edit Landing Cost Modals -->
    @foreach ($batches as $item)
        <div id="editLandingCostModal-{{ $item->id }}" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="editLandingCostLabel-{{ $item->id }}" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form class="editLandingCostForm" data-batch-id="{{ $item->id }}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Landing Cost - {{ $item->name }} ({{ $item->batch_no }})</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Product</label>
                                        <input type="text" class="form-control" value="{{ $item->name }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Batch No</label>
                                        <input type="text" class="form-control" value="{{ $item->batch_no }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Quantity</label>
                                        <input type="text" class="form-control" value="{{ $item->quantity }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="landing_cost_{{ $item->id }}" class="form-label fw-bold">Landing Cost (Ksh)</label>
                                        <input type="number" step="0.01" min="0" name="landing_cost"
                                            class="form-control" id="landing_cost_{{ $item->id }}"
                                            value="{{ $item->landing_cost }}" placeholder="Enter landing cost" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary w-100" id="editLandingCostBtn-{{ $item->id }}" type="submit">
                                <i class="mdi mdi-check-circle me-1"></i> Update Landing Cost
                            </button>
                            <button class="btn btn-secondary w-100" id="editLandingCostLoader-{{ $item->id }}"
                                style="display:none;" type="button">
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                Saving Data...
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.modal -->
    @endforeach

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Edit Landing Cost Form
            $(".editLandingCostForm").on('submit', function (e) {
                e.preventDefault();

                const form = $(this);
                var batchId = form.data('batch-id');
                var btn = $("#editLandingCostBtn-" + batchId);
                var loader = $("#editLandingCostLoader-" + batchId);
                btn.hide();
                loader.show();

                let data = form.serialize();

                $.ajax({
                    type: 'PATCH',
                    url: '/landingCost/' + batchId,
                    data: data,
                    success: function (response) {
                        if (response.status === 'success') {
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
                            };
                            toastr["success"]("", "Landing Cost Updated Successfully.");
                            btn.show();
                            loader.hide();
                            location.reload();
                        } else {
                            btn.show();
                            loader.hide();
                            Swal.fire("Error!", response.message || "Try again later...", "error");
                        }
                    },
                    error: function (res) {
                        console.log(res);
                        btn.show();
                        loader.hide();
                        Swal.fire("Error!", "Try again later...", "error");
                    }
                });
            });
        });
    </script>
@endsection