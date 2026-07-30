@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="History" module="Stock Mutations">
        <li class="breadcrumb-item">Stock</li>
        <li class="breadcrumb-item">Stock Mutations</li>
    </x-page-header>

    <div class="row g-2 align-items-center mb-4 mt-3 justify-content-between p-sm-0">
        <div class="col-12 col-lg-auto">
            <button class="btn btn-light-primary d-flex align-items-center justify-content-between w-100" type="button"
                data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false"
                aria-controls="collapseExample">
                <div>
                    <i class="ph-duotone ph-funnel icon-search me-2"></i>
                    <span>Data Filter</span>
                </div>
                <i data-feather="chevron-down" class="icon-search ms-3"></i>
            </button>
        </div>
        <div class="col">
            <div class="d-flex flex-column flex-lg-row gap-2 justify-content-end">
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
                        <i class="ph-duotone ph-star icon-search"></i>
                        <select class="form-control w-100" id="fl_tipe">
                            <option value="">All Type</option>
                            <option value="Masuk">Masuk</option>
                            <option value="Keluar">Keluar</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-lg-3 text-start mb-2 mb-lg-0">
                    <div class="form-search w-100">
                        <i class="ph-duotone ph-house icon-search"></i>
                        <select class="form-control w-100" id="fl_source">
                            <option value="">All Source</option>
                            <option value="External">External</option>
                            @foreach ($allGudang as $g)
                                <option value="{{ $g->id }}">{{ $g->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-lg-3 text-start mb-2 mb-lg-0">
                    <div class="form-search w-100">
                        <i class="ph-duotone ph-house icon-search"></i>
                        <select class="form-control w-100" id="fl_target">
                            <option value="">All Target</option>
                            <option value="External">External</option>
                            @foreach ($allGudang as $g)
                                <option value="{{ $g->id }}">{{ $g->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-lg-3 text-start mb-2 mb-lg-0">
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
            </div>
        </div>
    </div>

    @php
        $thead = ['No', 'Type', 'Source', 'Target', 'Item', 'Variant', 'Qty', 'Options'];
    @endphp
    <x-datatable :thead=$thead :filter="null">
    </x-datatable>

    <div id="modalDetail" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
        aria-labelledby="modalEditTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/dataTables.fixedColumns.min.js') }}"></script>
    <script type="text/javascript">
        $("#modalDetail").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-content").load(link.attr("href"));
        });

        let table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('stockMutation.index') }}",
                data: function(d) {
                    d.tipe = $('#fl_tipe').val();
                    d.source = $('#fl_source').val();
                    d.target = $('#fl_target').val();
                    d.category = $('#fl_category').val();
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
                    class: 'text-center py-lg-0 py-sm-1',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'tipe',
                    name: 'tipe',
                    class: 'py-lg-0 py-sm-1 text-center',
                },
                {
                    data: 'source_type',
                    name: 'source_type',
                    class: 'py-lg-0 py-sm-1 text-center',
                },
                {
                    data: 'target_type',
                    name: 'target_type',
                    class: 'py-lg-0 py-sm-1 text-center',
                },
                {
                    data: 'item',
                    name: 'item',
                    class: 'py-lg-0 py-sm-1 text-start',
                },
                {
                    data: 'variant',
                    name: 'variant',
                    class: 'py-lg-0 py-sm-1 text-center',
                },
                {
                    data: 'jumlah',
                    name: 'jumlah',
                    visible: true,
                    class: 'py-lg-0 py-sm-1 text-center',
                },
                {
                    data: 'action',
                    name: 'action',
                    class: 'text-center py-lg-0 py-sm-1',
                    orderable: false,
                    searchable: false,
                },
            ],
            createdRow: function(row, data, dataIndex) {
                var api = this.api();
                $('td', row).each(function(colIndex) {
                    // Mengambil title langsung dari konfigurasi kolom DataTables
                    var title = api.column(colIndex).header().textContent.trim();
                    $(this).attr('data-label', title);
                });
            }
        });

        $('#search').keyup(function() {
            table.search($(this).val()).draw();
        });

        $('#fl_source, #fl_target, #fl_category, #fl_tipe').on('change', function() {
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
    </script>
@endpush
