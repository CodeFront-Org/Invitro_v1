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
    </div>
@endsection