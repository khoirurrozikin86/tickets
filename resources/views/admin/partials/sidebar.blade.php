<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('super.dashboard') }}" class="sidebar-brand">
            TICKET<span> </span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span><span></span><span></span>
        </div>
    </div>

    <div class="sidebar-body">
        <ul class="nav">
            {{-- ================= MAIN ================= --}}
            <li class="nav-item nav-category">Main</li>
            @can('dashboard.view')
                <li class="nav-item">
                    <a href="{{ route('super.dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="link-icon" data-feather="box"></i>
                        <span class="link-title">Dashboard</span>
                    </a>
                </li>
            @endcan

            {{-- ================= ACCESS CONTROL ================= --}}
            @canany(['user.menu', 'role.menu', 'permission.menu'])
                <li class="nav-item nav-category">Access Control</li>
            @endcanany

            {{-- Users --}}
            @can('user.menu')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-users" role="button" aria-expanded="false"
                        aria-controls="menu-users">
                        <i class="link-icon" data-feather="users"></i>
                        <span class="link-title">Users</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('super.user.*') ? 'show' : '' }}" id="menu-users">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('super.user.index') }}"
                                    class="nav-link {{ request()->routeIs('super.user.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            {{-- Roles --}}
            @can('role.menu')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-roles" role="button" aria-expanded="false"
                        aria-controls="menu-roles">
                        <i class="link-icon" data-feather="shield"></i>
                        <span class="link-title">Roles</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('super.role.*') ? 'show' : '' }}" id="menu-roles">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('super.roles.index') }}"
                                    class="nav-link {{ request()->routeIs('super.role.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            {{-- Permissions --}}
            @can('permission.menu')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-permissions" role="button"
                        aria-expanded="false" aria-controls="menu-permissions">
                        <i class="link-icon" data-feather="key"></i>
                        <span class="link-title">Permissions</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('super.permission.*') ? 'show' : '' }}"
                        id="menu-permissions">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('super.permissions.index') }}"
                                    class="nav-link {{ request()->routeIs('super.permission.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan


            {{-- ================= MASTER ================= --}}
            @canany(['products.view'])
                <li class="nav-item nav-category">MASTER</li>
            @endcanany


            @can('products.view')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-products" role="button" aria-expanded="false"
                        aria-controls="menu-products">

                        <i class="link-icon" data-feather="shopping-bag"></i>

                        <span class="link-title">Products</span>

                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>

                    <div class="collapse {{ request()->routeIs('super.products.*') ? 'show' : '' }}" id="menu-products">

                        <ul class="nav sub-menu">

                            <li class="nav-item">
                                <a href="{{ route('super.products.index') }}"
                                    class="nav-link {{ request()->routeIs('super.products.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endcan



            @can('products.view')
                <li class="nav-item">

                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-products" role="button"
                        aria-expanded="{{ request()->routeIs('super.products.*') || request()->routeIs('super.product-prices.*') ? 'true' : 'false' }}"
                        aria-controls="menu-products">

                        <i class="link-icon" data-feather="shopping-bag"></i>

                        <span class="link-title">
                            Product Prices
                        </span>

                        <i class="link-arrow" data-feather="chevron-down">
                        </i>

                    </a>

                    <div class="collapse {{ request()->routeIs('super.products.*') || request()->routeIs('super.product-prices.*') ? 'show' : '' }}"
                        id="menu-products">

                        <ul class="nav sub-menu">


                            <li class="nav-item">

                                <a href="{{ route('super.product-prices.index') }}"
                                    class="nav-link {{ request()->routeIs('super.product-prices.*') ? 'active' : '' }}">

                                    Product Price

                                </a>

                            </li>

                        </ul>

                    </div>

                </li>
            @endcan



            @can('holidays.view')
                <li class="nav-item">
                    <a href="{{ route('super.holidays.index') }}"
                        class="nav-link {{ request()->routeIs('super.holidays.*') ? 'active' : '' }}">
                        <i class="link-icon" data-feather="calendar"></i>
                        <span class="link-title">Holidays</span>
                    </a>
                </li>
            @endcan



            @can('discounts.view')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-discounts" role="button"
                        aria-expanded="{{ request()->routeIs('super.discounts.*') ? 'true' : 'false' }}"
                        aria-controls="menu-discounts">

                        <i class="link-icon" data-feather="percent"></i>

                        <span class="link-title">
                            Discount
                        </span>

                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>

                    <div class="collapse {{ request()->routeIs('super.discounts.*') ? 'show' : '' }}"
                        id="menu-discounts">

                        <ul class="nav sub-menu">

                            <li class="nav-item">

                                <a href="{{ route('super.discounts.index') }}"
                                    class="nav-link {{ request()->routeIs('super.discounts.index') ? 'active' : '' }}">

                                    Show

                                </a>

                            </li>

                        </ul>

                    </div>
                </li>
            @endcan




            {{-- ================= SYSTEM ================= --}}
            @canany(['audit-logs.view'])
                <li class="nav-item nav-category">
                    SYSTEM
                </li>
            @endcanany

            @can('audit-logs.view')
                <li class="nav-item">

                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-audit-logs" role="button"
                        aria-expanded="{{ request()->routeIs('super.audit-logs.*') ? 'true' : 'false' }}"
                        aria-controls="menu-audit-logs">

                        <i class="link-icon" data-feather="activity"></i>

                        <span class="link-title">
                            Audit Log
                        </span>

                        <i class="link-arrow" data-feather="chevron-down"></i>

                    </a>

                    <div class="collapse {{ request()->routeIs('super.audit-logs.*') ? 'show' : '' }}"
                        id="menu-audit-logs">

                        <ul class="nav sub-menu">

                            <li class="nav-item">

                                <a href="{{ route('super.audit-logs.index') }}"
                                    class="nav-link {{ request()->routeIs('super.audit-logs.index') ? 'active' : '' }}">
                                    Show
                                </a>

                            </li>

                        </ul>

                    </div>

                </li>
            @endcan




            {{-- TRANSAKSI --}}
            @can('tickets.view')
                <li class="nav-item nav-category">
                    TRANSAKSI
                </li>
            @endcan

            @can('tickets.view')
                <li class="nav-item">

                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-tickets" role="button"
                        aria-expanded="{{ request()->routeIs('super.tickets.*') ? 'true' : 'false' }}"
                        aria-controls="menu-tickets">

                        <i class="link-icon" data-feather="tag"></i>

                        <span class="link-title">
                            Ticket
                        </span>

                        <i class="link-arrow" data-feather="chevron-down"></i>

                    </a>

                    <div class="collapse {{ request()->routeIs('super.tickets.*') ? 'show' : '' }}" id="menu-tickets">

                        <ul class="nav sub-menu">

                            <li class="nav-item">

                                <a href="{{ route('super.tickets.index') }}"
                                    class="nav-link {{ request()->routeIs('super.tickets.index', 'super.tickets.show') ? 'active' : '' }}">

                                    Monitoring

                                </a>

                            </li>

                        </ul>

                    </div>

                </li>
            @endcan



            @can('orders.view')
                <li class="nav-item">

                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-orders" role="button"
                        aria-expanded="{{ request()->routeIs('super.orders.*') ? 'true' : 'false' }}"
                        aria-controls="menu-orders">

                        <i class="link-icon" data-feather="shopping-cart"></i>

                        <span class="link-title">
                            Order
                        </span>

                        <i class="link-arrow" data-feather="chevron-down"></i>

                    </a>

                    <div class="collapse {{ request()->routeIs('super.orders.*') ? 'show' : '' }}" id="menu-orders">

                        <ul class="nav sub-menu">

                            <li class="nav-item">

                                <a href="{{ route('super.orders.index') }}"
                                    class="nav-link {{ request()->routeIs('super.orders.index', 'super.orders.show') ? 'active' : '' }}">
                                    Monitoring
                                </a>

                            </li>

                        </ul>

                    </div>

                </li>
            @endcan


            @can('payments.view')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-payments" role="button"
                        aria-expanded="{{ request()->routeIs('super.payments.*') ? 'true' : 'false' }}"
                        aria-controls="menu-payments">

                        <i class="link-icon" data-feather="credit-card"></i>

                        <span class="link-title">Payment</span>

                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>

                    <div class="collapse {{ request()->routeIs('super.payments.*') ? 'show' : '' }}" id="menu-payments">

                        <ul class="nav sub-menu">

                            <li class="nav-item">
                                <a href="{{ route('super.payments.index') }}"
                                    class="nav-link {{ request()->routeIs('super.payments.index') ? 'active' : '' }}">
                                    Monitoring
                                </a>
                            </li>

                        </ul>

                    </div>
                </li>
            @endcan










            @can('site-settings.view')
                <li class="nav-item nav-category">
                    WEBSITE
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-site-settings" role="button"
                        aria-expanded="{{ request()->routeIs('super.site-settings.*') ? 'true' : 'false' }}"
                        aria-controls="menu-site-settings">

                        <i class="link-icon" data-feather="settings"></i>

                        <span class="link-title">
                            Site Settings
                        </span>

                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>

                    <div class="collapse {{ request()->routeIs('super.site-settings.*') ? 'show' : '' }}"
                        id="menu-site-settings">

                        <ul class="nav sub-menu">
                            <li class="nav-item">

                                <a href="{{ route('super.site-settings.index') }}"
                                    class="nav-link {{ request()->routeIs('super.site-settings.index') ? 'active' : '' }}">
                                    Show
                                </a>

                            </li>
                        </ul>

                    </div>
                </li>
            @endcan




            @can('banners.view')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-banners" role="button"
                        aria-expanded="{{ request()->routeIs('super.banners.*') ? 'true' : 'false' }}"
                        aria-controls="menu-banners">

                        <i class="link-icon" data-feather="image"></i>

                        <span class="link-title">Banner</span>

                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>

                    <div class="collapse {{ request()->routeIs('super.banners.*') ? 'show' : '' }}" id="menu-banners">

                        <ul class="nav sub-menu">

                            <li class="nav-item">
                                <a href="{{ route('super.banners.index') }}"
                                    class="nav-link {{ request()->routeIs('super.banners.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endcan











        </ul>

    </div>



</nav>
