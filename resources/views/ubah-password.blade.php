@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-6 col-xl-4">
            <div class="card user-card">
                <div class="card-body">
                    {{-- <div class="user-cover-bg">
                        <img src="../assets/images/application/img-user-cover-1.jpg" alt="image" class="img-fluid">
                        <div class="cover-data">
                            <div class="d-inline-flex align-items-center">
                                <i class="ph-duotone ph-star text-warning me-1"></i>
                                4.5 <small class="text-white text-opacity-50">/ 5</small>
                            </div>
                        </div>
                    </div>
                    <div class="chat-avtar card-user-image">
                        <img src="../assets/images/user/avatar-1.jpg" alt="user-image" class="img-thumbnail rounded-circle">
                        <i class="chat-badge bg-success"></i>
                    </div> --}}
                    <div class="d-flex flex-wrap gap-2">
                        <div class="flex-grow-1">
                            {{-- <h6 class="mb-1">{{ $data->nama }}</h6> --}}
                            <h6 class="mb-1">Asta Nadi Karya Utama</h6>
                            {{-- <p class="text-muted text-sm mb-0"><a href="#" class="text-primary">{{ $data->email }}</a> --}}
                            <p class="text-muted text-sm mb-0"><a href="#" class="text-primary">ID : 12345678</a>
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <button class="btn btn-danger btn-sm">Nonaktifkan</button>
                        </div>
                    </div>
                    <div class="row g-3 my-3 text-center">
                        <div class="col-6">
                            <h5 class="mb-0">102</h5>
                            <small class="text-muted">SDM</small>
                        </div>
                        <div class="col-6 border border-top-0 border-bottom-0 border-end-0">
                            <h5 class="mb-0">3</h5>
                            <small class="text-muted">Unit</small>
                        </div>
                    </div>
                    <div class="saprator my-3">
                        <span>Industri</span>
                    </div>
                    <div class="text-center">
                        <span
                            class="badge bg-light-secondary border rounded-pill border-secondary bg-transparent f-14 me-1 mt-1">Outsourcing</span>
                    </div>
                    {{-- <div class="saprator my-3">
                        <span>Task completed</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 me-2">
                            <div class="progress" style="height: 8px">
                                <div class="progress-bar bg-primary" style="width: 60%"></div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <h6 class="mb-0">30%</h6>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-8">
            <x-card title="">
                {{ $data }}
                {{ $setting }}

            </x-card>
        </div>
    </div>
@endsection
