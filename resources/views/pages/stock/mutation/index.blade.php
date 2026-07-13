@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="History" module="Stock Mutations">
        <li class="breadcrumb-item">Stock</li>
        <li class="breadcrumb-item">Stock Mutations</li>
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
        $thead = ['No', 'Type', 'Source', 'Destination', 'Item', 'Qty', 'Options'];
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
            ajax: "{{ route('stockMutation.index') }}",
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
                    data: 'tipe',
                    name: 'tipe',
                    class: 'py-0 fw-bold text-center',
                },
                {
                    data: 'source_type',
                    name: 'source_type',
                    class: 'py-0 text-center',
                },
                {
                    data: 'target_type',
                    name: 'target_type',
                    class: 'py-0 text-center',
                },
                {
                    data: 'item',
                    name: 'item',
                    class: 'py-0 text-center',
                },
                {
                    data: 'jumlah',
                    name: 'jumlah',
                    visible: true,
                    class: 'py-0 text-center',
                },
                {
                    data: 'action',
                    name: 'action',
                    class: 'text-center py-0',
                    orderable: false,
                    searchable: false,
                },
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
