@extends('layouts.main')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/plugins/datepicker-bs5.min.css') }}" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <x-page-header title="Laporan Transaksi Bulanan" module="{{ $data_outlet->nama .' - '.$bulan_tahun }}" >
                <li class="breadcrumb-item">Laporan</li>
                <li class="breadcrumb-item">Transaksi Bulanan</li>
                <li class="breadcrumb-item"><a href="{{ route('report.indexBulanan') }}">Pilih Outlet</a></li>
            </x-page-header>
            <p class="mb-0">{{ $data_outlet->alamat }}</p>
        </div>
        <div class="mb-0 row" style="width: 40%;">
            <div class="col-12">
              <label for="date_transBulanan" class="mb-1 fw-bold">Pilih Periode Bulan</label>
              <div class="input-group date">
                <input type="text" class="form-control" placeholder="Pilih Tanggal" value="{{ $bulan_tahun_input }}" id="date_transBulanan" />
                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aksi</button>
                <div class="dropdown-menu">
                  <a class="dropdown-item d-flex align-items-center" href="{{ route('report.bulanan.ekspor', ['ido' => $data_outlet->id, 'tgls' => $awal, 'tgle' => $akhir]) }}"><i class="ph-duotone ph-microsoft-excel-logo"></i> <span>Download Excel</span></a>
                </div>
              </div>
            </div>
        </div>
    </div>
    
    <div class="d-block mb-4 mt-5">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="card statistics-card-1 overflow-hidden mb-0">
                  <div class="card-body">
                    <img src="{{ asset('assets/images/widget/img-status-4.svg') }}" alt="img" class="img-fluid img-bg">
                    <h5 class="mb-4">Pendapatan Outlet</h5>
                    <div class="d-flex align-items-center mt-3">
                      <h3 class="f-w-700 d-flex align-items-center m-b-0">{{ rupiah($total_out_transaksi) }}</h3>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <p class="text-muted mb-1 text-sm mb-0">Cash</p> 
                        <span class="mb-0 fw-bold">{{ rupiah($trans_out_cash) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-1 mb-0">
                        <p class="text-muted mb-1 text-sm mb-0">Qris</p> 
                        <span class="mb-0 fw-bold">{{ rupiah($trans_out_qris) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-1 mb-0">
                        <p class="text-muted mb-1 text-sm mb-0">Pengeluaran</p> 
                        <span class="mb-0 fw-bold">{{ rupiah($data_pengeluaran_outlet->sum('pengeluaran_harga')) }}</span>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card statistics-card-1 overflow-hidden mb-0">
                  <div class="card-body">
                    <img src="{{ asset('assets/images/widget/img-status-4.svg') }}" alt="img" class="img-fluid img-bg">
                    <h5 class="mb-4">Pendapatan Gerobak</h5>
                    <div class="d-flex align-items-center mt-3">
                      <h3 class="f-w-700 d-flex align-items-center m-b-0">{{ rupiah($total_gerobak_transaksi) }}</h3>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <p class="text-muted mb-1 text-sm mb-0">Cash</p> 
                        <span class="mb-0 fw-bold">{{ rupiah($trans_gerobak_cash) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-1 mb-0">
                        <p class="text-muted mb-1 text-sm mb-0">Qris</p> 
                        <span class="mb-0 fw-bold">{{ rupiah($trans_gerobak_qris) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-1 mb-0">
                        <p class="text-muted mb-1 text-sm mb-0">Pengeluaran</p> 
                        <span class="mb-0 fw-bold">{{ rupiah($data_pengeluaran_gerobak->sum('pengeluaran_harga')) }}</span>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card statistics-card-1 overflow-hidden bg-brand-color-3">
                  <div class="card-body">
                    <img src="{{ asset('assets/images/widget/img-status-6.svg') }}" alt="img" class="img-fluid img-bg">
                    <h5 class="mb-4 text-white">Total Pendapatan</h5>
                    <div class="d-flex align-items-center mt-3">
                      <h3 class="text-white f-w-700 d-flex align-items-center m-b-0">{{ rupiah($total_bulanan_outlet) }}</h3>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <p class="text-white mb-1 text-sm mb-0">Outlet</p> 
                        <span class="mb-0 fw-bold text-white">{{ rupiah($total_out_transaksi) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-1 mb-0">
                        <p class="text-white mb-1 text-sm mb-0">Gerobak</p> 
                        <span class="mb-0 fw-bold text-white">{{ rupiah($total_gerobak_transaksi) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-1 mb-0">
                        <p class="text-white mb-1 text-sm mb-0">Pengeluaran</p> 
                        <span class="mb-0 fw-bold text-white">{{ rupiah($total_pengeluaran) }}</span>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card statistics-card-1 overflow-hidden mb-0">
                  <div class="card-body">
                    <img src="{{ asset('assets/images/widget/img-status-4.svg') }}" alt="img" class="img-fluid img-bg">
                    <h5 class="mb-4">Pendapatan Cake</h5>
                    <div class="d-flex align-items-center mt-3">
                      <h3 class="f-w-700 d-flex align-items-center m-b-0">{{ rupiah($total_out_transaksi_cake) }}</h3>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <p class="text-muted mb-1 text-sm mb-0">Cash</p> 
                        <span class="mb-0 fw-bold">{{ rupiah($trans_out_cash_cake) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-1 mb-0">
                        <p class="text-muted mb-1 text-sm mb-0">Qris</p> 
                        <span class="mb-0 fw-bold">{{ rupiah($trans_out_qris_cake) }}</span>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-12 mb-5 mx-0">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h3>Transaksi Outlet</h3>
                        
                        <div class="d-flex align-items-center justify-content-between">
                            <a class="btn btn-dark text-white shadow-sm" style="min-width: 160px !important;" href="{{ route('report.bulanan.pdf', ['ido' => $data_outlet->id, 'tgls' => $awal, 'tgle' => $akhir]) }}"><i class="feather icon-download me-2"></i> <span>Download PDF</span></a>
                            
                            <div class="form-search ms-2" style="display:block;">
                                <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                                <input type="search" id="search" class="form-control" placeholder="Cari data disini...">
                            </div>
                        </div>
                    </div>
                    @php
                        $thead = ['No', 'Customer', 'Total', 'Metode', 'Bayar', 'Kembali', 'Dibuat', 'Opsi'];
                    @endphp
                    <x-datatable :thead=$thead :filter="null"></x-datatable>
                </div>
                
                <div class="col-12 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h3>Transaksi Gerobak</h3>
                        <div class="form-search" style="display:block;">
                            <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                            <input type="search" id="search_2" class="form-control" placeholder="Cari data disini...">
                        </div>
                    </div>
                    @php
                        $thead = ['No', 'Gerobak', 'Produk Terjual', 'Cash', 'Qris', 'Total', 'Opsi'];
                    @endphp
                    <x-datatable id="myTable_2" :thead=$thead :filter="null"></x-datatable>
                    
                </div>
                
                <div class="col-12 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h3 class="text-danger">Pengeluaran</h3>
                        <div class="form-search" style="display:block;">
                            <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                            <input type="search" id="search_3" class="form-control" placeholder="Cari data disini...">
                        </div>
                    </div>
                    @php
                        $thead = ['No', 'Tipe', 'Gerobak', 'Pengeluaran', 'Jumlah', 'Tanggal', 'Status'];
                    @endphp
                    <x-datatable id="myTable_3" :thead=$thead :filter="null">
                    </x-datatable>
                </div>
            </div>
        </div>
    </div>
    
    <div id="modalEdit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalEditTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
    
    <div id="modalTransGerobak" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalTransGerobakTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
    
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/dataTables.fixedColumns.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
    <script type="text/javascript">
        function getStartAndEndOfMonth(dateStr) {
          const [month, year] = dateStr.split('/');
          const startDate = `${year}-${month}-01`;
          const lastDay = new Date(year, parseInt(month), 0).getDate();
          const endDate = `${year}-${month}-${lastDay.toString().padStart(2, '0')}`;
          return { startDate, endDate };
        }

        (function () {
          const d_month = new Datepicker(document.querySelector('#date_transBulanan'), {
            buttonClass: 'btn',
            autohide: true,
            format: 'mm/yyyy',
            language: 'id',
            startView: 1,
            pickLevel: 1,
          });
        })();
        
        $('#date_transBulanan').on('changeDate', function () {
            const selectedDate = $(this).val();
            const { startDate, endDate } = getStartAndEndOfMonth(selectedDate);
            if (selectedDate) {
                const ido = "{{ $data_outlet->id }}";
                var route = "{{ route('report.bulanan', ['ido' => ':ido', 'tgls' => ':awal', 'tgle' => ':akhir']) }}";
                route = route.replace(':ido', ido).replace(':awal', startDate).replace(':akhir', endDate);
                showLoader(),
                window.location.href = route;
            }
        });
        
        $(document).on('click', '.btn-detail-outlet', function (e) {
            showLoader();
            e.preventDefault();
            let href = $(this).attr('href');
        
            $('#modalEdit .modal-content').load(href, function () {
                const modalEl = document.getElementById('modalEdit');
                const modal = new bootstrap.Modal(modalEl);
                hideLoader();
                modal.show();
            });
        });
        
        $(document).on('click', '.btn-detail-gerobak', function (e) {
            showLoader();
            e.preventDefault();
            let href = $(this).attr('href');
        
            $('#modalTransGerobak .modal-content').load(href, function () {
                const modalEl = document.getElementById('modalTransGerobak');
                const modal = new bootstrap.Modal(modalEl);
                hideLoader();
                modal.show();
            });
        });
            
        let table = $("#myTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('report.bulanan', ['ido' => $data_outlet->id, 'tgls' => $awal, 'tgle' => $akhir]) }}",
                data: { type: 'outlet' }
            },
            scrollY: '300px',
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
                { data: 'trans_outlet_nama',        name: 'trans_outlet_nama', },
                { data: 'trans_total',              name: 'trans_total', },
                { data: 'trans_outlet_metode',      name: 'trans_outlet_metode', class: 'text-center', },
                { data: 'trans_outlet_bayar',       name: 'trans_outlet_bayar', class: 'text-end', },
                { data: 'trans_outlet_kembali',     name: 'trans_outlet_kembali', class: 'text-end', },
                { data: 'updated_at',               name: 'updated_at', class: 'text-center', visible: true, },
                { data: 'action',                   name: 'action', class: 'text-center', orderable: false, searchable: false, },
            ]
        });
        let table2 = $("#myTable_2").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('report.bulanan', ['ido' => $data_outlet->id, 'tgls' => $awal, 'tgle' => $akhir]) }}",
                data: { type: 'gerobak' }
            },
            scrollY: '300px',
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
                { data: 'gerobak_nama',     name: 'gerobak_nama', },
                { data: 'produk_terjual',   name: 'produk_terjual', class: 'text-center', },
                { data: 'total_cash',       name: 'total_cash', class: 'text-end', },
                { data: 'total_qris',       name: 'total_qris', class: 'text-end', },
                { data: 'total_all',        name: 'total_all', class: 'text-end', },
                { data: 'action',           name: 'action', class: 'text-center', orderable: false, searchable: false, },
            ],
            footerCallback: function (row, data, start, end, display) {
                let api = this.api();
        
                // Parse angka untuk rupiah
                let parseRupiah = function (i) {
                    if (typeof i === 'string') {
                        return i.replace(/[^0-9]/g, '') * 1;
                    }
                    if (typeof i === 'number') {
                        return i;
                    }
                    return 0;
                };
            
                // Parse angka biasa (produk terjual)
                let parseNumber = function (i) {
                    if (typeof i === 'string') {
                        return parseInt(i) || 0;
                    }
                    if (typeof i === 'number') {
                        return i;
                    }
                    return 0;
                };
        
                // Total Produk Terjual (kolom index 2)
                let totalProduk = 0;
                api.column(2, { search: 'applied' }).nodes().each(function (cell) {
                    let val = $(cell).text().replace(/[^0-9]/g, '');
                    totalProduk += parseInt(val) || 0;
                });
            
                // Total Cash (kolom index 3)
                let totalCash = api
                    .column(3, { search: 'applied' })
                    .data()
                    .reduce((a, b) => parseRupiah(a) + parseRupiah(b), 0);
            
                // Total Qris (kolom index 4)
                let totalQris = api
                    .column(4, { search: 'applied' })
                    .data()
                    .reduce((a, b) => parseRupiah(a) + parseRupiah(b), 0);
            
                // Total Semua (kolom index 5)
                let grandTotal = api
                    .column(5, { search: 'applied' })
                    .data()
                    .reduce((a, b) => parseRupiah(a) + parseRupiah(b), 0);
        
                // Update footer
                $(api.column(1).footer()).html("TOTAL").css('text-align', 'left');
                $(api.column(2).footer()).html(totalProduk.toLocaleString()).css('text-align', 'center');
                $(api.column(3).footer()).html(`Rp. ${totalCash.toLocaleString()}`).css('text-align', 'right');
                $(api.column(4).footer()).html(`Rp. ${totalQris.toLocaleString()}`).css('text-align', 'right');
                $(api.column(5).footer()).html(`Rp. ${grandTotal.toLocaleString()}`).css('text-align', 'right');
            }
        });
        let table3 = $("#myTable_3").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('report.bulanan', ['ido' => $data_outlet->id, 'tgls' => $awal, 'tgle' => $akhir]) }}",
                data: { type: 'pengeluaran' }
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
                { data: 'tipe',             name: 'tipe', },
                { data: 'gerobak',          name: 'gerobak', class: 'text-start', },
                { data: 'pengeluaran_nama', name: 'pengeluaran_nama', class: 'text-start', },
                { data: 'jumlah',           name: 'jumlah', class: 'text-end', },
                { data: 'tanggal',          name: 'tanggal', class: 'text-start', },
                { data: 'status',           name: 'status', class: 'text-center', },
            ]
        });

        $('#search').keyup(function() {
            table.search($(this).val()).draw();
        });
        $('#search_2').keyup(function() {
            table2.search($(this).val()).draw();
        });
        $('#search_3').keyup(function() {
            table3.search($(this).val()).draw();
        });
        
        function initTooltips() {
            $('[data-bs-toggle="tooltip"], [title]').each(function () {
                new bootstrap.Tooltip(this);
            });
        }
        table.on('draw', initTooltips);
        table2.on('draw', initTooltips);
    </script>
@endpush
