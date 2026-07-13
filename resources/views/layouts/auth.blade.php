<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login - Smartstock | Smarter Inventory, Better Business</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=0.9, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Asta Research and Development App" />
    <meta name="author" content="Asta Research and Development App" />

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('logo_sruuput.webp') }}" type="image/x-icon" />

    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/assets/fonts/tabler-icons.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('/assets/fonts/feather.css') }} ">
    <link rel="stylesheet" href="{{ asset('/assets/fonts/fontawesome.css') }} ">
    <link rel="stylesheet" href="{{ asset('/assets/fonts/material.css') }} ">
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('/assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('/assets/css/style-preset.css') }} ">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            font-size: 18px;
            user-select: none;
            transition: .2s;
        }

        .password-toggle:hover {
            color: #0d6efd;
        }
    </style>
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="ltr"
    data-pc-theme="light">

    <div class="auth-main v2">
        <div class="bg-overlay bg-dark"></div>
        <div class="auth-wrapper">
            <div class="auth-sidecontent">
                <div class="auth-sidefooter">
                    <img src="{{ asset('img/logo/logo_apik_color.png') }}" class=" img-fluid" alt="images"
                        width="35%" style="margin-left: -15px !important;" />
                    <!--<hr class="mb-3 mt-4" />-->
                    <div class="row">
                        <div class="col my-1">
                            <p class="m-0">Restricted <a href="javascript:void(0)">
                                    Managed by Asta Pijar Kreasi Teknologi</a></p>
                        </div>
                    </div>
                </div>

            </div>
            <form class="auth-form" action="{{ route('login.post') }}" method="post">
                @csrf
                @method('POST')
                <div class="card my-4 mx-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-4 text-center">
                            <img src="{{ asset('img/logo/logo_rnd_color.png') }}" class="img-fluid" alt="images"
                                width="70%" />
                        </div>
                        <div class="mb-4">
                            <input type="text"
                                class="form-control @error('username')
                            is-invalid
                            @enderror"
                                name="username" id="floatingInputUsername" placeholder="Username"
                                autocomplete="one-time-code">
                        </div>
                        <div class="mb-0 position-relative">
                            <input type="password" name="password"
                                class="form-control @error('password')
                            is-invalid
                            @enderror"
                                id="floatingInputPassword" placeholder="Password" autocomplete="one-time-code">
                            <i class="password-toggle" id="togglePassword"></i>
                        </div>

                        <div class="d-grid mt-4 mb-3">
                            <button type="submit" class="btn btn-primary shadow">Sign In</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('layouts.includes.script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const password = document.getElementById("floatingInputPassword");
            const toggle = document.getElementById("togglePassword");

            toggle.innerHTML = feather.icons.eye.toSvg();

            toggle.addEventListener("click", function() {
                if (password.type === "password") {
                    password.type = "text";
                    toggle.innerHTML = feather.icons["eye-off"].toSvg();
                } else {
                    password.type = "password";
                    toggle.innerHTML = feather.icons.eye.toSvg();
                }
            });
        });
    </script>
</body>

</html>
