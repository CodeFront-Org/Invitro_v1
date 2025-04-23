@extends('layouts.app')

@section('content')@if (session()->has('message'))
    <div id="toast" class="alert text-center alert-success alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        Updated Successfully
    </div>
@endif
@if (session()->has('error'))
    <div id="toast" class="alert text-center alert-danger alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{session('error')}}
    </div>
@endif
          <br/>
          <!-- Start Content-->
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <div class="card p-2">
                <div class="card-box">
                  <div class="profile-info-name">
                    <form action="{{route('profile.update',$id)}}" id="detailForm"  method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="type" value="details">
                    <input type="hidden" required name="type1" value="profile" />
                    <div class="profile-info-detail overflow-hidden">
                      <h3>Your Details</h3>
                      <input
                        type="text"
                        required
                        id="first_name"
                        name="first_name"
                        class="form-control mt-2"
                        placeholder="First Name"
                        value="{{$first_name}}"
                      />
                      <input
                        type="text"
                        required
                        id="name"
                        name="last_name"
                        class="form-control mt-2"
                        placeholder="Last Name"
                        value="{{$last_name}}"
                      />
                      <input
                        type="email"
                        required
                        id="email"
                        name="email"
                        class="form-control mt-2"
                        placeholder="email"
                        autocomplete="off"
                        value="{{$email}}"
                      />
                      <input
                        type="number"
                        required
                        id="contact"
                        name="contacts"
                        class="form-control mt-2"
                        placeholder="Contacts"
                        value="{{$contacts}}"
                      /><br>

                    <div class="mb-3 mb-xl-0">
                        <label for="inputGroupFile04" class="form-label">Upload Profile Picture</label>
                        <input class="form-control" name="file" type="file" id="inputGroupFile04">
                    </div>

                      <ul class="social-list list-inline mt-3 mb-0"><button
                      type="submit"
                      class="
                        btn btn-purple pl-3 pr-3 btn-rounded justify-content-center
                        w-md
                        waves-effect waves-light
                        mt-2
                      " id="updatebtn" data-type="update" data-id="">
                        <li class="list-inline-item"> Save <i class="fas fa-save"></i>
                          </button>
                        </li>
                      </ul>
                    </div>
                    </form>
                    <div class="clearfix"></div>
                  </div>
                </div>
                </div>
                <!--/ meta -->
              </div>
                <!--/ end column -->

              <div class="col-sm-6">
             <div class="card p-1">
                <div class="card-box">
                     <form method="post" id="pswForm1" class="parsley-examples">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="type" value="password">
                  <h3 style="text-align:center">Update your password</h3>

                  <ul class="list-group mb-0 user-list">
                      
                                        <li class="list-group-item" style="display: none">
                                            <input type="text" required name="type1" value="psw" />
                                        </li>
                        <li class="list-group-item">
                            <input type="password" required name="oldpsw" class="form-control" parsley-trigger="change" placeholder="Old password" />
                        </li>
    
                        <li class="list-group-item">
                            <input type="password" required id="newpsw" name="newpsw" parsley-trigger="change" class="form-control" placeholder="New password" />
                        </li>
    
                        <li class="list-group-item">
                            <input type="password" required id="confirmpsw" name="confirmpsw" parsley-trigger="change" class="form-control" placeholder="Confirm password" />
                        </li>
                  </ul>
                  
                    <div class="form-group col-md-12 editbtn">
                    <button type="submit" style="width: 100%" class="btn btn-purple btn-rounded mt-1">Update Password</button>
                    </div>
                    <div class="form-group mb-0 col-md-12 text-center saveEditData" style="display: none;"><br>
                        <span style="display: 'none';">Updating Password...</span>
                        <br>
                        <div class="spinner-border text-primary m-0" style='color:green' role="status">
                        </div>
                    </div>
                  <div style="text-align: center">
                    </form>
                  </div></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div style="text-align: center" class="col-sm-12 p-4">
                  <p>
                    Your credentials are always secure, encrypted and privately
                    held by us.
                  </p>
                  <hr style="border-top: 1px solid #2d3beb" />

                </div>
              </div>
            </div>
          </div>
          <!-- container-fluid -->


@endsection

@section('scripts')
    <script>



/*************** Update PSW form processing ************************/
$("#pswForm1").on('submit',(e)=>{
e.preventDefault();
//Checking new and confirm psw
let newpsw=$("#newpsw").val();
let confirmpsw=$("#confirmpsw").val();
if(newpsw !== confirmpsw){
    Swal.fire("Error!", "Password Entered should Match", "error");
return
}
//Sending request
let id=$("#user_id").val();
let data=$("#pswForm1").serialize();
//alert(data)
        $.ajax({
            type: "POST",
            url: "{{route('pswUpdate')}}",
            data,
            success: function (response) { console.log(response)
            if(response=='1'){
            Swal.fire("Updated", 'Successfully', "success");
            }else if(response=='0'){
            Swal.fire("Error!", 'Old Password Entered is Wrong' , "error");
            }
            },
            error: function(res){ console.log(res)
                Swal.fire("Error!", "Try again later...", "error");
            }
        });

//Loading savedataer
let btn=$(".editbtn");
let savedata=$('.saveEditData');
btn.hide();
savedata.show();
setTimeout(() => {
    let btn=$(".editbtn");
    let savedata=$('.saveEditData');
    btn.show();
    savedata.hide();
}, 5000);
})




    </script>

        <!-- Plugin js-->
        <script src="{{asset('libs/parsleyjs/parsley.min.js')}}"></script>

        <!-- Validation init js-->
        <script src="{{asset('js/pages/form-validation.init.js')}}"></script>

        <!-- knob plugin -->
        <script src="{{asset('libs/jquery-knob/jquery.knob.min.js')}}"></script>

        <!-- Dashboar init js-->
        <script src="{{asset('js/pages/dashboard.init.js')}}"></script>


    <!-- Code to Update Password Details -->

@endsection
