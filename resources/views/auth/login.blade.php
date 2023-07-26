<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Login | CodeFront - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="Martin Njoroge" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('images/Logo/s-dark.png')}}">
    <!-- Notification css (Toastr) -->
    <link href="{{asset('libs/toastr/build/toastr.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Sweet Alert-->
    <link href="{{asset('libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="{{asset('css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

    <!-- App-dark css -->
    <link href="{{asset('css/bootstrap-dark.min.css')}}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled="disabled" />
    <link href="{{asset('css/app-dark.min.css')}}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled="disabled" />

    <!-- icons -->
    <link href="{{asset('css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

</head>

<body class="loading authentication-bg1 authentication-bg-pattern">

    <div class="account-pages mt-3 mb-1">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="text-center">
                        <a href="index.html">
                            <img src="{{asset('images/Logo/s-no bg.png')}}" alt="" width="50" class="mx-auto">
                        </a>
                        <p class="text-muted mt-0 mb-1">CodeFront</p>

                    </div>
                    <div class="card" style="box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);border-radius: 10px;">
                        <div class="card-body p-4">

                            @if (session()->has('message'))
                                <div id="toast" class="alert text-center alert-warning alert-dismissible w-100 fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    Your Session has expired please Refresh page and login again.
                                </div>
                            @endif
                        @if(session('message'))
                            <div class="alert alert-danger">
                                {{ session('message') }}
                            </div>
                        @endif
                            <div class="text-center mb-4">
                                <h4 class="text-uppercase mt-0" style="color: #fac107;">Sign In</h4>
                            </div>

                            <form id="loginForm">
                                <div class="mb-2">
                                    <label for="emailaddress" class="form-label">Email address</label>
                                    <input class="form-control" name="email" type="email" id="emailaddress" autocomplete="off" required="" placeholder="Enter your email">
                                </div>

                                <div class="mb-3">
                                    <label for="pass1" class="form-label">Password</label>
                                        <input type="password" name="password" id="pass1" class="form-control" autocomplete="new-password" placeholder="Password">
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-check-warning">
                                        <input type="checkbox" class="form-check-input" id="checkbox-signin">
                                        <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                    </div>
                                </div>

                                <div class="mb-2 d-grid text-center">
                                    <button class="btn rounded-pill p-1" id="btn_save" style="width: 100%; background-color: #fac107;color: white;" type="submit">

                                                        Login
                                                    </button>
                                    <button class="btn rounded-pill p-1" id="loader" style="width: 100%; background-color: #fac107;color: white;display:none;" type="button">
                                                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                                        Authenticating...
                                                    </button>
                                </div>
                            </form>

                        </div>
                        <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <!-- end row -->

                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->


                            <!-- Reset password Modal -->

                            <div id="con-close-modal-changepsw" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <div class="card">

                                            <div class="card-body p-4">

                                                <div class="text-center mb-4">
                                                    <h4 class="text-uppercase mt-0 mb-3">Reset Password</h4>
                                                    <p class="text-muted mb-0 font-15">Enter your email address and we'll send you an email with instructions to reset your password.  </p>
                                                </div>

                                                <form id="resetForm">
                                                    <div class="mb-3">
                                                        <label for="emailaddress" class="form-label">Email address</label>
                                                        <input class="form-control rounded-3" type="email" id="resetemail" required="" placeholder="Enter your email">
                                                    </div>

                                                    <div class="mb-3 text-center d-grid">
                                                        <button class="btn rounded-pill" id="resetbtn" style="width: 100%; background-color: #fac107;color: white" type="submit"> Reset Password </button>
                                                        <button class="btn rounded-pill p-1" id="loaderMail" style="width: 100%; background-color: #fac107;color: white;display:none;" type="button">
                                                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                                        Sending Mail...
                                                    </button>
                                                    </div>

                                                </form>

                                            </div> <!-- end card-body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                </div>
                            </div><!-- /.Reset modal -->

                            <!-- Confirm password Modal -->
                            <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none1;">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                            <div class="card text-center">

                                                <div class="card-body p-4">

                                                    <div class="mb-4">
                                                        <h4 class="text-uppercase mt-0">Confirm Email</h4>
                                                    </div>
                                                    <img src="{{asset('images/mail_confirm.png')}}" alt="img" width="86" class="mx-auto d-block" />

                                                    <p class="text-muted font-14 mt-2"> A email has been sent to <b id="emailtext"></b>.
                                                        Please check for an email from company and click on the included link to
                                                        reset your password. </p>

                                                    <a data-bs-dismiss="modal" aria-label="Close" class="btn d-block waves-effect waves-light mt-3" style="width: 100%; background-color: #fac107;color: white">Back to Home</a>

                                                </div> <!-- end card-body -->
                                            </div>
                                            <!-- end card -->
                                    </div>
                                </div>
                            </div><!-- /.Confirm modal -->


    </div>
    <!-- end page -->

        <!-- Vendor -->
        <script src="{{asset('libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('libs/node-waves/waves.min.js')}}"></script>
        <script src="{{asset('libs/waypoints/lib/jquery.waypoints.min.js')}}"></script>
        <script src="{{asset('libs/jquery.counterup/jquery.counterup.min.js')}}"></script>
        <script src="{{asset('libs/feather-icons/feather.min.js')}}"></script>


        <!-- Plugin js-->
        <script src="{{asset('libs/parsleyjs/parsley.min.js')}}"></script>

        <!-- Validation init js-->
        <script src="{{asset('js/pages/form-validation.init.js')}}"></script>

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
        // Get the form element
        $(document).ready(function() {
            $('#loginForm').on('submit', (e) => {
                e.preventDefault();
                var email=$("#emailaddress").val();
                var psw=$("#pass1").val();
                var password=psw;
                var loader = $("#loader")

                var btn = $("#btn_save");
                btn.hide();
                loader.show();


        $.ajax({
            type: 'POST',
            url: '{{route('login')}}',
            data:{
                _token:"{{csrf_token()}}", email,password
            },
            success:function(response)
            { console.log(response)

                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-center",
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
                    toastr["success"]("", "Logged in Successfully ")
                window.location.href='/home'
            },
            error: function(response) { console.log(response)
            if(response.status==419){
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-top-center",
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

                toastr["error"]("", "Session has expired. Refreshing Page.")
                window.location.href='/login'
            }else{
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-top-center",
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
                toastr["error"]("", "Wrong Password or email address.")
            }
                btn.show();
                loader.hide();
            }
        });

            })

        $("#resetForm").on('submit',(event)=>{
        event.preventDefault();
        var resetbtn = $("#resetbtn")
        var loaderMail = $("#loaderMail")
        resetbtn.hide()
        loaderMail.show()
        var email1=$("#resetemail").val();
$.ajax({
    type: "POST",
    url: "{{route('reset')}}",
    data: {_token:"{{csrf_token()}}",email1},
    success: function (res) {
        if(res==1){
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-center",
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
                   toastr["success"]("", "An Email has been sent successfully")
                resetbtn.show()
                loaderMail.hide()
                $('#emailtext').text(email1);
                $('#myModal').modal('show')
                $('#con-close-modal-changepsw').modal('hide')

        }else if(res==2){
            resetbtn.show()
            loaderMail.hide()
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Email provided does not match our records."
            })
        }
    },
    error: function(res){
        resetbtn.show()
        loaderMail.hide()
        if(res.status==419){
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-top-center",
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

                toastr["error"]("", "Session has expired. Refreshing Page.")
                window.location.href='/login'
        }else{

            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Mail not sent. Please Check your connection and try again"
            })}
    }
});
        })

        })
    </script>
</body>

</html>
