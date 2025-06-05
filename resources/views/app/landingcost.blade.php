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
                        <div class="table-responsive">
                            <table  style="font-family: 'Times New Roman', Times, serif" class="table table-bordered nowrap text-center" id="datatable" class="table table-sm table-bordered dt-responsive nowrap text-center">
                                <thead class="table-light">

                                    @php
                                        $page=$page_number;
                                    @endphp
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Product Id</th>
                                    <th>Batch no</th>
                                    <th>Quantity</th>
                                    <th>Landing Cost</th>
                                    <th>Stock Value</th>
                       
                                </tr>
                                </thead>


                                <tbody>
                                @foreach ($batches as $item)
                                
                                 
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->product_id}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->batch_no}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{$item->landing_cost}}</td>
                                        <td>{{$item->stock_value}}</td>
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
                                {{-- {{ $data->appends(request()->except('page'))->links('vendor.pagination.simple-bootstrap-4')}} --}}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- end row -->


 

        



         






@endsection
