        <div class="left-side-menu" style="background-size: cover;background-image: linear-gradient(to right, #1b47f721,#001d9154);overflow: hidden;color:white">

            <div class="h-100" data-simplebar>

                <!--- Sidemenu -->
                <div id="sidebar-menu">

                    <ul id="side-menu">

                        <li class="menu-title">Navigation</li>
@role('admin')

                            <li>
                                <a href="index.html">
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
                                            <a href="#">
                                                Staff
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Customers
                                            </a>
                                        </li>
                                </ul>

                            </div>
                        </li>

                        <li>
                            <a href="wallet.html">
                                <i class="fas fa-truck-moving"></i>
                                <span>Stocks </span>
                            </a>
                        </li>

                        <li>
                            <a href="wallet.html">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Orders </span>
                            </a>
                        </li>

                        <li>
                            <a href="#sidebarMultilevel1" data-bs-toggle="collapse">
                                <i class="fas fa-check-double"></i>
                                <span> Approvals</span>
                                <span class="badge bg-success rounded-pill float-end">New</span>
                            </a>
                            <div class="collapse" id="sidebarMultilevel1">
                                <ul class="nav-second-level">
                                        <li>
                                            <a href="#">
                                                Stocks
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Returns
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Orders
                                            </a>
                                        </li>
                                </ul>

                            </div>
                        </li>

                        <li>
                            <a href="#sidebarMultilevel2" data-bs-toggle="collapse">
                                <i class="mdi mdi-book-settings-outline"></i>
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
                            <a href="profile.html">
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
