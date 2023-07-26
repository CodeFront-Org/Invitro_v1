<!--Header Sections-->

<x-header />

<!--End Header Section -->

@yield('css')
@yield('modals')
    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->

            <x-topbarnav :label='$label' />
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
                <x-leftnavbar />
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content mt-2">

                <!-- Start Content-->
                    @yield('content')

        </div>
        <!-- content -->

        <!-- Footer Start -->
             <x-footer />
        <!-- end Footer -->

    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->
           <!-- Vendor -->
        <script src="{{asset('libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('libs/node-waves/waves.min.js')}}"></script>
        <script src="{{asset('libs/waypoints/lib/jquery.waypoints.min.js')}}"></script>
        <script src="{{asset('libs/jquery.counterup/jquery.counterup.min.js')}}"></script>
        <script src="{{asset('libs/feather-icons/feather.min.js')}}"></script>



        @yield('scripts')


        <!-- Tippy js-->
        <script src="{{asset('/libs/tippy.js/tippy.all.min.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('js/app.min.js')}}"></script>


        <!-- Sweet Alerts js -->
        <script src="{{asset('libs/sweetalert2/sweetalert2.all.min.js')}}"></script>

        <!-- Sweet alert init js-->
        <script src="{{asset('js/pages/sweet-alerts.init.js')}}"></script>

    <!-- Toastr js -->
    <script src="{{asset('libs/toastr/build/toastr.min.js')}}"></script>

    <script src="{{asset('js/pages/toastr.init.js')}}"></script>
    <script>

    </script>

</body>

</html>
