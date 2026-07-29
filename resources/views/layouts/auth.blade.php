<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.includes.meta')
    @include('layouts.includes.style')
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

    <div class="auth-main v2" style="background-image: url({{ asset('assets/images/bg/auth-bg-small-2.jpg') }})";>
        <div class="bg-overlay bg-light"></div>
        <div class="auth-wrapper">
            <form id="form-login" class="auth-form" action="{{ route('login.post') }}" method="post">
                @csrf
                @method('POST')
                <div class="card my-4 mx-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-1 text-center">
                            <img src="{{ asset('assets/images/logo/logo_smartwarehouse_color.png') }}" class="img-fluid"
                                alt="images" width="80%" />
                        </div>
                        <p class="text-center mb-4 text-muted opacity-50">Procurement System and Warehouse Efficiency
                        </p>
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
                            <button id="btn-login" type="submit" class="btn btn-primary shadow">Sign In</button>
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

        $('#form-login').submit(function(e) {
            e.preventDefault();
            let btn = $('#btn-login');
            btn.prop('disabled', true);
            btn.html('Signing in...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    btn.prop('disabled', false);
                    btn.html('Sign In');
                    showToastSuccess(response.message);
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 700);
                },

                error: function(xhr) {
                    btn.prop('disabled', false);
                    btn.html('Sign In');
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            showToastError(errors[key][0]);
                        });
                    } else {
                        showToastError(xhr.responseJSON.message);
                    }
                }
            });
        });
    </script>
</body>

</html>
