@extends('layouts.app')

@section('content')
    @if (session()->has('error'))
        <div id="toast" class="alert text-center alert-danger alert-dismissible w-100 fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ session('error') }}
        </div>
    @endif

    <!-- Executive Summary Dashboard -->
    <div class="row mt-3 mb-2">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold text-dark" style="font-family: 'Outfit', sans-serif;">
                <i class="mdi mdi-chart-pie me-2 text-primary"></i>Stock Value Analysis
            </h4>
            <a href="{{ route('landing.cost.export', request()->query()) }}" class="btn btn-success px-3 shadow-sm rounded-pill btn-export-excel">
                <i class="mdi mdi-file-excel me-1"></i> Export to Excel
            </a>
        </div>
    </div>

    <!-- Executive KPI Cards -->
    <div class="row mb-4 mt-2">
        <!-- Card 1: Total Stock Value -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 kpi-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; color: white;">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-white-50 text-uppercase fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Total Stock Value</h6>
                        <h3 class="fw-bold mb-0 text-white" style="font-size: 1.6rem;">Ksh {{ number_format($totalValue, 2) }}</h3>
                    </div>
                    <div class="ms-3">
                        <div class="avatar-title rounded-circle" style="width: 48px; height: 48px; background: rgba(255,255,255,0.18); display: flex; align-items: center; justify-content: center;">
                            <i class="mdi mdi-currency-usd text-white" style="font-size: 1.6rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Items In Stock -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 kpi-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 16px; color: white;">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-white-50 text-uppercase fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Items in Stock</h6>
                        <h3 class="fw-bold mb-0 text-white" style="font-size: 1.6rem;">{{ number_format($totalQty) }} units</h3>
                    </div>
                    <div class="ms-3">
                        <div class="avatar-title rounded-circle" style="width: 48px; height: 48px; background: rgba(255,255,255,0.18); display: flex; align-items: center; justify-content: center;">
                            <i class="mdi mdi-package-variant text-white" style="font-size: 1.6rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card & Table Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-4">
            <!-- Sleek Filter Form -->
            <form method="GET" action="{{ route('landing.cost') }}" class="row g-3 mb-4 pb-4 border-bottom align-items-end">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold text-muted mb-1" style="font-size: 0.85rem;">Product Name</label>
                    <input type="text" name="product_name" class="form-control rounded-3" placeholder="Search by name..."
                        value="{{ request('product_name') }}">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-semibold text-muted mb-1" style="font-size: 0.85rem;">Batch No</label>
                    <input type="text" name="batch_no" class="form-control rounded-3" placeholder="Batch number..."
                        value="{{ request('batch_no') }}">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-semibold text-muted mb-1" style="font-size: 0.85rem;">From Date</label>
                    <input type="date" name="start_date" class="form-control rounded-3" value="{{ request('start_date') }}">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-semibold text-muted mb-1" style="font-size: 0.85rem;">To Date</label>
                    <input type="date" name="end_date" class="form-control rounded-3" value="{{ request('end_date') }}">
                </div>
                <div class="col-lg-3 col-md-12">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary px-3 rounded-start-3 btn-filter">
                            <i class="mdi mdi-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('landing.cost') }}" class="btn btn-secondary px-3 rounded-end-3 btn-reset">
                            <i class="mdi mdi-refresh me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <!-- Table Section -->
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center mb-0" id="stockvaluetable" style="font-family: 'Inter', sans-serif;">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th class="py-3" style="border-radius: 8px 0 0 0; border: none;">#</th>
                            <th class="py-3" style="border: none;">Product Name</th>
                            <th class="py-3" style="border: none;">Product ID</th>
                            <th class="py-3" style="border: none;">Batch No</th>
                            <th class="py-3" style="border: none;">Quantity Available</th>
                            <th class="py-3" style="border: none;">Landing Cost</th>
                            <th class="py-3" style="border: none;">Stock Value</th>
                            <th class="py-3" style="border: none;">Date Created</th>
                            <th class="py-3" style="border-radius: 0 8px 0 0; border: none;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($batches as $item)
                            <tr class="stock-row" style="transition: all 0.3s ease;">
                                <td><span class="text-muted fw-semibold">#{{ $item->id }}</span></td>
                                <td class="text-start fw-semibold" style="color: #08228a; padding-left: 20px;">{{ $item->name }}</td>
                                <td><span class="badge bg-light text-dark font-size-12 px-2 py-1">{{ $item->product_id }}</span></td>
                                <td><code class="text-secondary fw-semibold">{{ $item->batch_no }}</code></td>
                                <td>
                                    <span class="badge bg-info px-2.5 py-1.5" style="font-size: 0.85rem; font-weight: 600;">
                                        {{ number_format($item->quantity) }}
                                    </span>
                                </td>
                                <td class="fw-semibold text-success">Ksh {{ number_format($item->landing_cost, 2) }}</td>
                                <td class="fw-bold" style="color: #fd7e14; font-size: 0.95rem;">Ksh {{ number_format($item->stock_value, 2) }}</td>
                                <td>
                                    <small class="text-muted">
                                        <i class="mdi mdi-calendar-outline me-1"></i>{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}
                                    </small>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-circle" style="width: 32px; height: 32px; padding: 0;" data-bs-toggle="modal"
                                        data-bs-target="#editLandingCostModal-{{ $item->id }}" title="Edit Landing Cost">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="mdi mdi-information-outline text-muted" style="font-size: 2.2rem;"></i>
                                    <p class="mt-2 text-muted mb-0">No stock records found matching filters.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-4">
                {{ $batches->links('vendor.pagination.simple-bootstrap-4') }}
            </div>

        </div>
    </div>

    <!-- Edit Landing Cost Modals -->
    @foreach ($batches as $item)
        <div id="editLandingCostModal-{{ $item->id }}" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="editLandingCostLabel-{{ $item->id }}" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                    <form class="editLandingCostForm" data-batch-id="{{ $item->id }}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header bg-light" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                            <h5 class="modal-title fw-bold text-dark">Edit Landing Cost</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold text-muted">Product</label>
                                    <input type="text" class="form-control rounded-3 bg-light" value="{{ $item->name }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">Batch No</label>
                                    <input type="text" class="form-control rounded-3 bg-light" value="{{ $item->batch_no }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">Quantity</label>
                                    <input type="text" class="form-control rounded-3 bg-light" value="{{ $item->quantity }}" disabled>
                                </div>
                                <div class="col-md-12 pt-2">
                                    <label for="landing_cost_{{ $item->id }}" class="form-label fw-semibold text-dark">Landing Cost (Ksh)</label>
                                    <div class="input-group">
                                        <span class="input-group-text rounded-start-3 bg-light">Ksh</span>
                                        <input type="number" step="0.01" min="0" name="landing_cost"
                                            class="form-control rounded-end-3" id="landing_cost_{{ $item->id }}"
                                            value="{{ $item->landing_cost }}" placeholder="0.00" required>
                                    </div>
                                    <small class="text-muted mt-1 d-block"><i class="mdi mdi-information-outline me-1"></i>Updating this value will also recalculate the restock ledger.</small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-3 bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                            <button class="btn btn-primary w-100 rounded-3 py-2 fw-semibold" id="editLandingCostBtn-{{ $item->id }}" type="submit">
                                <i class="mdi mdi-check-circle me-1"></i> Save Changes
                            </button>
                            <button class="btn btn-secondary w-100 rounded-3 py-2 fw-semibold" id="editLandingCostLoader-{{ $item->id }}"
                                style="display:none;" type="button">
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                Saving Data...
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Custom Executive Styling -->
    <style>
        .kpi-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
        }
        .stock-row:hover {
            background-color: rgba(102, 126, 234, 0.05) !important;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
        }
        .btn-primary.btn-filter {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary.btn-filter:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.25);
        }
        .btn-success.btn-export-excel {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-success.btn-export-excel:hover {
            background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(17, 153, 142, 0.25);
        }
    </style>
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
                            toastr["success"]("", "Landing Cost Updated Successfully.");
                            btn.show();
                            loader.hide();
                            setTimeout(() => { location.reload(); }, 800);
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