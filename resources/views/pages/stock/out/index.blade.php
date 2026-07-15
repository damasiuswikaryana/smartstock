@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="History" module="Stock Out">
        <li class="breadcrumb-item">Stock</li>
        <li class="breadcrumb-item">Stock Out</li>
    </x-page-header>

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div class="col-6">
            <button type="button" class="btn btn-shadow btn-light-primary me-2 d-flex align-items-center"
                data-bs-toggle="modal" data-bs-target="#exampleModalCenter"><i
                    class="ph-duotone ph-plus-circle icon-search me-2"></i> Add Stock Out</button>
        </div>
        <div class="col-6 text-end">
            <div class="form-search">
                <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                <input type="search" id="search" class="form-control" placeholder="Search here...">
            </div>
        </div>
    </div>

    @php
        $thead = ['Number', 'Project', 'Date', 'Werehouse', 'Items', 'Status', 'Options'];
    @endphp
    <x-datatable :thead=$thead :filter="null">
    </x-datatable>

    <div id="exampleModalCenter" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalCenterTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Stock Out - {{ namaLokasi($gudang) }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" action="#" method="post" id="form-tambah">
                    <div class="px-2">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-6">
                                <h4 class="fw-bold mb-3">Stock Info</h4>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Stock Out Number:</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" placeholder="ASTA/xxx/xxx"
                                            name="stock_out_number" value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Date:</label>
                                    <div class="col-lg-8">
                                        <input type="date" class="form-control" placeholder="Date stock in"
                                            name="out_date" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <h4 class="fw-bold mb-3">Project</h4>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Project:</label>
                                    <div class="col-lg-8">
                                        <select class="form-control" name="pekerjaan_id">
                                            @foreach ($pekerjaan as $pr)
                                                <option value="{{ $pr->id }}">{{ $pr->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Entity:</label>
                                    <div class="col-lg-8">
                                        <select class="form-control" name="entitas_id">
                                            @foreach ($entitas as $et)
                                                <option value="{{ $et->id }}">{{ $et->entitas_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-4">
                                <h4 class="fw-bold mb-3">Items</h4>
                                <div id="produk-container">

                                </div>
                                <div class="row mb-0 p-2">
                                    <a href="#" id="btn-add-product"
                                        class="btn btn-light-primary w-100 d-flex justify-content-center align-items-center">
                                        <i class="fa fa-plus-circle me-2"></i>
                                        <span>Add Item</span>
                                    </a>
                                </div>
                            </div>

                            <div class="col-12">
                                <h4 class="fw-bold mb-3">Notes and Documentation</h4>
                                <div class="mb-1 row">
                                    <div class="col-lg-12">
                                        <label class="col-form-label">Notes:</label>
                                        <textarea type="text" class="form-control" name="notes"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" form="form-tambah">Submit Data</button>
                </div>
            </div>
        </div>
    </div>
    <div id="modalEdit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
        aria-labelledby="modalEditTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/dataTables.fixedColumns.min.js') }}"></script>
    <script type="text/javascript">
        $("#modalEdit").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-content").load(link.attr("href"));
        });

        let table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('stockout.index') }}",
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
                    data: 'so_number',
                    name: 'so_number',
                    class: 'py-1 fw-bold',
                },
                {
                    data: 'entitas',
                    name: 'entitas',
                    class: 'py-1',
                },
                {
                    data: 'date',
                    name: 'date',
                    class: 'py-1',
                },
                {
                    data: 'werehouse',
                    name: 'werehouse',
                    class: 'py-1',
                },
                {
                    data: 'items',
                    name: 'items',
                    class: 'py-1 text-center',
                },
                {
                    data: 'status',
                    name: 'status',
                    visible: true,
                    class: 'py-1 text-center',
                },
                {
                    data: 'action',
                    name: 'action',
                    class: 'text-center py-1',
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

        $('#form-tambah').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('stockout.simpan') }}', // Route untuk simpan data
                method: 'POST',
                data: $(this).serialize(),
                beforeSend: showLoader(),
                success: function(response) {
                    if (response.success) {
                        $('#form-tambah')[0].reset();
                        table.ajax.reload(null, false);
                        hideLoader();
                        $('#exampleModalCenter').modal('hide');
                        showToastSuccess("Data has been added");
                    } else {
                        hideLoader();
                        showToastError(response.message);
                    }
                },
                error: function(xhr) {
                    hideLoader();
                    showToastError("Error while adding data");
                }
            });
        });

        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            var url = "{{ route('stockout.hapus', ':id:') }}";
            var url = url.replace(':id:', id);

            if (confirm('Delete this data?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: showLoader(),
                    success: function(res) {
                        table.ajax.reload(null, false);
                        hideLoader();
                        showToastSuccess("Data has been deleted");
                    },
                    error: function() {
                        hideLoader();
                        showToastError("Error while deleting data");
                    }
                });
            }
        });

        $(document).on('click', '.btn-delete-produk', function() {
            $(this).closest('.produk-item').remove();
        });

        let itemMasterIndex = 0;
        $('#btn-add-product').on('click', function(e) {
            e.preventDefault();
            let html = `
                <div class="row p-0 mx-0 mb-2 produk-item">
                    <div class="col-11 col-lg-11 ps-0">
                        <select data-index="${itemMasterIndex}" class="form-control item-master" name="item[${itemMasterIndex}][id_item]" required>
                            <option value="" selected disabled>Select Item</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        <div class="variant-container mt-2" id="variant-container-${itemMasterIndex}"></div>
                    </div>
                    <div class="col-1 col-lg-1 mx-0 pe-0">
                        <button id="btn-delete-${itemMasterIndex}" type="button" class="btn btn-rounded btn-light-danger btn-delete-produk" style="font-size:20px;">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
                `;

            $('#produk-container').append(html);
            itemMasterIndex++;
        });

        $(document).on('change', '.item-master', function() {
            let itemId = $(this).val();
            let werehouseId = {{ $gudang }};
            let index = $(this).data('index');
            $.ajax({
                url: "{{ route('getVariantStocks', ['id' => ':id', 'whid' => ':wh_id']) }}".replace(':id',
                    itemId).replace(':wh_id', werehouseId),
                type: "GET",
                success: function(res) {
                    let html = '';
                    $.each(res.variants, function(i, variant) {
                        html += `
                    <div class="row mb-2 align-items-center">
                        <div class="col-1 text-center">
                            <i class="fs-3 ph-duotone ph-arrow-elbow-down-right"></i>
                        </div>
                        <div class="col-2">
                            <input type="text" class="form-control" value="${variant.sku_varian}" disabled>
                        </div>
                        <div class="col-5">
                            <input type="text" class="form-control" value="${variant.name_varian}" disabled>
                        </div>
                        <div class="col-2 text-center">
                            <input type="text" class="form-control" value="Stock: ${variant.stok}" disabled>
                        </div>
                        <div class="col-2">
                            <input type="number" min="0" max="${variant.stok}" class="form-control qty-input" data-stock="${variant.stok}" name="item[${index}][variants][${variant.id}][qty]" placeholder="Qty" value="0">
                            <input type="hidden" name="item[${index}][variants][${variant.id}][id_variant]" value="${variant.id}">
                        </div>
                    </div>`;
                    });
                    $('#variant-container-' + index).html(html);
                }
            });
        });

        $(document).on('input change', '.qty-input', function() {
            let stock = parseInt($(this).data('stock'));
            let qty = parseInt($(this).val()) || 0;
            if (qty > stock) {
                $(this).val(stock);
                showToastError("Stock tidak mencukupi");
            }
            if (qty < 0) {
                $(this).val(0);
            }
        });
    </script>
@endpush
