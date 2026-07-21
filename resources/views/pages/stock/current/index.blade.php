@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="List Stock" module="Stock Current">
        <li class="breadcrumb-item">Stock</li>
        <li class="breadcrumb-item">Stock Current</li>
    </x-page-header>

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div class="text-start">
            <button class="btn btn-light-primary d-flex align-items-center" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                <i class="ph-duotone ph-funnel icon-search me-2"></i>
                <span>Data Filter</span>
                <i data-feather="chevron-down" class="icon-search ms-3"></i></button>
        </div>
        <div class="text-end d-flex justify-content-between align-items-center">
            <button id="btnDownload" class="btn btn-light-secondary d-flex align-items-center me-3" type="button">
                <i class="ph-duotone ph-download icon-search me-2"></i>
                <span>Download Data</span></button>
            <div class="form-search">
                <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                <input type="search" id="search" class="form-control" placeholder="Search here...">
            </div>
        </div>
    </div>

    <div class="border-0 mb-3">
        <div class="collapse" id="collapseExample">
            <div class="row">
                <div class="col-3 text-start">
                    <div class="form-search w-100">
                        <i class="ph-duotone ph-house icon-search"></i>
                        <select class="form-control w-100" id="fl_werehouse">
                            <option value="">All Werehouses</option>
                            @foreach ($allGudang as $g)
                                <option value="{{ $g->id }}">{{ $g->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-3 text-start">
                    <div class="form-search w-100">
                        <i class="ph-duotone ph-package icon-search"></i>
                        <select class="form-control w-100" id="fl_category">
                            <option value="">All Categories</option>
                            @foreach ($allCategory as $c)
                                <option value="{{ $c->id }}">{{ $c->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-3 text-start">
                    <div class="form-search w-100">
                        <i class="ph-duotone ph-star icon-search"></i>
                        <select class="form-control w-100" id="fl_entitas">
                            <option value="">All Entities</option>
                            @foreach ($allEntitas as $e)
                                <option value="{{ $e->id }}">{{ $e->entitas_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $thead = ['No', 'Item', 'Variant', 'Category', 'Werehouse', 'Qty', 'Last Update'];
    @endphp
    <x-datatable :thead=$thead :filter="null">
    </x-datatable>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/dataTables.fixedColumns.min.js') }}"></script>
    <script type="text/javascript">
        let table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('stockCurrent.index') }}",
                data: function(d) {
                    d.gudang = $('#fl_werehouse').val();
                    d.category = $('#fl_category').val();
                    d.entitas = $('#fl_entitas').val();
                }
            },
            scrollY: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1,
                rightColumns: 1
            },
            lengthMenu: [10, 20, 30, 40, 50, 100],
            "dom": '<"my-0"t><"d-flex justify-content-between align-items-center mx-3 mb-2"<"d-flex justify-content-start mx-2" <"me-2 pt-2"l>><"pt-2"p>>',
            order: [
                [0, 'asc']
            ],
            columns: [{
                    data: null,
                    name: 'no',
                    class: 'text-center py-1',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'item',
                    name: 'item',
                    class: 'py-0',
                },
                {
                    data: 'variant',
                    name: 'variant',
                    class: 'py-0 text-center',
                },
                {
                    data: 'category',
                    name: 'category',
                    visible: true,
                    class: 'py-0 text-center',
                },
                {
                    data: 'werehouse',
                    name: 'werehouse',
                    class: 'py-0 text-start',
                },

                {
                    data: 'qty',
                    name: 'qty',
                    visible: true,
                    class: 'py-0 text-center',
                },
                {
                    data: 'last_update',
                    name: 'last_update',
                    visible: true,
                    class: 'py-0 text-center',
                }
            ]
        });

        $('#search').keyup(function() {
            table.search($(this).val()).draw();
        });

        $('#fl_werehouse, #fl_category, #fl_entitas').on('change', function() {
            table.ajax.reload();
        });

        table.on('draw', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipTriggerList1 = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            tooltipTriggerList1.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        $('#btnDownload').on('click', function() {
            let warehouse = $('#fl_werehouse').val() || 'all';
            let category = $('#fl_category').val() || 'all';
            let entitas = $('#fl_entitas').val() || 'all';

            let url =
                "{{ route('stockCurrent.downloadReport', ['whid' => ':whid', 'cat' => ':cat', 'entitas' => ':entitas']) }}"
                .replace(':whid', warehouse).replace(':cat', category).replace(':entitas', entitas);

            const link = document.createElement('a');
            link.href = url;
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            link.click();
        });
    </script>
@endpush
