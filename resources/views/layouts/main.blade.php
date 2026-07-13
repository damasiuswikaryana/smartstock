@php
    $id_user = auth()->user()->id;
    $mode = auth()->user()->mode_style;
    $tglNow = date('Y-m-d');
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    @include('layouts.includes.style')
    @stack('css')
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-theme="{{ $mode }}" data-pc-theme="{{ $mode }}"
    data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-layout="vertical" id="body">
    <div id="loading-bar"
        style="position: fixed; top: 0; left: 0; width: 100%; height: 5px; background-color: #eee; z-index: 9999;">
    </div>
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    @include('layouts.includes.sidebar')
    @include('layouts.includes.head')

    <div class="pc-container">
        <div class="pc-content">
            @yield('content')
        </div>
    </div>

    @include('layouts.includes.foot')
    <div class="loader">
        <div class="p-4 text-center">
            <div class="custom-loader"></div>
            <h2 class="my-3 f-w-400">Memproses Data ...</h2>
            <p class="mb-0">Mohon tunggu, sedang memproses data ...</p>
        </div>
    </div>

    <div class="offcanvas border-0 pct-offcanvas offcanvas-end" tabindex="-1" id="offcanvas_pc_layout">
        <div class="offcanvas-header justify-content-between">
            <h5 class="offcanvas-title">Settings</h5>
            <button type="button" class="btn btn-icon btn-link-danger" data-bs-dismiss="offcanvas"
                aria-label="Close"><i class="ti ti-x"></i></button>
        </div>
    </div>

    @include('layouts.includes.script')
    <script>
        window.addEventListener("load", function() {
            // memulai loading bar
            var loadingBar = document.getElementById("loading-bar");
            loadingBar.style.display = "block";
        });

        // kode untuk memuat data atau melakukan proses lainnya
        setTimeout(function() {
            // menampilkan loading bar selesai
            document.getElementById("loading-bar").style.display = "none";
        }, 2000);
    </script>
    @stack('js')
</body>

</html>
