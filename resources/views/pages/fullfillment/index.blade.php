@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="Contract List" module="Contract Fullfillment">
        <li class="breadcrumb-item">Contract Fullfillment</li>
    </x-page-header>

    <div class="row g-2 align-items-center mb-4 mt-3 justify-content-between p-sm-0">
        <div class="col-12 col-lg-auto">
            <div class="d-flex justify-content-start align-items-center">
                <button class="btn btn-shadow btn-light-primary d-flex align-items-center justify-content-between mx-1"
                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false"
                    aria-controls="collapseExample">
                    <div>
                        <i class="ph-duotone ph-funnel icon-search me-2"></i>
                        <span>Data Filter</span>
                    </div>
                    <i data-feather="chevron-down" class="icon-search ms-3"></i>
                </button>
            </div>
        </div>
        <div class="col">
            <div class="d-flex flex-column flex-lg-row gap-2 justify-content-end">
                <div class="">
                </div>
                <div class="">
                    <div class="form-search w-100">
                        <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                        <input type="search" id="search" class="form-control" placeholder="Search here...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border-0 mb-3">
        <div class="collapse" id="collapseExample">
            <div class="row">
                <div class="col-12 col-lg-3 text-start mb-2 mb-lg-0">
                    <div class="form-search w-100">
                        <i class="ph-duotone ph-house icon-search"></i>
                        <select class="form-control w-100" id="fl_status">
                            <option value="">All Status</option>
                            <option value="Active" selected>Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-lg-3 text-start mb-2 mb-lg-0">
                    <div class="form-search w-100">
                        <i class="ph-duotone ph-check-circle icon-search"></i>
                        <select class="form-control w-100" id="fl_entitas">
                            <option value="">All Entity</option>
                            @foreach ($pekerjaan->pluck('entitas')->filter()->unique('id') as $entitas)
                                <option value="{{ $entitas->id }}">
                                    {{ $entitas->entitas_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section>
        <div class="row" id="pekerjaan-container">
        </div>

        <div id="pekerjaan-loading" class="text-center py-5 d-none">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="text-muted mt-2 mb-0">Loading...</p>
        </div>

        <div id="pekerjaan-empty" class="text-center py-5 d-none">
            <i class="ph-duotone ph-magnifying-glass f-40 text-muted"></i>
            <h5 class="mt-3">No data found</h5>
            <p class="text-muted">
                No contract matches the selected filter.
            </p>
        </div>

        <div id="pekerjaan-pagination" class="mt-3"></div>
    </section>
@endsection

@push('js')
    <script>
        function renderPekerjaan(data) {
            let html = '';
            console.log(data);
            data.forEach(function(item) {
                let totalQty = 0;
                if (item.items) {
                    totalQty = item.items.reduce(function(total, item) {
                        return total + Number(item.req_qty || 0);
                    }, 0);
                }
                let realityQty = item.reality_qty ?? 0;
                let realityQtyOut = item.reality_qty_out ?? 0;
                let percentageIn = totalQty > 0 ?
                    Math.round((realityQty / totalQty) * 100) :
                    0;
                let percentageOut = totalQty > 0 ?
                    Math.round((realityQtyOut / totalQty) * 100) :
                    0;
                html += `
                <div class="col-12 col-lg-4 mb-3">
                    <div class="card user-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="mb-0">
                                    ${item.name ?? '-'}
                                </h5>
                                <div class="dropdown">
                                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none"
                                    href="#"
                                    data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical f-18"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item"
                                        href="/fullfillment/detail/${item.id}">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <code>${item.no_kontrak ?? '-'}</code>
                            </div>
                            <ul class="list-group list-group-flush">
                                <!-- Contract Items -->
                                <li class="list-group-item px-0">
                                    <div class="row g-1">
                                        <div class="col-6">
                                            <h6 class="mb-0">Contract Items</h6>
                                            <p class="text-muted mb-0">
                                                <small>All items in contract</small>
                                            </p>
                                        </div>
                                        <div class="col-6 text-end">
                                            <h6 class="mb-1">
                                                ${totalQty}
                                            </h6>
                                        </div>
                                    </div>
                                </li>
                                <!-- Items In -->
                                <li class="list-group-item px-0">
                                    <div class="row g-1">
                                        <div class="col-10">
                                            <h6 class="mb-0">Items In</h6>
                                            <p class="text-muted mb-0">
                                                <small>
                                                    All items in to warehouse as stock
                                                </small>
                                            </p>
                                        </div>
                                        <div class="col-2 text-end">
                                            <h6 class="mb-1">
                                                ${realityQty}
                                            </h6>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-2">
                                                    <div class="progress" style="height: 8px">
                                                        <div class="progress-bar bg-primary"
                                                            style="width: ${percentageIn}%">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h6 class="mb-0">
                                                        ${percentageIn}%
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <!-- Items Completed -->
                                <li class="list-group-item px-0">
                                    <div class="row g-1">
                                        <div class="col-10">
                                            <h6 class="mb-0">Items Completed</h6>
                                            <p class="text-muted mb-0">
                                                <small>
                                                    All items issued and distributed to employee
                                                </small>
                                            </p>
                                        </div>
                                        <div class="col-2 text-end">
                                            <h6 class="mb-1">
                                                ${realityQtyOut}
                                            </h6>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-2">
                                                    <div class="progress" style="height: 8px">
                                                        <div class="progress-bar bg-success"
                                                            style="width: ${percentageOut}%">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h6 class="mb-0">
                                                        ${percentageOut}%
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <!-- Add Items -->
                                <li class="list-group-item px-0 pb-0">
                                    <a href="/fullfillment/add/${item.id}"
                                    class="btn btn-light-primary w-100 mb-0">
                                        Add Items
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>`;
            });
            $('#pekerjaan-container').html(html);
        }

        let currentPage = 1;

        function loadPekerjaan(page = 1) {
            let status = $('#fl_status').val();
            let entitas = $('#fl_entitas').val();
            $('#pekerjaan-loading').removeClass('d-none');
            $('#pekerjaan-empty').addClass('d-none');

            $.ajax({
                url: "{{ route('getFullfillmentAjax') }}",
                type: "GET",
                data: {
                    status: status,
                    entitas: entitas,
                    page: page
                },
                success: function(res) {
                    if (res.success) {
                        renderPekerjaan(res.data);
                        currentPage = res.pagination.current_page;
                        renderPagination(res.pagination);
                        if (res.data.length === 0) {
                            $('#pekerjaan-container').html('');
                            $('#pekerjaan-empty').removeClass('d-none');
                        }
                    } else {
                        showToastError(res.message ?? 'Failed to load data');
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    showToastError('Failed to load data');
                },
                complete: function() {
                    $('#pekerjaan-loading').addClass('d-none');
                }
            });
        }

        $('#fl_status, #fl_entitas').on('change', function() {
            loadPekerjaan(1);
        });

        document.addEventListener('DOMContentLoaded', function() {
            loadPekerjaan(1);
        });
    </script>
@endpush
