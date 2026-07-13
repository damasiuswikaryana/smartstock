@extends('layouts.main')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/plugins/flatpickr.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="List Stock Master" module="Management Stock Master" >
        <li class="breadcrumb-item">Stock Opnam</li>
        <li class="breadcrumb-item">Stock Master</li>
    </x-page-header>
    
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div class="col-6 d-flex">
            <button type="button" class="btn btn-shadow btn-warning me-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalAddStock"><i class="ph-duotone ph-plus-circle icon-search me-2"></i> Stock Masuk</button>
            <button type="button" class="btn btn-shadow btn-secondary me-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalTransferStock"><i class="ph-duotone ph-arrows-left-right icon-search me-2"></i> Transfer Stock</button>
            <button type="button" class="btn btn-shadow btn-danger me-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalKeluarStock"><i class="ph-duotone ph-minus-circle icon-search me-2"></i> Stock Keluar</button>
            
            <a href="{{ route('stock-opnam.master.ekspor_stock') }}" class="btn btn-shadow btn-success" data-bs-toggle="tooltip" title="Download ke Excel">
                <i class="ph-duotone ph-download-simple icon-search"></i>
            </a>
        </div>
        <div class="col-6 text-end">
            <div class="form-search">
                <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                <input type="search" id="search" class="form-control" placeholder="Cari data disini...">
            </div>
        </div>
    </div>

    @php
        $thead = ['No', 'Item', 'Jumlah & Satuan'];
    @endphp
    <x-datatable :thead=$thead :filter="null">
    </x-datatable>
    
    @php
        $thead = ['No', 'Tanggal', 'Jenis', 'Item', 'Jumlah', 'Satuan', 'Keterangan'];
    @endphp
    <div class="mb-2 mt-5">
        <h3>Mutasi Stock Master</h3>
        <div class="d-flex justify-content-between align-items-center">
            <div class="col-8 d-flex">
                <div class="col-4 me-2">
                    <div class="input-group">
                        <input type="text" id="daterange_1" class="form-control flatpickr-input" placeholder="Pilih rentang waktu" readonly="readonly" style="padding-top: 0.62rem; padding-bottom: 0.62rem;">
                        <span class="input-group-text"><i class="feather icon-calendar"></i></span>
                    </div>
                </div>
                <div class="col-4 me-2">
                    <select class="form-select" id="jenis_filter" style="padding-top: 0.62rem; padding-bottom: 0.62rem;">
                      <option value="">Semua Jenis</option>
                      <option value="Masuk">Masuk</option>
                      <option value="Keluar">Keluar</option>
                    </select>
                </div>
                <div class="col-3 me-2">
                    <select class="form-select" id="outlet_filter" style="padding-top: 0.62rem; padding-bottom: 0.62rem;">
                      <option value="">Semua Outlet</option>
                      @foreach($outlet as $o)
                      <option value="{{ $o->id }}">{{ $o->nama }}</option>
                      @endforeach
                    </select>
                </div>
                <div class="col-1">
                    <a id="btn-export-mutasi" href="{{ route('stock-opnam.master.ekspor_mutasi', ['s' => $awal, 'e' => $akhir]) }}" class="btn btn-shadow btn-secondary" data-bs-toggle="tooltip" title="Download ke Excel">
                        <i class="ph-duotone ph-download-simple icon-search"></i>
                    </a>
                </div>
            </div>
            <div class="col-4 text-end">
                <div class="form-search">
                    <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                    <input type="search" id="search2" class="form-control" placeholder="Cari data disini...">
                </div>
            </div>
        </div>
    </div>
    <x-datatable :thead=$thead :filter="null" id="myTable_2">
    </x-datatable>
    
    <!--modal add stock-->
    <div id="modalAddStock" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalAddStockTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddStockTitle">Tambah Stock Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" action="{{ route('stock-opnam.master_store') }}" method="post" id="form-tambah">
                    @csrf
                    @method('POST')
                    <div class="px-4">
                        <div class="mb-0 row">
                            <div class="col-lg-12">
                                <div id="produk-container">
                                    <div class="row p-0 mx-0 mb-2 produk-item">
                                        <div class="col-5 col-lg-5 ps-0">
                                            <select class="form-control" name="produk[0][id_item]" required>
                                                <option value="" selected disabled>Pilih Item</option>
                                                @foreach ($item as $i)
                                                    <option value="{{ $i->id }}">{{ $i->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3 col-lg-3 ps-0">
                                            <input type="number" class="form-control" placeholder="Jumlah item" name="produk[0][jumlah]" required />
                                        </div>
                                        <div class="col-3 col-lg-3 ps-0">
                                            <select class="form-control" name="produk[0][id_satuan]" required>
                                                <option value="" selected disabled>Pilih Satuan</option>
                                                @foreach ($satuan as $s)
                                                    <option value="{{ $s->id }}">{{ $s->satuan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-1 col-lg-1 mx-0 pe-0">
                                            <button id="btn-delete-0" type="button" class="btn btn-rounded btn-light-danger btn-delete-produk" style="font-size:20px;">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-0 p-2">
                                    <a href="#" id="btn-add-product" class="btn btn-light-success w-100 d-flex justify-content-center align-items-center">
                                        <i class="fa fa-plus-circle me-2"></i> 
                                        <span>Tambah Item Stock</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="form-tambah">Submit Data</button>
                </div>
            </div>
        </div>
    </div>
    
    <!--modal transfer stock-->
    <div id="modalTransferStock" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalTransferStockTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTransferStockTitle">Transfer Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" action="{{ route('stock-opnam.master_transfer') }}" method="post" id="form-transfer">
                    @csrf
                    @method('POST')
                    <div class="px-4">
                        <div class="mb-0 row">
                            <div class="col-lg-12">
                                <div class="mb-3 row">
                                    <div class="col-lg-12">
                                        <select class="form-control" name="id_outlet" id="id_outlet" required>
                                            <option value="" selected disabled>Pilih Outlet</option>
                                            @foreach ($outlet as $ot)
                                            <option value="{{ $ot->id }}">{{ $ot->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="transfer-container">
                                    <div class="row p-0 mx-0 mb-2 transfer-item">
                                        <div class="col-5 col-lg-5 ps-0">
                                            <select class="form-control" name="transfer[0][id_item]" required>
                                                <option value="" selected disabled>Pilih Item</option>
                                                @foreach ($item as $i)
                                                    <option value="{{ $i->id }}">{{ $i->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3 col-lg-3 ps-0">
                                            <select class="form-control" name="transfer[0][id_satuan]" required>
                                                <option value="" selected disabled>Pilih Satuan</option>
                                                @foreach ($satuan as $s)
                                                    <option value="{{ $s->id }}">{{ $s->satuan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3 col-lg-3 ps-0">
                                            <input type="number" class="form-control jumlah-input-transfer" placeholder="Jumlah item" name="transfer[0][jumlah]" required />
                                        </div>
                                        <div class="col-1 col-lg-1 mx-0 pe-0">
                                            <button id="btn-delete-transfer-0" type="button" class="btn btn-rounded btn-light-danger btn-delete-transfer" style="font-size:20px;">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-0 p-2">
                                    <a href="#" id="btn-add-transfer" class="btn btn-light-success w-100 d-flex justify-content-center align-items-center">
                                        <i class="fa fa-plus-circle me-2"></i> 
                                        <span>Tambah Item Stock</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="form-transfer">Submit Data</button>
                </div>
            </div>
        </div>
    </div>
    
    <!--modal stock keluar-->
    <div id="modalKeluarStock" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalKeluarStockTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKeluarStockTitle">Stock Keluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" action="{{ route('stock-opnam.master_keluar') }}" method="post" id="form-keluar">
                    @csrf
                    @method('POST')
                    <div class="px-4">
                        <div class="mb-0 row">
                            <div class="col-lg-12">
                                <div id="keluar-container">
                                    <div class="row p-0 mx-0 mb-2 keluar-item">
                                        <div class="col-3 col-lg-3 ps-0">
                                            <select class="form-control" name="keluar[0][id_item]" required>
                                                <option value="" selected disabled>Pilih Item</option>
                                                @foreach ($item as $i)
                                                    <option value="{{ $i->id }}">{{ $i->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-2 col-lg-2 ps-0">
                                            <select class="form-control" name="keluar[0][id_satuan]" required>
                                                <option value="" selected disabled>Pilih Satuan</option>
                                                @foreach ($satuan as $s)
                                                    <option value="{{ $s->id }}">{{ $s->satuan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3 col-lg-3 ps-0">
                                            <input type="number" class="form-control jumlah-input-keluar" placeholder="Jumlah item" name="keluar[0][jumlah]" required />
                                        </div>
                                        <div class="col-3 col-lg-3 ps-0">
                                            <input type="text" class="form-control" placeholder="Keterangan" name="keluar[0][keterangan]" required />
                                        </div>
                                        <div class="col-1 col-lg-1 mx-0 pe-0">
                                            <button id="btn-delete-keluar-0" type="button" class="btn btn-rounded btn-light-danger btn-delete-keluar" style="font-size:20px;">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-0 p-2">
                                    <a href="#" id="btn-add-keluar" class="btn btn-light-success w-100 d-flex justify-content-center align-items-center">
                                        <i class="fa fa-plus-circle me-2"></i> 
                                        <span>Tambah Item Stock</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="form-keluar">Submit Data</button>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/dataTables.fixedColumns.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>
    <script type="text/javascript">
        flatpickr(document.querySelector('#daterange_1'), {
            mode: 'range',
            dateFormat: "Y-m-d",
            onChange: function (selectedDates, dateStr) {
                if (dateStr.includes(" to ")) {
                    const [start, end] = dateStr.split(" to ");
                    let baseUrl = "{{ route('stock-opnam.master.ekspor_mutasi', ['s' => 'xxx', 'e' => 'yyy']) }}";
                    baseUrl = baseUrl.replace('xxx', start).replace('yyy', end);
                    $('#btn-export-mutasi').attr('href', baseUrl);
                }
            },
        });
        let table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url : "{{ route('stock-opnam.master_index') }}",
                data: { type: 'stock' }
            },
            scrollY: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
              leftColumns: 1,
              rightColumns: 1
            },
            lengthMenu: [30, 60, 90, 120, 150],
            "dom": '<"my-0"t><"d-flex justify-content-between align-items-center mx-3 mb-2"<"d-flex justify-content-start mx-2" <"me-2 pt-2"l>><"pt-2"p>>',
            order: [
                [0, 'asc']
            ],
            columns: [
                { data: null, name: 'no', class: 'text-center', orderable: false, searchable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'item',         name: 'item', class: 'text-start' },
                { data: 'jumlah',       name: 'jumlah', class: 'text-start' },
            ]
        });
        
        let table2 = $("#myTable_2").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('stock-opnam.master_index') }}",
                data: function (d) {
                    d.type = 'riwayat';
                    d.jenis = $('#jenis_filter').val();
                    d.outlet = $('#outlet_filter').val();
                    d.daterange = $('#daterange_1').val();
                    d.search = $('#search2').val();
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
            columns: [
                { data: null, name: 'no',  orderable: false, searchable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'tanggal',          name: 'tanggal', class: 'text-start' },
                { data: 'jenis',            name: 'jenis', class: 'text-center' },
                { data: 'item',             name: 'item', class: 'text-start' },
                { data: 'jumlah',           name: 'jumlah', class: 'text-center' },
                { data: 'satuan',           name: 'satuan', class: 'text-center' },
                { data: 'keterangan',       name: 'keterangan', class: 'text-start' },
            ]
        });

        $('#search').keyup(function() {
            table.search($(this).val()).draw();
        });
        $('#search2').keyup(function() {
            table2.search($(this).val()).draw();
        });
        $('#jenis_filter, #daterange_1, #outlet_filter').on('change', function () {
            table2.ajax.reload();
        });
        
        table.on('draw', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipTriggerList1 = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            tooltipTriggerList1.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
        
        $(document).ready(function () {
            $(document).on('click', '.btn-delete-produk', function() {
                $(this).closest('.produk-item').remove();
            });
            $(document).on('click', '.btn-delete-transfer', function() {
                $(this).closest('.transfer-item').remove();
            });
            $(document).on('click', '.btn-delete-keluar', function() {
                $(this).closest('.keluar-item').remove();
            });
            
            let itemIndex = 1;
            let itemIndexTransfer = 1;
            let itemIndexKeluar = 1;
            $('#btn-add-product').on('click', function (e) {
                e.preventDefault();
                let html = `
                <div class="row p-0 mx-0 mb-2 produk-item">
                    <div class="col-5 col-lg-5 ps-0">
                        <select class="form-control" name="produk[${itemIndex}][id_item]" required>
                            <option value="" selected disabled>Pilih Item</option>
                            @foreach ($item as $i)
                                <option value="{{ $i->id }}">{{ $i->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 col-lg-3 ps-0">
                        <input type="number" class="form-control" placeholder="Jumlah item" name="produk[${itemIndex}][jumlah]" required />
                    </div>
                    <div class="col-3 col-lg-3 ps-0">
                        <select class="form-control" name="produk[${itemIndex}][id_satuan]" required>
                            <option value="" selected disabled>Pilih Satuan</option>
                            @foreach ($satuan as $s)
                                <option value="{{ $s->id }}">{{ $s->satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-1 col-lg-1 mx-0 pe-0">
                        <button id="btn-delete-${itemIndex}" type="button" class="btn btn-rounded btn-light-danger btn-delete-produk" style="font-size:20px;">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
                `;
        
                $('#produk-container').append(html);
                itemIndex++;
            });
            $('#btn-add-transfer').on('click', function (e) {
                e.preventDefault();
                let html = `
                <div class="row p-0 mx-0 mb-2 transfer-item">
                    <div class="col-5 col-lg-5 ps-0">
                        <select class="form-control" name="transfer[${itemIndexTransfer}][id_item]" required>
                            <option value="" selected disabled>Pilih Item</option>
                            @foreach ($item as $i)
                                <option value="{{ $i->id }}">{{ $i->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 col-lg-3 ps-0">
                        <select class="form-control" name="transfer[${itemIndexTransfer}][id_satuan]" required>
                            <option value="" selected disabled>Pilih Satuan</option>
                            @foreach ($satuan as $s)
                                <option value="{{ $s->id }}">{{ $s->satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 col-lg-3 ps-0">
                        <input type="number" class="form-control jumlah-input-transfer" placeholder="Jumlah item" name="transfer[${itemIndexTransfer}][jumlah]" required />
                    </div>
                    
                    <div class="col-1 col-lg-1 mx-0 pe-0">
                        <button id="btn-delete-transfer-${itemIndexTransfer}" type="button" class="btn btn-rounded btn-light-danger btn-delete-transfer" style="font-size:20px;">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
                `;
        
                $('#transfer-container').append(html);
                itemIndexTransfer++;
            });
            $('#btn-add-keluar').on('click', function (e) {
                e.preventDefault();
                let html = `
                <div class="row p-0 mx-0 mb-2 keluar-item">
                    <div class="col-3 col-lg-3 ps-0">
                        <select class="form-control" name="keluar[${itemIndexKeluar}][id_item]" required>
                            <option value="" selected disabled>Pilih Item</option>
                            @foreach ($item as $i)
                                <option value="{{ $i->id }}">{{ $i->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2 col-lg-2 ps-0">
                        <select class="form-control" name="keluar[${itemIndexKeluar}][id_satuan]" required>
                            <option value="" selected disabled>Pilih Satuan</option>
                            @foreach ($satuan as $s)
                                <option value="{{ $s->id }}">{{ $s->satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 col-lg-3 ps-0">
                        <input type="number" class="form-control jumlah-input-keluar" placeholder="Jumlah item" name="keluar[${itemIndexKeluar}][jumlah]" required />
                    </div>
                    <div class="col-3 col-lg-3 ps-0">
                        <input type="text" class="form-control" placeholder="Keterangan" name="keluar[${itemIndexKeluar}][keterangan]" required />
                    </div>
                    <div class="col-1 col-lg-1 mx-0 pe-0">
                        <button id="btn-delete-keluar-${itemIndexKeluar}" type="button" class="btn btn-rounded btn-light-danger btn-delete-keluar" style="font-size:20px;">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
                `;
        
                $('#keluar-container').append(html);
                itemIndexKeluar++;
            });
            
            $('#form-tambah').on('submit', function (e) {
                e.preventDefault();
                let form = $(this);
                let formData = form.serialize();
        
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    beforeSend: function () {
                        showLoader();
                    },
                    success: function (res) {
                        if (res.success) {
                            hideLoader();
                            $('#modalAddStock').modal('hide');
                            table.ajax.reload(null, false);
                            table2.ajax.reload(null, false);
                            showToastSuccess(res.message);
                            $('#produk-container').empty();
                            itemIndex = 0;
                        } else {
                            hideLoader();
                            showToastError(res.message);
                            console.log(res.message);
                        }
                    },
                    error: function (xhr) {
                        hideLoader();
                        showToastError("Error:" + xhr.responseText);
                        console.log(xhr.responseText);
                    }
                });
            });
            $('#form-transfer').on('submit', function (e) {
                e.preventDefault();
                let form = $(this);
                let formData = form.serialize();
        
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    beforeSend: function () {
                        showLoader();
                    },
                    success: function (res) {
                        if (res.success) {
                            hideLoader();
                            $('#modalTransferStock').modal('hide');
                            table.ajax.reload(null, false);
                            table2.ajax.reload(null, false);
                            showToastSuccess(res.message);
                            $('#transfer-container').empty();
                            itemIndexTransfer = 0;
                        } else {
                            hideLoader();
                            showToastError(res.message);
                            console.log(res.message);
                        }
                    },
                    error: function (xhr) {
                        hideLoader();
                        showToastError("Error:" + xhr.responseText);
                        console.log(xhr.responseText);
                    }
                });
            });
            $('#form-keluar').on('submit', function (e) {
                e.preventDefault();
                let form = $(this);
                let formData = form.serialize();
        
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    beforeSend: function () {
                        showLoader();
                    },
                    success: function (res) {
                        if (res.success) {
                            hideLoader();
                            $('#modalKeluarStock').modal('hide');
                            table.ajax.reload(null, false);
                            table2.ajax.reload(null, false);
                            showToastSuccess(res.message);
                            $('#keluar-container').empty();
                            itemIndexKeluar = 0;
                        } else {
                            hideLoader();
                            showToastError(res.message);
                            console.log(res.message);
                        }
                    },
                    error: function (xhr) {
                        hideLoader();
                        showToastError("Error:" + xhr.responseText);
                        console.log(xhr.responseText);
                    }
                });
            });
            
            // ajax select transfer
            $(document).on('change', 'select[name^="transfer"][name$="[id_item]"]', function () {
                const $row          = $(this).closest('.transfer-item');
                const itemId        = $(this).val();
                const index         = $row.index();
                const satuanSelect  = $row.find('select[name^="transfer"][name$="[id_satuan]"]');
                const jumlahInput   = $row.find('input[name^="transfer"][name$="[jumlah]"]');
                satuanSelect.html('<option selected disabled>Loading...</option>');
                jumlahInput.val('');
                $.ajax({
                    url: `/stock-opnam/get-satuan-by-item/${itemId}`,
                    method: 'GET',
                    success: function (res) {
                        satuanSelect.empty().append('<option selected disabled>Pilih Satuan</option>');
                        res.forEach(s => {
                            satuanSelect.append(`<option value="${s.id}">${s.satuan}</option>`);
                        });
                    }
                });
            });
            $(document).on('change', 'select[name^="transfer"][name$="[id_satuan]"]', function () {
                const $row          = $(this).closest('.transfer-item');
                const satuanId      = $(this).val();
                const itemId        = $row.find('select[name^="transfer"][name$="[id_item]"]').val();
                const jumlahInput   = $row.find('input[name^="transfer"][name$="[jumlah]"]');
                if (!itemId || !satuanId) return;
                $.ajax({
                    url: `/stock-opnam/get-stock-max`,
                    method: 'GET',
                    data: { item_id: itemId, satuan_id: satuanId },
                    success: function (res) {
                        jumlahInput.attr('max', res.max_stock);
                        jumlahInput.attr('placeholder', `Max: ${res.max_stock}`);
                    }
                });
            });
            $(document).on('input', '.jumlah-input-transfer', function () {
                const max = parseFloat($(this).attr('max'));
                const val = parseFloat($(this).val());
                if (val > max) {
                    $(this).addClass('is-invalid');
                    if ($(this).next('.invalid-feedback').length === 0) {
                        $(this).after(`<div class="invalid-feedback">Jumlah melebihi stok maksimal (${max})</div>`);
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                }
            });
            
            //ajax select keluar 
            $(document).on('change', 'select[name^="keluar"][name$="[id_item]"]', function () {
                const $row          = $(this).closest('.keluar-item');
                const itemId        = $(this).val();
                const index         = $row.index();
                const satuanSelect  = $row.find('select[name^="keluar"][name$="[id_satuan]"]');
                const jumlahInput   = $row.find('input[name^="keluar"][name$="[jumlah]"]');
                satuanSelect.html('<option selected disabled>Loading...</option>');
                jumlahInput.val('');
                $.ajax({
                    url: `/stock-opnam/get-satuan-by-item/${itemId}`,
                    method: 'GET',
                    success: function (res) {
                        satuanSelect.empty().append('<option selected disabled>Pilih Satuan</option>');
                        res.forEach(s => {
                            satuanSelect.append(`<option value="${s.id}">${s.satuan}</option>`);
                        });
                    }
                });
            });
            $(document).on('change', 'select[name^="keluar"][name$="[id_satuan]"]', function () {
                const $row          = $(this).closest('.keluar-item');
                const satuanId      = $(this).val();
                const itemId        = $row.find('select[name^="keluar"][name$="[id_item]"]').val();
                const jumlahInput   = $row.find('input[name^="keluar"][name$="[jumlah]"]');
                if (!itemId || !satuanId) return;
                $.ajax({
                    url: `/stock-opnam/get-stock-max`,
                    method: 'GET',
                    data: { item_id: itemId, satuan_id: satuanId },
                    success: function (res) {
                        jumlahInput.attr('max', res.max_stock);
                        jumlahInput.attr('placeholder', `Max: ${res.max_stock}`);
                    }
                });
            });
            $(document).on('input', '.jumlah-input-keluar', function () {
                const max = parseFloat($(this).attr('max'));
                const val = parseFloat($(this).val());
                if (val > max) {
                    $(this).addClass('is-invalid');
                    if ($(this).next('.invalid-feedback').length === 0) {
                        $(this).after(`<div class="invalid-feedback">Jumlah melebihi stok maksimal (${max})</div>`);
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                }
            });
        });
    </script>
@endpush
