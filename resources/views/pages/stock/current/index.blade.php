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
        <div class="col-12 text-end">
            <div class="form-search">
                <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                <input type="search" id="search" class="form-control" placeholder="Search here...">
            </div>
        </div>
    </div>

    @php
        $thead = ['No', 'Item', 'Variant', 'Werehouse', 'Entity', 'Category', 'Qty', 'Last Update'];
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
            ajax: "{{ route('stockCurrent.index') }}",
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
                    class: 'py-0 fw-bold text-center',
                },
                {
                    data: 'variant',
                    name: 'variant',
                    class: 'py-0 text-center',
                },
                {
                    data: 'werehouse',
                    name: 'werehouse',
                    class: 'py-0 text-center',
                },
                {
                    data: 'entity',
                    name: 'entity',
                    class: 'py-0 text-center',
                },
                {
                    data: 'category',
                    name: 'category',
                    visible: true,
                    class: 'py-0 text-center',
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
