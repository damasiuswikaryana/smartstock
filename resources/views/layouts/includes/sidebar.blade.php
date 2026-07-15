<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('dashboard') }}" class="b-brand text-primary">
                @if (auth()->user()->mode_style == 'light')
                    <img src="{{ asset('assets/images/logo/logo_rnd_color.png') }}" alt="logo image" class="logo-lg mt-2"
                        height="40" />
                @else
                    <img src="{{ asset('assets/images/logo/logo_rnd_white.png') }}" alt="logo image" class="logo-lg mt-2"
                        height="40" />
                @endif
                <span class="badge bg-brand-color-2 rounded-pill ms-1 theme-version">v1.3.0</span>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar mt-2">

                <li class="pc-item {{ request()->is('/') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph-duotone ph-gauge"></i>
                        </span>
                        <span class="pc-mtext" data-i18n="Dashboard">Dashboard</span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label data-i18n="Pengadaan">Pengadaan</label>
                    <i class="ph-duotone ph-chart-pie"></i>
                </li>

                <li class="pc-item pc-caption">
                    <label data-i18n="Gudang">Gudang</label>
                    <i class="ph-duotone ph-chart-pie"></i>
                </li>

                <li class="pc-item pc-hasmenu">
                    <a href="javascript:void(0);" class="pc-link active">
                        <span class="pc-micon">
                            <i class="ph-duotone ph-monitor"></i>
                        </span>
                        <span class="pc-mtext" data-i18n="Stock Monitor">Stock Monitor</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="{{ route('stockCurrent.index') }}"
                                data-i18n="Current Stock">Current Stock</a>
                        </li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('stockMutation.index') }}"
                                data-i18n="Mutation">Mutation</a></li>
                        <li class="pc-item"><a class="pc-link" href="" data-i18n="Opnam">Opnam</a></li>
                    </ul>
                </li>
                <li class="pc-item pc-hasmenu">
                    <a href="javascript:void(0);" class="pc-link active">
                        <span class="pc-micon">
                            <i class="ph-duotone ph-arrows-left-right"></i>
                        </span>
                        <span class="pc-mtext" data-i18n="In Out Stock">In Out Stock</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="{{ route('stockin.index') }}"
                                data-i18n="Stock In">Stock In</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('stockout.index') }}"
                                data-i18n="Stock Out">Stock Out</a></li>
                        <li class="pc-item"><a class="pc-link" href="" data-i18n="Transfer">Transfer</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('stockinit.index') }}"
                                data-i18n="Initation">Initation</a></li>
                    </ul>
                </li>

                <li
                    class="pc-item pc-hasmenu 
                    {{ request()->is('category*') || request()->is('satuan*') || request()->is('vendor*') || request()->is('item*') ? 'active pc-trigger' : '' }}">
                    <a href="javascript:void(0);" class="pc-link active">
                        <span class="pc-micon">
                            <i class="ph-duotone ph-package"></i>
                        </span>
                        <span class="pc-mtext" data-i18n="Item Master">Item Master</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item {{ request()->is('category*') ? 'active' : '' }}"><a class="pc-link"
                                href="{{ route('category.index') }}" data-i18n="Category">Category</a></li>
                        <li class="pc-item {{ request()->is('satuan*') ? 'active' : '' }}"><a class="pc-link"
                                href="{{ route('satuan.index') }}" data-i18n="Satuan">Satuan</a></li>
                        <li class="pc-item {{ request()->is('vendor*') ? 'active' : '' }}"><a class="pc-link"
                                href="{{ route('vendor.index') }}" data-i18n="Vendor">Vendor</a></li>
                        <li class="pc-item {{ request()->is('item*') ? 'active' : '' }}"><a class="pc-link"
                                href="{{ route('item.index') }}" data-i18n="Item">Item</a></li>
                    </ul>
                </li>

                <li class="pc-item pc-caption">
                    <label data-i18n="Master Data">Master Data</label>
                    <i class="ph-duotone ph-chart-pie"></i>
                </li>

                <li
                    class="pc-item pc-hasmenu {{ request()->is('outlet*') || request()->is('bank-account*') || request()->is('user*') ? 'active pc-trigger' : '' }}">
                    <a href="javascript:void(0);" class="pc-link active">
                        <span class="pc-micon">
                            <i class="ph-duotone ph-database"></i>
                        </span>
                        <span class="pc-mtext" data-i18n="Data Master">Data Master</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item {{ request()->is('outlet*') ? 'active' : '' }}"><a class="pc-link"
                                href="{{ route('outlet.index') }}" data-i18n="Werehouse">Werehouse</a></li>
                        <li class="pc-item {{ request()->is('entitas*') ? 'active' : '' }}"><a class="pc-link"
                                href="{{ route('entitas.index') }}" data-i18n="Entity">Entity</a></li>
                        <li class="pc-item {{ request()->is('project*') ? 'active' : '' }}"><a class="pc-link"
                                href="{{ route('project.index') }}" data-i18n="Projects">Projects</a>
                        </li>
                        <li class="pc-item {{ request()->is('bank-account*') ? 'active' : '' }}"><a class="pc-link"
                                href="{{ route('bank-account.index') }}" data-i18n="Bank Accounts">Bank Accounts</a>
                        </li>
                        <li class="pc-item {{ request()->is('user*') ? 'active' : '' }}"><a class="pc-link"
                                href="{{ route('user.index') }}" data-i18n="System Users">System Users</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="card pc-user-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img src="{{ auth()->user()->photo ? asset('storage/user/' . auth()->user()->photo) : asset('assets/images/user/avatar-1.jpg') }}"
                            alt="user-image" class="user-avtar wid-45 rounded-circle" />
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="dropdown">
                            <a href="#" class="arrow-none dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false" data-bs-offset="0,20">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-2">
                                        <h6 class="mb-0">{{ auth()->user()->firstname }}
                                            {{ auth()->user()->lastname }}</h6>
                                        <small>Administrator</small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="btn btn-icon btn-link-secondary avtar">
                                            <i class="ph-duotone ph-windows-logo"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu">
                                <ul>
                                    <li>
                                        <a href="{{ route('profile') }}" class="pc-user-links">
                                            <i class="ph-duotone ph-user"></i>
                                            <span>My Account</span>
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="post" class="d-none"
                                            id="logoutForm">
                                            @csrf @method('POST')
                                        </form>
                                        <button type="submit" form="logoutForm"
                                            class="pc-user-links text-danger border-0 bg-transparent w-100">
                                            <i class="ph-duotone ph-power"></i>
                                            <span>Logout</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

@push('js')
@endpush
