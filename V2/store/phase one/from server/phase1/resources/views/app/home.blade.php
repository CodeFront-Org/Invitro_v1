@extends('layouts.app')

@section('content')
@if (session()->has('error'))
    <div id="toast" class="alert text-center alert-danger alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ session('error') }}
    </div>
@endif
<div class="container">
    <div class="row justify-content-center">

        <div class="row">


            <div class="col-xl-4 col-md-6">
                <div class="card">
                    <div class="card-body widget-user">
                        <div class="text-center">
                            <h2 class="fw-normal text-primary" data-plugin="counterup">{{$products}}</h2>
                            products
                            <h5>Total Products</h5>
                        </div>
                    </div>
                </div>

            </div>
            <!-- end col -->

            <div class="col-xl-4 col-md-6">
                <div class="card">
                    <div class="card-body widget-user">
                        <div class="text-center">
                            <h2 class="fw-normal text-primary" data-plugin="counterup">{{$users}}</h2>Users
                            <h5>Total Users</h5>
                        </div>
                    </div>
                </div>

            </div>

            <!-- end col -->


            <div class="col-xl-4 col-md-6">
                <div class="card">
                    <div class="card-body widget-user">
                        <div class="text-center">
                            <h2 class="fw-normal text-primary" data-plugin="counterup">{{$orders}}</h2>orders
                            <h5>Total Orders</h5>
                        </div>
                    </div>
                </div>

            </div>


        </div>
        <!-- end row -->

    </div>
</div>
@endsection
