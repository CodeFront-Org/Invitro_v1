@extends('layouts.app')

@section('content')
@if (session()->has('error'))
    <div id="toast" class="alert text-center alert-danger alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ session('error') }}
    </div>
@endif
@if (session()->has('message'))
    <div id="toast" class="alert text-center alert-success alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ session('message') }}
    </div>
@endif


<form method="GET" action="{{route('cards.index')}}">
    <div class="row">

    <div class="row">
        <div class="mb-3 col-md-3">
            <label for="from">Product:</label>

            @php
                use App\Models\Product;
                $products = Product::all();

            @endphp

            <input type="text" list="regnoo" parsley-trigger="change"  class="form-control"
                id="p1_name" name='product_filter' autocomplete="off" placeholder="Search Product ..." aria-label="#"
            />

            <datalist id="regnoo">
                @foreach ($products as $product)
                    <option value="{{ $product->name }}">{{ $product->name }}</option>
                @endforeach
            </datalist>
        </div>
        <div class="mb-3 col-md-3">
            <label for="from">From:</label>
            <input type="date" class="form-control" name="from" data-provide="w" required placeholder="From: ">
        </div>
        <div class="mb-3 col-md-3">
            <label for="To">To:</label>
            <input type="date" class="form-control" name="to" required data-provide="datepicker1" placeholder="To: ">
        </div>
        <div class="mb-3 col-md-3" style="margin-top: 2%">
            <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
        </div>
        
    </div>


</form>



<div class="row mt-1">
    <div class="col-12">
    <button  style="background-color: #08228a9f;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
             New
        </button>
        <button id="excelbtn" type="button" class="btn btn-success"><i class="fa fa-file-excel bg-success"></i> excel </button>
        <div class="card" style="border-radius:0px 15px 15px 15px;box-shadow: 2px 3px 3px 2px rgba(9, 107, 255, 0.179);">
            <div class="card-body">
                <div class="table-responsive">
                    <table  style="font-family: 'Times New Roman', Times, serif" class="table table-bordered nowrap text-center" id="salestable" class="table table-sm table-bordered dt-responsive nowrap text-center">
                        <thead class="table-light">

                            @php
                                $page=$page_number;
                            @endphp
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            {{-- <th>Size</th> --}}
                            <th>Initial Qty</th>
                            <th>Out</th>
                            <th>In</th>
                            <th>At Hand</th>
                            <th>Name</th>
                      
                            <th>Date</th>
                            <th>Remarks</th>
                        
                        </tr>
                        </thead>


                        <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{$page}} </td>
                                <td>{{$item['item']}}</td>
                                {{-- <td>{{$item['size']}}</td> --}}
                                <td>{{$item['at_hand']}}</td>
                                <td>{{$item['out']}}</td>
                                <td>{{$item['in']}}</td>
                                <td>{{$item['balance']}}</td>
                                <td>{{$item['user']}}</td>
                            
                                <td>{{$item['date']}}</td>
                                <td>{{$item['remarks']}}</td>
                            
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
                        {{ $data1->appends(request()->except('page'))->links('vendor.pagination.simple-bootstrap-4')}}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div> <!-- end row -->

    
@endsection