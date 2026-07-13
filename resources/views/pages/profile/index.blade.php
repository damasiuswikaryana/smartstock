@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/uppy.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="User Profile" module="User Profile">
        <li class="breadcrumb-item">Settings</li>
    </x-page-header>

    <div class="row">
        <div class="col-lg-5 col-xxl-3">
            <div class="card overflow-hidden">
                <div class="card-body position-relative">
                    <div class="text-center mt-3">
                        <div class="chat-avtar d-inline-flex mx-auto">
                            <img class="rounded-circle img-fluid wid-90 img-thumbnail"
                                src="{{ auth()->user()->photo ? asset('storage/user/' . auth()->user()->photo) : asset('assets/images/user/avatar-1.jpg') }}"
                                alt="User image">
                        </div>
                        <h5 class="mb-0">{{ $data->firstname . ' ' . $data->lastname }}</h5>
                        <p class="text-muted text-sm">Username <a href="#"
                                class="link-primary">{{ '@' . $data->username }} </a></p>

                        <button type="button"
                            class="pc-uppy-btn btn btn-secondary w-100 d-flex align-items-center justify-content-center"
                            id="uppyModalOpener">
                            <i class="ti ti-camera me-1"></i> <span class="fw-medium">Change Photo</span>
                        </button>

                    </div>
                </div>
                <div class="nav flex-column nav-pills list-group list-group-flush account-pills mb-0" id="user-set-tab"
                    role="tablist" aria-orientation="vertical">
                    <a class="nav-link list-group-item list-group-item-action" id="user-set-profile-tab"
                        data-bs-toggle="pill" href="#user-set-profile" role="tab" aria-controls="user-set-profile"
                        aria-selected="false" tabindex="-1"><span class="f-w-500"><i
                                class="ph-duotone ph-user-circle m-r-10"></i>Personal Information</span>
                    </a>
                    <a class="nav-link list-group-item list-group-item-action active" id="user-set-passwort-tab"
                        data-bs-toggle="pill" href="#user-set-passwort" role="tab" aria-controls="user-set-passwort"
                        aria-selected="true"><span class="f-w-500"><i class="ph-duotone ph-key m-r-10"></i>Change
                            Password</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-xxl-9">
            <div class="tab-content" id="user-set-tabContent">
                <div class="tab-pane fade" id="user-set-profile" role="tabpanel" aria-labelledby="user-set-profile-tab">
                    <div class="card">
                        <form action="#" method="POST" id="form-profile">
                            @csrf
                            @method('PUT')
                            <div class="card-header">
                                <h5>Personal Information</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item border-bottom-0 px-0 pb-2 pt-0">
                                        <div class="form-floating mb-0">
                                            <input type="text" class="form-control" id="nik"
                                                placeholder="Nomor Induk Kependudukan" name="nik"
                                                value="{{ $data->detail ? $data->detail->nik : '' }}">
                                            <label for="nik">Nomor Induk Kependudukan</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item border-bottom-0 px-0 py-2">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-0">
                                                    <input type="text" class="form-control" id="firstname"
                                                        placeholder="First Name" name="firstname"
                                                        value="{{ $data->firstname }}">
                                                    <label for="firstname">First Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-0">
                                                    <input type="text" class="form-control" id="lastname"
                                                        placeholder="Last Name" name="lastname"
                                                        value="{{ $data->lastname }}">
                                                    <label for="lastname">Last Name</label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item border-bottom-0 px-0 pt-2 pb-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-0">
                                                    <input type="email" class="form-control" id="email"
                                                        placeholder="Email" name="email" value="{{ $data->email }}">
                                                    <label for="email">Email</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-0">
                                                    <input type="text" class="form-control" id="phone"
                                                        placeholder="Nomor Telepon" name="phone"
                                                        value="{{ $data->phone }}">
                                                    <label for="phone">Phone Number</label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer p-2">
                                <button type="submit" class="btn btn-light-primary w-100">Update Profile</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="tab-pane fade active show" id="user-set-passwort" role="tabpanel"
                    aria-labelledby="user-set-passwort-tab">
                    <div class="card">
                        <form action="#" method="POST" id="form-password">
                            @csrf
                            @method('PUT')
                            <div class="card-header">
                                <h5>Change Password</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item border-bottom-0 px-0 pb-2 pt-0">
                                        <div class="form-floating mb-0">
                                            <input type="password" class="form-control" id="old_password"
                                                placeholder="Current Password" name="old_password" value=""
                                                required>
                                            <label for="old_password">Current Password</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item border-bottom-0 px-0 py-2">
                                        <div class="form-floating mb-0">
                                            <input type="password" class="form-control" id="new_password"
                                                placeholder="New Password" name="new_password" value="" required>
                                            <label for="new_password">New Password</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item border-bottom-0 px-0 pb-0 pt-2">
                                        <div class="form-floating mb-0">
                                            <input type="password" class="form-control" id="cfr_password"
                                                placeholder="Confirm Password" name="new_password_confirmation"
                                                value="" required>
                                            <label for="cfr_password">Confirm Password</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer p-2">
                                <button type="submit" class="btn btn-light-primary w-100">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('a[href="' + activeTab + '"]').tab('show');
            }
            $('a[data-bs-toggle="pill"], a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
        });

        $('#form-profile').on('submit', function(e) {
            e.preventDefault();
            var url = "{{ route('update-profile') }}";
            $.ajax({
                url: url,
                type: 'PUT',
                data: $(this).serialize(),
                beforeSend: showLoader(),
                success: function(res) {
                    if (res.success) {
                        hideLoader();
                        showToastSuccess(res.message);
                    } else {
                        hideLoader();
                        showToastError(res.message);
                    }
                },
                error: function() {
                    hideLoader();
                    showToastError("Terjadi kesalahan saat memperbarui data");
                }
            });
        });

        $('#form-password').on('submit', function(e) {
            e.preventDefault();
            var url = "{{ route('update-password') }}";
            $.ajax({
                url: url,
                type: 'PUT',
                data: $(this).serialize(),
                beforeSend: showLoader(),
                success: function(res) {
                    if (res.success) {
                        hideLoader();
                        showToastSuccess(res.message);
                    } else {
                        hideLoader();
                        showToastError(res.message);
                    }
                },
                error: function() {
                    hideLoader();
                    showToastError("Terjadi kesalahan saat update password");
                }
            });
        });
    </script>
    <script src="{{ asset('assets/js/plugins/uppy.min.js') }}"></script>
    <script type="module">
        // Function for displaying uploaded files
        const onUploadSuccess = (elForUploadedFiles) => (file, response) => {
            const url = response.uploadURL;
            const fileName = file.name;
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = url;
            a.target = '_blank';
            a.appendChild(document.createTextNode(fileName));
            li.appendChild(a);
            document.querySelector(elForUploadedFiles).appendChild(li);
        };

        const userId = {{ $data->id }};
        const uploadUrl = `profile/${userId}/upload-foto`;

        import {
            Uppy,
            Dashboard,
            Webcam,
            XHRUpload,
            DragDrop,
            ProgressBar,
        } from 'https://releases.transloadit.com/uppy/v3.23.0/uppy.min.mjs';

        // for popup modal open and upload files
        const uppy = new Uppy({
                debug: true,
                autoProceed: false
            })
            .use(Dashboard, {
                trigger: '#uppyModalOpener'
            })
            .use(Webcam, {
                target: Dashboard
            })
            .use(XHRUpload, {
                endpoint: uploadUrl,
                fieldName: 'file',
                formData: true,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

        uppy.on('success', (fileCount) => {
            console.log(`${fileCount} files uploaded`);
        });
    </script>
@endpush
