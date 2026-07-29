@extends('layouts.main')

@section('title')
    - Dashboard
@endsection

@push('css')
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <x-page-header title="Dashboard" module="Dashboard">
            </x-page-header>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between py-3">
                    <div>
                        <h5>Stock In</h5>
                        <p class="text-muted mb-0 mt-0">Showing data in {{ BulanTahun($tahun . '-' . $bulan . '-01') }}</p>
                    </div>
                </div>
                <div class="card-body">
                    <select id="wh_so_in" class="form-select form-select-sm w-auto shadow-none ps-0 mb-0 mt-0 pt-0"
                        name="warehouse_id">
                        @foreach ($gudang as $g)
                            <option value="{{ $g->id }}">{{ $g->nama }}</option>
                        @endforeach
                    </select>
                    <div class="d-flex align-items-center justify-content-between">
                        <div id="ct_si" class="d-none">
                            <div class="d-flex flex-column align-items-start mt-1 mb-2">
                                <h1 class="mb-0" id="val_si">x</h1>
                                <p class="text-muted mb-0 mt-0">Stock In Recorded</p>
                            </div>
                        </div>
                        <div class="w-100 mb-3 d-none" id="pc_si">
                            <div class="row">
                                <div class="placeholder-glow">
                                    <span class="placeholder col-10"></span>
                                    <span class="placeholder col-8"></span>
                                    <span class="placeholder col-4"></span>
                                </div>
                            </div>
                        </div>
                        <div class="avtar bg-brand-color-1 text-white">
                            <i class="ph-duotone ph-arrow-square-in f-26"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between py-3">
                    <div>
                        <h5>Stock Out</h5>
                        <p class="text-muted mb-0 mt-0">Showing data in {{ BulanTahun($tahun . '-' . $bulan . '-01') }}</p>
                    </div>
                </div>
                <div class="card-body">
                    <select id="wh_so_out" class="form-select form-select-sm w-auto shadow-none ps-0 mb-0 mt-0 pt-0"
                        name="warehouse_id">
                        @foreach ($gudang as $g)
                            <option value="{{ $g->id }}">{{ $g->nama }}</option>
                        @endforeach
                    </select>
                    <div class="d-flex align-items-center justify-content-between">
                        <div id="ct_so" class="d-none">
                            <div class="d-flex flex-column align-items-start mt-1 mb-2">
                                <h1 class="mb-0" id="val_so">x</h1>
                                <p class="text-muted mb-0 mt-0">Stock Out Recorded</p>
                            </div>
                        </div>
                        <div class="w-100 mb-3 d-none" id="pc_so">
                            <div class="row">
                                <div class="placeholder-glow">
                                    <span class="placeholder col-10"></span>
                                    <span class="placeholder col-8"></span>
                                    <span class="placeholder col-4"></span>
                                </div>
                            </div>
                        </div>
                        <div class="avtar bg-brand-color-2 text-white">
                            <i class="ph-duotone ph-arrow-square-out f-26"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between py-3">
                    <div>
                        <h5>Stock Transfer</h5>
                        <p class="text-muted mb-0 mt-0">Showing data in {{ BulanTahun($tahun . '-' . $bulan . '-01') }}</p>
                    </div>
                </div>
                <div class="card-body">
                    <select id="wh_so_trf" class="form-select form-select-sm w-auto shadow-none ps-0 mb-0 mt-0 pt-0"
                        name="warehouse_id">
                        @foreach ($gudang as $g)
                            <option value="{{ $g->id }}">{{ $g->nama }}</option>
                        @endforeach
                    </select>
                    <div class="d-flex align-items-center justify-content-between">
                        <div id="ct_strf" class="d-none">
                            <div class="d-flex flex-column align-items-start mt-1 mb-2">
                                <h1 class="mb-0" id="val_strf">x</h1>
                                <p class="text-muted mb-0 mt-0">Stock Transfer Recorded</p>
                            </div>
                        </div>
                        <div class="w-100 mb-3 d-none" id="pc_strf">
                            <div class="row">
                                <div class="placeholder-glow">
                                    <span class="placeholder col-10"></span>
                                    <span class="placeholder col-8"></span>
                                    <span class="placeholder col-4"></span>
                                </div>
                            </div>
                        </div>
                        <div class="avtar bg-brand-color-3 text-white">
                            <i class="ph-duotone ph-arrows-left-right f-26"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-6">
            <div class="card statistics-card-1">
                <div class="card-header d-flex align-items-center justify-content-between py-3">
                    <h5>Contract Fullfillment</h5>
                </div>
                <div class="card-body d-none" id="ct_cf">
                    <img src="{{ asset('assets/images/widget/img-status-2.svg') }}" alt="img"
                        class="img-fluid img-bg" />
                    <div class="d-flex align-items-center">
                        <h1 class="f-w-300 d-flex align-items-center m-b-0 fw-bold" id="val_cf">? <small
                                class="text-muted">/?</small>
                        </h1>
                        <span class="badge bg-light-primary ms-2" id="cf_percentage">?</span>
                    </div>
                    <p class="text-muted mb-2 text-sm mt-3">Completed Fullfillment</p>
                    <div class="progress" style="height: 7px">
                        <div id="cf_progress" class="progress-bar bg-brand-color-1" role="progressbar" style="width: 0%"
                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="card-body" id="pc_cf">
                    <div class="w-100 mb-3">
                        <div class="row">
                            <div class="placeholder-glow">
                                <span class="placeholder col-10"></span>
                                <span class="placeholder col-8"></span>
                                <span class="placeholder col-6"></span>
                                <br>
                                <span class="placeholder col-4"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-6">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between py-3">
                    <div>
                        <h5>Client Projects</h5>
                    </div>
                </div>
                <div class="card-body pb-3">
                    <select id="entitas_id" class="form-select form-select-sm w-auto shadow-none ps-0 mb-0 mt-0 pt-0"
                        name="entitas_id">
                        <option value="all">All Entity</option>
                        @foreach ($entitas as $e)
                            <option value="{{ $e->id }}">{{ $e->entitas_name }}</option>
                        @endforeach
                    </select>
                    <div class="d-flex align-items-center justify-content-between">
                        <div id="ct_clients" class="d-none">
                            <div class="d-flex flex-column align-items-start mt-1 mb-2">
                                <h1 class="mb-0" id="val_clients">x</h1>
                                <p class="text-muted mb-0 mt-0">Projects registered</p>
                            </div>
                        </div>
                        <div class="w-100 mb-3 d-none" id="pc_clients">
                            <div class="row">
                                <div class="placeholder-glow">
                                    <span class="placeholder col-10"></span>
                                    <span class="placeholder col-8"></span>
                                    <span class="placeholder col-4"></span>
                                </div>
                            </div>
                        </div>
                        <div class="avtar bg-brand-color-2 text-white">
                            <i class="ph-duotone ph-cube f-26"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-xl-6">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between pb-3">
                    <div>
                        <h5>Most Stock Items</h5>
                        <select id="wh_most_item"
                            class="form-select form-select-sm w-auto shadow-none ps-0 mb-0 mt-0 pt-0" name="warehouse_id">
                            @foreach ($gudang as $g)
                                <option value="{{ $g->id }}">{{ $g->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-body d-none" id="ct_item_most">
                    <div id="overview-bar-chart"></div>
                    <div id="top-items-detail" class="mt-3"></div>
                </div>
                <div class="card-body" id="pc_item_most">
                    <div class="w-100 mb-3">
                        <div class="row">
                            <div class="placeholder-glow">
                                <span class="placeholder col-10"></span>
                                <span class="placeholder col-8"></span>
                                <span class="placeholder col-6"></span>
                                <br>
                                <span class="placeholder col-4"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5>Item Categories</h5>
                        <p class="text-muted mb-0 mt-0">Showing all categories data with total items</p>
                    </div>
                </div>
                <div class="card-body d-none" id="ct_categories">
                    <div id="categories-chart"></div>
                    <div id="categories_detail" class="mt-3"></div>
                </div>
                <div class="card-body" id="pc_categories">
                    <div class="w-100 mb-3">
                        <div class="row">
                            <div class="placeholder-glow">
                                <span class="placeholder col-10"></span>
                                <span class="placeholder col-8"></span>
                                <span class="placeholder col-6"></span>
                                <br>
                                <span class="placeholder col-4"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/widgets/overview-bar-chart.js') }}"></script>
    <script src="{{ asset('assets/js/widgets/performance-chart.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard_load.js') }}"></script>
    <script>
        $(document).ready(function() {
            loadStockIn();
            loadStockOut();
            loadStockTrf();
            loadClients();
            loadContractFullfillment();
            loadTopItems();
            loadCategories();
        });
        $('#wh_so_in').on('change', function() {
            loadStockIn();
        });
        $('#wh_so_out').on('change', function() {
            loadStockOut();
        });
        $('#wh_so_trf').on('change', function() {
            loadStockTrf();
        });
        $('#entitas_id').on('change', function() {
            loadClients();
        });
        $('#wh_most_item').on('change', function() {
            loadTopItems();
        });
    </script>
@endpush
