@extends('layouts.app')

@section('content')

<!-- Start Content-->
<div class="container-fluid">


    <div class="row">
        <div class="col-sm-6">
            <div class="card p-2">
                <div class="card-box">
                    <div class="profile-info-name">
                        <form action="" id="detailForm" method="post" accept-charset="UTF-8"
                            enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="type" value="details">
                            <div class="profile-info-detail overflow-hidden">
                                <h3>Your Details</h3>
                                <input type="text" required id="name" name="first_name" class="form-control mt-2"
                                    placeholder="First Name" value="Martin" />
                                <input type="text" required id="name" name="second_name" class="form-control mt-2"
                                    placeholder="Second Name" value="Njoroge" />
                                <input type="text" required id="name" name="last_name" class="form-control mt-2"
                                    placeholder="Last Name" value="Muchene" />
                                <input type="email" required id="email" name="email" class="form-control mt-2"
                                    placeholder="email" autocomplete="off" value="martin@gmail.com" />
                                <input type="number" required id="contact" name="contacts" class="form-control mt-2"
                                    placeholder="Contacts" value="0797965680" /><br>

                                <div class="mb-3 mb-xl-0">
                                    <label for="inputGroupFile04" class="form-label">Upload Profile Picture</label>
                                    <input class="form-control" name="file" type="file" id="inputGroupFile04">
                                </div>

                                <ul class="social-list list-inline mt-3 mb-0"><button type="submit" class="
                        btn pl-3 pr-3 btn-rounded justify-content-center
                        w-md
                        waves-effect waves-light
                        mt-2
                      " style="background-color: #30D5C8;color: white" id="updatebtn" data-type="update" data-id="">
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
                    <form action="" id="pswForm" method="post" class="parsley-examples">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="type" value="password">
                        <h3 style="text-align:center">Update your password</h3>

                        <ul class="list-group mb-0 user-list">
                            <li class="list-group-item">
                                <input type="password" required id="oldpsw" name="oldpsw" class="form-control"
                                    placeholder="Old password" />
                            </li>

                            <li class="list-group-item">

                                <input id="pass1" name="newpsw" autocomplete="off" type="password"
                                    placeholder="Password" required class="form-control" />
                            </li>

                            <li class="list-group-item">

                                <input data-parsley-equalto="#pass1" type="password" required placeholder="Password"
                                    class="form-control" id="passWord2" />
                            </li>
                        </ul>
                        <div style="text-align: center">
                            <button type="submit" class="
                        btn rounded-pill
                        w-md
                        waves-effect waves-light
                        mt-2
                      " style="background-color: #30D5C8;color: white" data-type="psw" data-id="" id="pswUpdate">
                                Update Password
                            </button>
                    </form>
                </div>
            </div>
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
<!-- container -->

@endsection
