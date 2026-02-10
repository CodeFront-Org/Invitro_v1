<div class="left-side-menu">

    <div class="h-100" data-simplebar>

        <!-- Sidemenu -->
        <div id="sidebar-menu">

            <ul id="side-menu">
                <li class="menu-title"
                    style="color: #667eea; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.75rem;">
                    <i class="mdi mdi-menu me-2"></i>Navigation
                </li>





                @role(['card', 'card_view_only'])
                <li>
                    <a href="/cards" class="waves-effect">
                        <i class="fas fa-calendar-check"></i>
                        <span>Stock Card</span>
                    </a>
                </li>

                @endrole


                @role('card')
                <li>
                    <a href="{{route('audits.index')}}" class="waves-effect">
                        <i class="fas fa-credit-card"></i>
                        <span>Stock Audits</span>
                    </a>
                </li>
                @endrole

                @role('admin')
                <li>
                    <a href="{{route('users.index')}}" class="waves-effect">
                        <i class="fa fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>
                @endrole

                @role('staff')
                <li>
                    <a href="{{route('stock.index')}}" class="waves-effect">
                        <i class="fas fa-truck-moving"></i>
                        <span>Stocks</span>
                    </a>
                </li>


                <li>
                    <a href="/batch-edit" class="waves-effect">
                        <i class="fas fa-edit"></i>
                        <span>Edit Batch Expiry Dates</span>
                    </a>
                </li>


                <li>
                    <a href="{{route('order.index')}}" class="waves-effect">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Orders</span>
                    </a>
                </li>

                @endrole

                @role('admin')


                <li>
                    <a href="/batch-view" class="waves-effect">
                        <i class="fas fa-eye"></i>
                        <span>View Batches</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('approve.index')}}" class="waves-effect">
                        <i class="fas fa-check-double"></i>
                        <span>Approvals</span>
                    </a>
                </li>


                @endrole

                @role(['admin', 'staff'])

                <li>
                    <a href="#sidebarMultilevel2" data-bs-toggle="collapse" class="waves-effect">
                        <i class="fas fa-newspaper"></i>
                        <span>Reports</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarMultilevel2">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{route('/audited')}}" class="waves-effect">
                                    <i class="mdi mdi-check-circle-outline me-2"></i>Audit
                                </a>
                            </li>
                            <li>
                                <a href="{{route('/sales')}}" class="waves-effect">
                                    <i class="mdi mdi-chart-line me-2"></i>Sales
                                </a>
                            </li>
                            <li>
                                <a href="{{route('/reorder-level', ['type' => 2])}}" class="waves-effect">
                                    <i class="mdi mdi-alert-circle-outline me-2"></i>Re-Order Levels
                                </a>
                            </li>
                            <li>
                                <a href="{{route('/expired', ['type' => 0])}}" class="waves-effect">
                                    <i class="mdi mdi-calendar-remove me-2"></i>Expiry
                                </a>
                            </li>
                            <li>
                                <a href="{{route('/with-batch')}}" class="waves-effect">
                                    <i class="mdi mdi-package-variant me-2"></i>Stocks
                                </a>
                            </li>
                            <li>
                                <a href="/landingCost" class="waves-effect">
                                    <i class="mdi mdi-currency-usd me-2"></i>Stock Value
                                </a>
                            </li>
                            <li>
                                <a href="{{route('/restocks')}}" class="waves-effect">
                                    <i class="mdi mdi-truck-delivery me-2"></i>Restocks
                                </a>
                            </li>


                        </ul>

                    </div>
                </li>
                @endrole

                <li>
                    <a href="{{route('profile.index')}}" class="waves-effect">
                        <i class="fas fa-user-circle"></i>
                        <span>Profile</span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>