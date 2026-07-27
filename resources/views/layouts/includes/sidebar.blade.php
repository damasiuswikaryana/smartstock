@php
    $menus = \App\Helpers\MenuHeader::getMenu();
    $lastScope = null;
    $classWarna = null;
    $scopeIndex = [];
    foreach ($menus as $menu) {
        if (!empty($menu['scope'])) {
            $scopeCounts[$menu['scope']] = ($scopeCounts[$menu['scope']] ?? 0) + 1;
        }
    }
@endphp
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('dashboard') }}"
                class="b-brand text-primary d-flex flex-column align-items-center justify-content-between">
                @if (auth()->user()->mode_style == 'light')
                    <img src="{{ asset('assets/images/logo/logo_smartwarehouse_color.png') }}" alt="logo image"
                        class="logo-lg mt-0" height="27" />
                @else
                    <img src="{{ asset('assets/images/logo/logo_smartwarehouse_white.png') }}" alt="logo image"
                        class="logo-lg mt-0" height="27" />
                @endif
                <span class="badge bg-brand-color-2 rounded-pill ms-1 theme-version">v1.2.0</span>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar mt-2">
                @foreach ($menus as $menu)
                    @php
                        if ($menu['scope'] == 'pengadaan') {
                            $classWarna = 'pc-item-pink';
                        } elseif ($menu['scope'] == 'gudang') {
                            $classWarna = 'pc-item-blue';
                        } else {
                            $classWarna = '';
                        }
                    @endphp
                    @if (!empty($menu['scope']) && $lastScope != $menu['scope'])
                        @php
                            if ($menu['scope'] == 'pengadaan') {
                                $titleMenu = 'Pengadaan';
                            } elseif ($menu['scope'] == 'gudang') {
                                $titleMenu = 'Gudang';
                            } elseif ($menu['scope'] == 'masterdata') {
                                $titleMenu = 'Master Data';
                            } else {
                                $titleMenu = '';
                            }
                        @endphp
                        <li class="pc-item pc-caption {{ $classWarna }} border-top-radius mt-1">
                            <label data-i18n="{{ $titleMenu }}">
                                {{ $titleMenu }}
                            </label>
                            <i class="ph-duotone ph-chart-pie"></i>
                        </li>
                        @php
                            $lastScope = $menu['scope'];
                            if (!isset($scopeIndex[$menu['scope']])) {
                                $scopeIndex[$menu['scope']] = 0;
                            }
                        @endphp
                    @endif
                    @php
                        $classBorderBottom = '';
                        if (!empty($menu['scope'])) {
                            $scopeIndex[$menu['scope']]++;
                            if (
                                $scopeCounts[$menu['scope']] > 0 &&
                                $scopeIndex[$menu['scope']] == $scopeCounts[$menu['scope']]
                            ) {
                                $classBorderBottom = 'border-bottom-radius mb-2';
                            }
                        }
                    @endphp
                    @if (isset($menu['children']))
                        <li class="pc-item pc-hasmenu {{ $classWarna . ' ' . $classBorderBottom }}">
                            <a href="javascript:void(0);"
                                class="pc-link @if (Request::segment(1) == $menu['segment']) active show @endif">
                                <span class="pc-micon">
                                    <i class="{{ $menu['icon'] }}"></i>
                                </span>
                                <span class="pc-mtext" data-i18n="{{ $menu['title'] }}">{{ $menu['title'] }}</span>
                                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                            </a>
                            <ul class="pc-submenu">
                                @foreach ($menu['children'] as $subchild)
                                    <li class="pc-item @if (Request::is(ltrim($menu['uri'], '/'))) active @endif"><a
                                            class="pc-link" href="{{ url($subchild['uri']) }}"
                                            data-i18n="{{ $subchild['title'] }}">{{ $subchild['title'] }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li
                            class="pc-item {{ $classWarna . ' ' . $classBorderBottom }} @if (Request::segment(1) == $menu['segment']) active @endif">
                            <a href="{{ url($menu['uri']) }}" class="pc-link">
                                <span class="pc-micon">
                                    <i class="{{ $menu['icon'] }}"></i>
                                </span>
                                <span class="pc-mtext" data-i18n="{{ $menu['title'] }}">{{ $menu['title'] }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
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
