        <div class="navbar-custom">
            <ul class="list-unstyled topnav-menu float-end mb-0">

                <li class="d-none d-lg-block">
                    <form class="app-search">
                        <div class="app-search-box">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search..." id="top-search">
                                <button class="btn input-group-text" type="submit">
                                            <i class="fe-search"></i>
                                        </button>
                            </div>
                            <div class="dropdown-menu dropdown-lg" id="search-dropdown">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h5 class="text-overflow mb-2">Found 3 results</h5>
                                </div>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <i class="fe-home me-1"></i>
                                    <span>Analytics Report</span>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <i class="fe-settings me-1"></i>
                                    <span>User profile settings</span>
                                </a>

                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow mb-2 text-uppercase">Users</h6>
                                </div>

                                <div class="notification-list">
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="d-flex align-items-start">
                                            <img class="d-flex me-2 rounded-circle" src="{{asset('images/users/profile/default.jpg')}} alt="user image" height="32">
                                            <div class="w-100">
                                                <h5 class="m-0 font-14">Martin</h5>
                                                <span class="font-12 mb-0">Developer</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </form>
                </li>

                <li class="dropdown d-inline-block d-lg-none">
                    <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="fe-search noti-icon"></i>
                    </a>
                    <div class="dropdown-menu dropdown-lg dropdown-menu-end p-0">
                        <form class="p-3">
                            <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                        </form>
                    </div>
                </li>

                        @php
                            use App\Models\Stock;
                            $approvals = count(Stock::all()->where('approve',0));
                            if($approvals>0){$count=1;}else{$count=0;}

                        @endphp
                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="fe-bell noti-icon"></i>
                        <span class="badge bg-danger rounded-circle noti-icon-badge">{{$count}}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-lg">

                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="m-0">
                                <span class="float-end">
                                            <a href="" class="text-dark">
                                                <small>Clear All</small>
                                            </a>
                                        </span>Notification
                            </h5>
                        </div>

                        <div class="noti-scroll" data-simplebar>

                            <!-- item
                            <a href="javascript:void(0);" class="dropdown-item notify-item active">
                                <div class="notify-icon">
                                    <img src="{{asset('images/users/default.jpg')}}" class="img-fluid rounded-circle" alt="" /> </div>
                                <p class="notify-details">Martin Njoroge</p>
                                <p class="text-muted mb-0 user-msg">
                                    <small>Hi, your documents were verified</small>
                                </p>
                            </a>-->
                            <!-- item-->
                            @if ($approvals>0)
                                <a href="{{route('approve.index')}}" class="dropdown-item notify-item active">
                                    <p class="notify-details">Approve Stocks</p>
                                    <p class="text-muted mb-0 user-msg">
                                        <small>Hi, you have <b>{{$approvals}}</b> new stock to approve.</small>
                                    </p>
                                </a>
                            @endif
                        </div>

                        <!-- All
                        <a href="javascript:void(0);" class="dropdown-item text-center text-warning notify-item notify-all">
                                    View all
                                    <i class="fe-arrow-right"></i>
                                </a>-->

                    </div>
                </li>

                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    @if (Auth::user()->path=='')
                        <img src="{{asset('images/users/profile/default.jpg')}}" alt="user-image" class="rounded-circle">
                    @else
                        <img src="{{asset('images/users/profile/'.Auth::user()->path)}}" alt="user-image" class="rounded-circle">
                    @endif
                        <span class="pro-user-name ms-1">
                        {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }} <i class="mdi mdi-chevron-down"></i>
                    </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome !</h6>
                        </div>

                        <!-- item-->
                        <a href="{{route('profile')}}" class="dropdown-item notify-item">
                            <i class="fe-user"></i>
                            <span>My Account</span>
                        </a>

                        <div class="dropdown-divider"></div>

                    <!-- item-->
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item notify-item">
                        <i class="fe-log-out"></i>
                        <span>Logout</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                     </a>

                    </div>
                </li>


            </ul>

            <!-- LOGO -->
            <div class="logo-box">
                <a href="{{route('home')}}" class="logo logo-light text-center">
                    <span class="logo-sm">
                                <img src="{{asset('images/Logo/logo.png')}}" alt="" width="60">
                            </span>
                    <span class="logo-lg" style="left:50px;">
                                <img src="{{asset('images/Logo/logo.png')}}" alt="" width="85">
                            </span>
                </a>
                <a href="{{route('home')}}" class="logo logo-dark text-center">
                    <span class="logo-sm">
                                <img src="{{asset('images/Logo/logo.png')}}" alt="" width="60">
                            </span>
                    <span class="logo-lg" style="left:50px;">
                                <img src="{{asset('images/Logo/logo.png')}}" alt="" width="85">
                            </span>
                </a>
            </div>

            <ul class="list-unstyled topnav-menu topnav-menu-left mb-0">
                <li>
                    <button class="button-menu-mobile disable-btn waves-effect">
                                <i class="fe-menu"></i>
                            </button>
                </li>

                <li>
                    <h4 class="page-title-main" id="logo-tour">{{$label}}</h4>
                </li>

            </ul>

            <div class="clearfix"></div>

        </div>
