@extends('layouts.main')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/plugins/datepicker-bs5.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/plugins/flatpickr.min.css') }}" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <x-page-header title="Laporan Transaksi Cake" module="Transaksi Cake" >
                <li class="breadcrumb-item">Laporan</li>
            </x-page-header>
        </div>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-12 mb-5 mx-0">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex gap-2 me-2">
                            <div class="input-group">
                                <input type="text" id="daterange_1" class="form-control flatpickr-input" placeholder="Pilih rentang waktu" readonly="readonly" style="padding-top: 0.62rem; padding-bottom: 0.62rem;">
                                    <span class="input-group-text"><i class="feather icon-calendar"></i></span>
                            </div>
                            <a class="btn btn-dark text-white shadow-sm" style="min-width: 160px !important;" id="btnDownload"><i class="feather icon-download me-2"></i>Unduh Laporan</a>
                        </div>
                        <div class="form-search" style="display:block;">
                            <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                            <input type="search" id="search" class="form-control" placeholder="Cari data disini...">
                        </div>
                    </div>
                    
                    
                    <div class="d-block mb-0 mt-0">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="card statistics-card-1 overflow-hidden bg-brand-color-3">
                                  <div class="card-body">
                                    <img src="{{ asset('assets/images/widget/img-status-6.svg') }}" alt="img" class="img-fluid img-bg">
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-0">
                                      <div>
                                          <h5 class="mb-1 text-white">Total Pendapatan</h5>
                                          <p class="mb-0 text-white">Total pendapatan khusus penjualan produk Bakery and Cake.</p>
                                      </div>
                                      <h3 class="text-white f-w-700 d-flex align-items-center m-b-0" id="revenue_total">{{ rupiah($summary_product->sum('penjualan_rp')) }}</h3>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @php
                        $thead = ['No', 'ID Transaksi', 'Outlet / Gerobak', 'Produk', 'Harga', 'Qty', 'Subtotal'];
                    @endphp
                    <x-datatable :thead=$thead :filter="null"></x-datatable>
                </div>
                
                <div class="col-12" id="summary_wp">
                    <div class="card">
                        <div class="card-header p-3">
                            <h4 class="card-title mb-1">Ringkasan Penjualan</h4>
                            <p class="mb-0">Jumlah penjualan cake dalam satuan pcs</p>
                        </div>
                        <div class="card-body p-1">
                            <ul class="list-group">
                              @foreach($summary_product as $s)    
                              <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                <div class="d-flex align-items-center">
                                    <i class="material-icons-two-tone me-3">arrow_right_alt</i> {{ $s->nama }}
                                </div>
                                <span class="badge text-bg-dark rounded-pill">
                                    {{ $s->penjualan }}
                                </span>
                              </li>
                              @endforeach
                            </ul>
                        </div>
                        <div class="card-footer py-2 px-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-title mb-1">Total Penjualan [Pcs]</h4>
                                <h4><span class="badge text-bg-danger rounded-pill">{{ $summary_product->sum('penjualan') }}</span></h4>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-title mb-1">Total Penjualan [Rp]</h4>
                                <h4><span class="badge text-bg-danger rounded-pill">{{ rupiah($summary_product->sum('penjualan_rp')) }}</span></h4>
                            </div>
                        </div>
                    </div>
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
    <script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>
    
    <script type="text/javascript">
        $("#modalEdit").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-content").load(link.attr("href"));
        });
        
        flatpickr(document.querySelector('#daterange_1'), {
            mode: 'range',
            dateFormat: "Y-m-d",
        });
        
        let table = $("#myTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('report.indexCake') }}",
                data: function (d) {
                    d.daterange = $('#daterange_1').val();
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
                { data: null, name: 'no', class: 'text-center',  orderable: false, searchable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'transaksi',        name: 'transaksi',  class: 'py-0 text-center', },
                { data: 'outlet',           name: 'outlet',     class: 'py-0 text-center' },
                { data: 'produk',           name: 'produk',     class: 'py-0 text-start', },
                { data: 'harga',            name: 'harga',      class: 'py-0 text-end', },
                { data: 'qty',              name: 'qty',        class: 'py-0 text-center', },
                { data: 'subtotal',         name: 'subtotal',   class: 'py-0 text-end', },
            ]
        });

        $('#search').keyup(function() {
            table.search($(this).val()).draw();
        });
        
        $('#daterange_1').on('change', function () {
            let range = $(this).val();
            $.ajax({
                url: "{{ route('report.ajax.getAjaxCake') }}",
                type: "GET",
                data: { range: range },
                success: function(res) {
                    $("#summary_wp").html(res.html);
                    $("#revenue_total").html(res.revenue_total);
                    
                }
            });
            
            table.ajax.reload();
        });
        
        function initTooltips() {
            $('[data-bs-toggle="tooltip"], [title]').each(function () {
                new bootstrap.Tooltip(this);
            });
        }
        table.on('draw', initTooltips);
        
        // Button Download report
        $('#btnDownload').on('click', function(e) {
            e.preventDefault(); // Mencegah perilaku default link (href="#")
    
            // 1. Ambil value dari input daterange
            var periode = $('#daterange_1').val();
    
            // 3. Definisikan Base URL Route Anda
            // Sesuaikan 'url_download' dengan route Laravel/PHP Anda
            var baseUrl = "{{ route('report.cake.ekspor') }}"; 
    
            // 4. Arahkan window ke URL dengan parameter
            // Hasilnya nanti: https://webanda.com/laporan/download?period=2025-10-01+to+2025-10-31
            window.location.href = baseUrl + '?period=' + encodeURIComponent(periode);
        });
    </script>
@endpush
