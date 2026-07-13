@extends('layouts.main')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/plugins/datepicker-bs5.min.css') }}" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <x-page-header title="Laporan Transaksi Harian" module="{{ $data_outlet->nama .' - '.tanggalIndo($tanggal) }}" >
                <li class="breadcrumb-item">Laporan</li>
                <li class="breadcrumb-item">Transaksi Harian</li>
                <li class="breadcrumb-item"><a href="{{ route('report.index') }}">Pilih Outlet</a></li>
            </x-page-header>
            <p class="mb-0">{{ $data_outlet->alamat }}</p>
        </div>
        <div class="mb-0 row" style="width: 40%;">
            <div class="col-12">
              <label for="date_transHarian" class="mb-1 fw-bold">Pilih Tanggal</label>              
              <div class="input-group date">
                <input type="text" class="form-control" placeholder="Pilih Tanggal" value="{{ converttanggal($tanggal) }}" id="date_transHarian" />
                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aksi</button>
                <div class="dropdown-menu">
                  <a class="dropdown-item d-flex align-items-center" href="{{ route('report.harian.ekspor', ['ido' => $data_outlet->id, 'tgl' => $tanggal]) }}"><i class="ph-duotone ph-microsoft-excel-logo"></i> <span>Download Excel</span></a>
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
                    <img src="{{ asset('assets/images/widget/img-status-5.svg') }}" alt="img" class="img-fluid img-bg">
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
                      <h3 class="text-white f-w-700 d-flex align-items-center m-b-0">{{ rupiah($total_harian_outlet) }}</h3>
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
                        <div class="form-search" style="display:block;">
                            <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                            <input type="search" id="search" class="form-control" placeholder="Cari data disini...">
                        </div>
                    </div>
                    @php
                        $thead = ['No', 'Customer', 'Total', 'Metode', 'Bayar', 'Kembali', 'Dibuat', 'Opsi'];
                    @endphp
                    <x-datatable :thead=$thead :filter="null">
                    </x-datatable>
                </div>
                
                <div class="col-12 mb-4">
                    <h3 class="mb-3">Transaksi Gerobak</h3>
                    <div class="row">
                        @foreach($data_outlet->gerobak as $gerobak)
                        
                        <div class="col-4">
                            <div class="pc-component">
                                <div class="card">
                                  <div class="card-header py-3">
                                    <div class="d-flex align-items-center justify-content-start mb-2">
                                        <h5 class="me-2">{{ $gerobak->nama }}</h5> 
                                        @if ($gerobak->trans_master != null && $gerobak->trans_master->trans_status == 'Approved')
                                        <span class="badge rounded-pill text-bg-success">Approved</span>
                                        @elseif ($gerobak->trans_master != null && $gerobak->trans_master->trans_status == 'Pending')
                                        <span class="badge rounded-pill text-bg-info">Sedang Ditinjau</span>
                                        @else
                                        @endif
                                    </div>
                                    <p class="mb-0 mt-0" style="font-size:13px;">{{ $gerobak->employee->firstname }} {{ $gerobak->employee->lastname }}</p>
                                  </div>
                                  <ul class="list-group list-group-flush border-top-0">
                                    @php 
                                        $total_penjualan = 0; 
                                    @endphp
                                    @if ($gerobak->trans->count() > 0) 
                                        @foreach ($gerobak->trans as $trans)
                                        <li class="list-group-item" style="padding: 5px 25px;">
                                          <div class="d-flex align-items-center">
                                            @if($trans->row_type == 'varian')
                                              <i class="ms-2 me-2 material-icons-two-tone">subdirectory_arrow_right</i>
                                            @endif       
                                            <div class="flex-grow-1 me-2">
                                              <h6 class="mb-0">{{ $trans->product->nama }}</h6>
                                              @php 
                                                if ($trans->row_type == 'varian') {
                                                    $subtotal        = $trans->qty * $trans->product_harga; 
                                                } else {
                                                    $subtotal        = $trans->original_product_sales * $trans->product_harga; 
                                                } 
                                                $total_penjualan     = $total_penjualan + $subtotal;
                                              @endphp
                                              <p class="mt-1 mb-0"> {{ rupiah($subtotal) }}</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                              @if($trans->row_type == 'varian')
                                              <span class="badge bg-secondary rounded-pill">{{ $trans->qty }}</span>
                                              @else
                                              <span class="badge bg-secondary rounded-pill">{{ $trans->product_sales }} / {{ $gerobak->stock[$trans->product_id]->sum('stock') ?? 0 }}</span>
                                              @endif
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                    @endif
                                    @if ($gerobak->trans_nominal->count() > 0)
                                        @php 
                                            $total_nominal = 0; 
                                        @endphp
                                        @foreach ($gerobak->trans_nominal as $trans_nominal)
                                        <li class="list-group-item" style="padding: 5px 25px;">
                                          <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-2">
                                              <h6 class="mb-0">{{ $trans_nominal->metode_bayar }}</h6>
                                            </div>
                                            <div class="flex-shrink-0">
                                              @php 
                                                $total_nominal = $total_nominal + $trans_nominal->transaction_amount;
                                              @endphp
                                              <p class="mt-1 mb-0">{{ rupiah($trans_nominal->transaction_amount) }}</p>
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                    @endif
                                    @if ($gerobak->pengeluaran->count() > 0)
                                        <li class="list-group-item" style="padding: 5px 25px;">
                                          <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-2">
                                              <h6 class="mb-0">Total Pengeluaran</h6>
                                            </div>
                                            <div class="flex-shrink-0">
                                              <p class="my-1 text-danger">{{ rupiah($gerobak->pengeluaran->sum('pengeluaran_harga')) }}</p>
                                            </div>
                                          </div>
                                        </li>
                                    @endif
                                    @php
                                        if ($gerobak->pengeluaran->count() > 0) {
                                            $total_penjualan_after_pengeluaran = $total_penjualan - $gerobak->pengeluaran->sum('pengeluaran_harga');
                                        } else {
                                            $total_penjualan_after_pengeluaran = $total_penjualan;
                                        }
                                    @endphp
                                    @if ($gerobak->trans->count() > 0 && $gerobak->trans_nominal->count() > 0)
                                        <li class="list-group-item" style="padding: 5px 25px;">
                                          <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-2">
                                              <h6 class="mb-0">Total Penjualan</h6>
                                              @if ($total_nominal == $total_penjualan_after_pengeluaran)
                                              <p class="mt-1 mb-0 text-success">Balanced</p>
                                              @elseif ($total_nominal > $total_penjualan_after_pengeluaran)
                                              <p class="mt-1 mb-0 text-info">+ {{ rupiah($total_nominal - $total_penjualan_after_pengeluaran) }}</p>
                                              @else
                                              <p class="mt-1 mb-0 text-danger">- {{ rupiah($total_penjualan_after_pengeluaran - $total_nominal) }}</p>
                                              @endif
                                            </div>
                                            <div class="flex-shrink-0 text-end">
                                              <p class="mt-1 mb-0">{{ rupiah($total_penjualan) }}</p>
                                              @if ($gerobak->pengeluaran->count() > 0)
                                              <p class="mt-0 mb-0 text-danger">-{{ rupiah($gerobak->pengeluaran->sum('pengeluaran_harga')) }}</p>
                                              <p class="mt-0 mb-0 fw-bold">{{ rupiah($total_penjualan_after_pengeluaran) }}</p>
                                              @endif
                                            </div>
                                          </div>
                                        </li>
                                    @endif
                                  </ul>
                                </div>
                            </div>
                        </div>
                        
                        @endforeach
                    </div>
                </div>
                
                <div class="col-12 mb-5 mx-0">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h3 class="text-danger">Pengeluaran</h3>
                        <div class="form-search" style="display:block;">
                            <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                            <input type="search" id="search2" class="form-control" placeholder="Cari data disini...">
                        </div>
                    </div>
                    @php
                        $thead = ['No', 'Tipe', 'Gerobak', 'Pengeluaran', 'Jumlah', 'Status'];
                    @endphp
                    <x-datatable id="myTable_2" :thead=$thead :filter="null">
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
    
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/dataTables.fixedColumns.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
    
    <script type="text/javascript">
        // input group layout
        function formatDateToYMD(dateString) {
            const parts = dateString.split('/'); // ["06", "11", "2025"]
            const month = parts[0];
            const day = parts[1];
            const year = parts[2];
            return `${year}-${month}-${day}`; // "2025-11-06"
        }
        
        (function () {
          const d_week = new Datepicker(document.querySelector('#date_transHarian'), {
            buttonClass: 'btn',
            autohide: true,
          });
        })();
        
        $('#date_transHarian').on('changeDate', function () {
            const selectedDate = $(this).val(); // Format: YYYY-MM-DD
            const formatedSelectedDate = formatDateToYMD(selectedDate);
            if (selectedDate) {
                const ido = "{{ $data_outlet->id }}";
                var route = "{{ route('report.harian', ['ido' => ':ido', 'tgl' => ':tgl']) }}";
                route = route.replace(':ido', ido).replace(':tgl', formatedSelectedDate);
                showLoader(),
                window.location.href = route;
            }
        });
        
        $("#modalEdit").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-content").load(link.attr("href"));
        });
            
        let table = $("#myTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url : "{{ route('report.harian', ['ido' => $data_outlet->id, 'tgl' => $tanggal]) }}",
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
                { data: 'trans_outlet_metode',      name: 'trans_outlet_metode', },
                { data: 'trans_outlet_bayar',       name: 'trans_outlet_bayar', },
                { data: 'trans_outlet_kembali',     name: 'trans_outlet_kembali', },
                { data: 'updated_at',               name: 'updated_at', visible: true, },
                { data: 'action',                   name: 'action', class: 'text-center', orderable: false, searchable: false, },
            ]
        });
        
        let table2 = $("#myTable_2").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('report.harian', ['ido' => $data_outlet->id, 'tgl' => $tanggal]) }}",
                data: { type: 'pengeluaran' }
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
                { data: 'tipe',             name: 'tipe', },
                { data: 'gerobak',          name: 'gerobak', class: 'text-start', },
                { data: 'pengeluaran_nama', name: 'pengeluaran_nama', class: 'text-start', },
                { data: 'jumlah',           name: 'jumlah', class: 'text-end', },
                { data: 'status',           name: 'status', class: 'text-center', },
            ]
        });

        $('#search').keyup(function() {
            table.search($(this).val()).draw();
        });
        $('#search2').keyup(function() {
            table2.search($(this).val()).draw();
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
        
        $(document).on('click', '.btn-delete', function () {
            let id = $(this).data('id');
            var url = "{{ route('transaction.outlet.hapus', ':id:') }}";
            var url = url.replace(':id:', id);
            
            if (confirm('Yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: showLoader(),
                    success: function (res) {
                        table.ajax.reload(null, false);
                        hideLoader();
                        showToastSuccess("Berhasil menghapus data");
                    },
                    error: function () {
                        hideLoader();
                        showToastError("Terjadi kesalahan saat menghapus data");
                    }
                });
            }
        });
    </script>
@endpush
