@extends('layouts.app')

@section('content')
@if (session()->has('error'))
    <div id="toast" class="alert text-center alert-danger alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ session('error') }}
    </div>
@endif

<div class="row mt-1">
    
    <div class="card" style="border-radius:0px 15px 15px 15px;box-shadow: 2px 3px 3px 2px rgba(9, 107, 255, 0.179);">
        <div class="card-body">
            <a href="{{ route('landing.cost.export', request()->query()) }}" class="btn btn-success mb-3">
    Export to Excel
</a>


            <!-- Filter Form -->
            <form method="GET" action="{{ route('landing.cost') }}" class="row g-3 mb-3">
                <div class="col-md-3">
                    <input type="text" name="product_name" class="form-control" placeholder="Product Name" value="{{ request('product_name') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="batch_no" class="form-control" placeholder="Batch No" value="{{ request('batch_no') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="product_id" class="form-control" placeholder="Product ID" value="{{ request('product_id') }}">
                </div>
                <div class="col-md-3 d-flex">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('landing.cost') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered nowrap text-center" style="font-family: 'Times New Roman', Times, serif">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Product ID</th>
                            <th>Batch No</th>
                            <th>Quantity</th>
                            <th>Landing Cost</th>
                            <th>Stock Value</th>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
               {{ $batches->links() }}

                
            </div>

        </div>
    </div>
</div>
@endsection
