<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Register & Signup | CodeFront - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="Martin Njoroge" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- csrf-->
    <meta name="csrf-token" content="{{csrf_token()}}" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('images/Logo/s-dark.png')}}">

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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

</head>

<body class="loading authentication-bg-pattern">

    <div class="account-pages mt-2 mb-1">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-12 col-xl-6">
                    <div class="text-center">
                        <a href="index.html">
                            <img src="{{asset('images/Logo/s-no bg.png')}}" alt="" width="50" class="mx-auto">
                        </a>
                        <!-- <p class="text-muted mb-3">JuaSmart</p> -->
                    </div>
                    <div class="card" style="box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);border-radius: 10px;">

                        <div class="card-body p-lg-3">

                            <div class="text-center mb-4">
                                <h4 class="text-uppercase mt-0" style="color: #001d91f5;">Sign Up</h4>
                            </div>

                            <form id="registerForm" method="POST" class="needs-validation" novalidate>
@csrf
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="firstname" class="form-label">First name</label>
                                        <input type="text" name="firstname" class="form-control" id="firstname" placeholder="First name" required />
                                        <!-- <div class="valid-feedback">
                                            Looks good!
                                        </div> -->
                                        <div class="invalid-feedback">
                                            Please Enter your First name
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="lastname" class="form-label">Last name</label>
                                        <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Last name" required />
                                        <!-- <div class="valid-feedback">
                                            Looks good!
                                        </div> -->
                                        <div class="invalid-feedback">
                                            Please Enter your Last name
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label><br>
                                            <input type="tel" name="phone" class="form-control full-width" id="phone" aria-describedby="inputGroupPrepend" required />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email001" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value=" " id="email001" autocomplete="off" placeholder="email" required />
                                        <!-- <div class="valid-feedback">
                                            Looks good!
                                        </div> -->
                                        <div class="invalid-feedback">
                                            Enter valid Email
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" name="password" value="" autocomplete="new-password" id="password" class="form-control" placeholder="Enter your password">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                <div class="invalid-feedback">
                                    Enter your password
                                </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Confirm Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" name="password_confirmation" id="password-confirm" class="form-control" ="new-password" placeholder="Confirm password">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                <div class="invalid-feedback">
                                    Confirm password
                                </div>
                                </div>
                                </div>
                                <div class="col-md-12 mt-1 mb-3">
                                    <div class="form-check form-check-warning">
                                        <input type="checkbox" name="terms" class="form-check-input check-btn" id="invalidCheck" required />
                                        <label class="form-check-label" for="invalidCheck">By creating an account means you agree to the<a style="color: #001d91f5;" href="http://"> Terms and Conditions</a></label>
                                        <div class="invalid-feedback">
                                            You must agree before submitting.
                                        </div>
                                    </div>
                                </div>

                                <button class="btn rounded-pill p-1" id="btn_save" style="width: 100%; background-color: #001d91f5;color: white;" type="submit">

                                                        Submit
                                                    </button>
                                <button class="btn rounded-pill p-1" id="loader" style="width: 100%; background-color: #001d91f5;color: white;display:none;" type="button">
                                                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                                        Saving Info...
                                                    </button>

                            </form>
                            <hr>

                            <div class="row mt-2">
                                <div class="col-12 text-center">
                                    <p class="text-muted">Already have account? <a href="{{route('login')}}" class="text-dark ms-1"><b style="color: #001d91f5;">Sign In</b></a></p>
                                </div>
                                <!-- end col -->
                            </div>
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

    <script>
        const phoneInputField = document.querySelector("#phone");
        const phoneInput = window.intlTelInput(phoneInputField, {
        separateDialCode: true,
            preferredCountries: ["ke", "ug", "tz"],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });
    </script>
    <script>

        // Get the form element
        $(document).ready(function() {

$("#registerForm").on('submit',(e)=>{
    e.preventDefault()

//   const selectedCountryData = phoneInput.getSelectedCountryData();
//   const countryCode = selectedCountryData.iso2;
//   const countryName = selectedCountryData.name;
//   const dialingPrefix = selectedCountryData.dialCode;

//   const phoneNumber = phoneInput.getNumber();

//   console.log("Selected Country Code:", countryCode);
//   console.log("Selected Country Name:", countryName);
//   console.log("Dialing Prefix:", dialingPrefix);
//   console.log("Phone Number:", phoneNumber);
// return;
// Store input values in an array
var data = [];
$('input[name], .check-btn').each(function() {
    data.push($(this).val());
});
// Check if the checkbox is checked
var isChecked = $('.check-btn').is(':checked');

// Loop through each element in the data array and check if any value is empty
var isAllPopulated = true;
$.each(data, function(index, value) {
    if (value === '') {
        isAllPopulated = false;
        return false; // Exit the loop early if an empty value is found
    }
});

        var loader = $("#loader")
        var btn = $("#btn_save");
if (isAllPopulated && isChecked) {
    //console.log('All fields are populated and checkbox is checked!');
    //check for password match
    var firstname = $('#firstname').val();
    var lastname = $("#lastname").val();
    var password = $('#password').val();
    var conpsw = $("#password-confirm").val();
    var email = $("#email001").val();
    var phone = $("#phone").val();
    var terms=$("#invalidCheck").val();
    localStorage.setItem('email', email)
    localStorage.setItem('mobile', phone)
    if (password === conpsw) {
        btn.hide();
        loader.show();
    //Sending register request
    $.ajax({
        type: "POST",
        url: "{{ route('new_admin_reg') }}",
        data: {_token:"{{csrf_token()}}",firstname,lastname,email,phone,password,terms},
        success: function (response) {   console.log(response)
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Registered",
                text: "Successfully.",
                showConfirmButton: !1,
                timer: 3000
            });
        alert(response)
         window.location.href = "{{route('login')}}";

        },
        error:function(response){ console.log(response)
if(response.status==422){
    if(response.responseJSON.errors.email !==''){
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "The email has already been registered"
        })
    }else{
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Invalid data given"
        })
    }

}else if(response.status==419){
    Swal.fire({
        icon: "error",
        title: "Error",
        text: "Session expired. Refresh..."
    })
}else if(response.status==500){
    if(response.responseJSON.message.includes("Integrity constraint")){
           Swal.fire({
            icon: "error",
            title: "Error",
            text: "The Phone Number has already been registered"
        })
    }else{
            Swal.fire({
            icon: "error",
            title: "Error",
            text: "Server Error."
    })
    }
}


        btn.show();
        loader.hide();
        }
    });

    } else { //mismatch
        $("#password-confirm").val("");
        Swal.fire({
            icon: "error",
            title: "Password Mismatch",
            text: "Password entered should match"
        })
    }
} else {
    //console.log('Please fill in all fields and check the checkbox!');
}

})


            $('#registerpForm').on('submit',function(event) {
                event.preventDefault();

                // Store input values in an array
                var data = [];
                $('input[name], .check-btn').each(function() {
                    data.push($(this).val());
                });
                // Check if the checkbox is checked
                var isChecked = $('.check-btn').is(':checked');

                // Loop through each element in the data array and check if any value is empty
                var isAllPopulated = true;
                $.each(data, function(index, value) {
                    if (value === '') {
                        isAllPopulated = false;
                        return false; // Exit the loop early if an empty value is found
                    }
                });


            });
        });
    </script>

</body>

</html>
