        <div class="left-side-menu">

            <div class="h-100" data-simplebar>

                <!--- Sidemenu -->
                <div id="sidebar-menu">

                    <ul id="side-menu">

                        <li class="menu-title">Navigation</li>
@role('admin')

                            <li>
                                <a href="{{route('home')}}">
                                    <i class="mdi mdi-view-dashboard-outline"></i>
                                    <span> Dashboard </span>
                                </a>
                            </li>

                        <li>
                            <a href="#sidebarMultilevel" data-bs-toggle="collapse">
                                <i class="fa fa-users"></i>
                                <span> Users</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarMultilevel">
                                <ul class="nav-second-level">
                                        <li>
                                            <a href="{{route('users.index')}}">
                                                Staff
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{route('customers')}}">
                                                Customers
                                            </a>
                                        </li>
                                </ul>

                            </div>
                        </li>

                        <li>
                            <a href="{{route('stock.index')}}">
                                <i class="fas fa-truck-moving"></i>
                                <span>Stocks </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{route('order.index')}}">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Orders </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{route('approve.index')}}">
                                <i class="fas fa-check-double"></i>
                                <span> Approvals</span>

                                   <!-- <span class="badge bg-success rounded-pill float-end">New</span>-->


                            </a>
                        </li>

                        <li>
                            <a href="#sidebarMultilevel2" data-bs-toggle="collapse">
                                <i class="fas fa-newspaper"></i>
                                <span> Reports</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarMultilevel2">
                                <ul class="nav-second-level">
                                        <li>
                                            <a href="#">
                                                Stocks
                                            </a>
                                        </li>
                                </ul>

                            </div>
                        </li>
@endrole
                        <li>
                            <a href="{{route('profile')}}">
                                <i class="fas fa-user-circle"></i>
                                <span> Profile </span>
                            </a>
                        </li>

                    </ul>

                </div>
                <!-- End Sidebar -->

                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
