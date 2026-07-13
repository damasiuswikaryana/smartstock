@extends('layouts.main')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/plugins/datepicker-bs5.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/plugins/flatpickr.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="List Transaksi" module="Transaksi Event" >
        <li class="breadcrumb-item">Transaksi</li>
        <li class="breadcrumb-item">Transaksi Event</li>
    </x-page-header>
    
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        @if (auth()->user()->is_admin)
        <div class="col-3">
            <button type="button" class="btn btn-shadow btn-warning me-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#exampleModalCenter"><i class="ph-duotone ph-plus-circle icon-search me-2"></i> Transaksi Event Baru</button>
        </div>
        @endif
        <div class="{{ auth()->user()->is_admin ? 'col-9' : 'col-12' }} d-flex justify-content-end align-items-center">
            
            <div class="col-4 me-2">
                <div class="input-group">
                    <input type="text" id="daterange_1" class="form-control flatpickr-input" placeholder="Pilih rentang waktu" readonly="readonly" style="padding-top: 0.62rem; padding-bottom: 0.62rem;">
                        <span class="input-group-text"><i class="feather icon-calendar"></i></span>
                </div>
            </div>
            
            <div class="col-4 form-search">
                <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                <input type="search" id="search" class="form-control" placeholder="Cari data disini...">
            </div>
            
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
                          <p class="mb-0 text-white">Total pendapatan dihitung jika pembayaran event berstatus lunas.</p>
                      </div>
                      <h3 class="text-white f-w-700 d-flex align-items-center m-b-0" id="revenue_total">{{ rupiah($revenue) }}</h3>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    
    @php
        $thead = ['No', 'Booking Date', 'Nama Event', 'Status', 'Lokasi Acara',  'Customer', 'Bank', 'Dibuat', 'Opsi'];
    @endphp
    <x-datatable :thead=$thead :filter="null">
    </x-datatable>
    
     @if (auth()->user()->is_admin)
    <div id="exampleModalCenter" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalEditTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Transaksi Event Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('transaction.event.store') }}" class="modal-body" method="post" id="form-tambah">
                    <div class="px-4">
                        @csrf
                        @method('POST')
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Outlet:</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="id_outlet" id="id_outlet">
                                    <option value="" selected disabled>Pilih Outlet</option>
                                    @foreach ($outletData as $outlet)
                                    <option value="{{ $outlet->id }}">{{ $outlet->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">No. invoice:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" placeholder="Masukan nomor invoice" name="no_invoice" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Nama Event:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" placeholder="Masukan nama event" name="event_nama" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Lokasi Event:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" placeholder="Masukan lokasi event" name="event_lokasi" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Tanggal Event:</label>
                            <div class="col-lg-8 row pe-0">
                                <div class="col-12 col-lg-4">
                                    <input type="text" class="form-control" placeholder="Pilih Tanggal" value="" id="event_date" name="event_date" required />
                                </div>
                                <div class="col-12 col-lg-4 mx-0">
                                    <input type="time" class="form-control" placeholder="Waktu awal event" id="waktu_awal" name="waktu_awal" />
                                </div>
                                <div class="col-12 col-lg-4 mx-0 pe-0">
                                    <input type="time" class="form-control" placeholder="Waktu akhir event" id="waktu_akhir" name="waktu_akhir" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Tenggat Waktu Invoice:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" placeholder="Pilih Tanggal" id="due_date" name="due_date" required />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Customer:</label>
                            <div class="col-lg-8 row pe-0">
                                <div class="col-12 col-lg-6">
                                    <select class="form-control" name="id_customer" id="id_customer">
                                        <option value="" selected disabled>Pilih Customer</option>
                                        @foreach ($customer as $cus)
                                        <option data-company="{{ $cus->company }}" value="{{ $cus->id }}">{{ $cus->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 mx-0 pe-0">
                                    <input type="text" class="form-control" placeholder="Nama Perusahaan" id="company" name="company" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Discount:</label>
                            <div class="col-lg-8 row pe-0">
                                <div class="col-12 col-lg-6">
                                    <select class="form-control" name="discount" id="discount" required>
                                        <option value="percent">Percent</option>
                                        <option value="flat">Flat</option>
                                        <option value="no">Tidak Menggunakan Discount</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 mx-0 pe-0">
                                    <input type="text" class="form-control" placeholder="Jumlah Diskon" id="discount_value" name="discount_value" value=""
                                    />
                                    <small id="taxes_value_editHelp" class="form-text text-danger">Kosongkan jika tidak ada diskon.</small>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Taxes:</label>
                            <div class="col-lg-8 row pe-0">
                                <div class="col-12 col-lg-6">
                                    <select class="form-control" name="taxes" id="taxes" required>
                                        <option value="percent">Percent</option>
                                        <option value="flat">Flat</option>
                                        <option value="no">Tidak Menggunakan Pajak</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 mx-0 pe-0">
                                    <input type="text" class="form-control" placeholder="Nominal Pajak" id="taxes_value" name="taxes_value"
                                        value=""
                                    />
                                    <small id="taxes_value_editHelp" class="form-text text-danger">Kosongkan jika tidak ada biaya pajak.</small>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Nomor Rekening:</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="id_bank_account">
                                    @foreach ($bankAccounts as $ba)
                                    <option value="{{ $ba->id }}">{{ $ba->bank->bank_name.': '.$ba->bank_account_name.' - '.$ba->bank_account_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Catatan:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" placeholder="Masukan catatan" name="event_note">
                            </div>
                        </div>
                        <div class="mb-0 row">
                            <label class="col-lg-4 col-form-label">Produk:</label>
                            <div class="col-lg-8">
                                <div id="produk-container">
                                    
                                </div>
                                <div class="row mb-0 p-2">
                                    <a href="#" id="btn-add-product" class="btn btn-light-success w-100 d-flex justify-content-center align-items-center">
                                        <i class="fa fa-plus-circle me-2"></i> 
                                        <span>Tambah Produk</span>
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
    
    <div id="modalEdit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalEditTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
    @endif
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/dataTables.fixedColumns.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>
    <script type="text/javascript">
        @if (auth()->user()->is_admin)
        function formatDateToYMD(dateString) {
            const parts = dateString.split('/'); // ["06", "11", "2025"]
            const month = parts[0];
            const day = parts[1];
            const year = parts[2];
            return `${year}-${month}-${day}`; // "2025-11-06"
        }
        
        (function () {
          const d_week = new Datepicker(document.querySelector('#event_date'), {
            buttonClass: 'btn',
            autohide: true,
          });
          const d_due = new Datepicker(document.querySelector('#due_date'), {
            buttonClass: 'btn',
            autohide: true,
          });
        })();
        
        $(document).ready(function () {
            $('#id_customer').on('change', function () {
                var selectedOption = $(this).find('option:selected');
                var companyName = selectedOption.data('company') || '';
                $('#company').val(companyName);
            });
            
            $(document).on('click', '.btn-delete-produk', function() {
                $(this).closest('.produk-item').remove();
            });
            
            let produkIndex = 0;
            $('#btn-add-product').on('click', function (e) {
                e.preventDefault();
                let html = `
                <div class="row p-0 mx-0 mb-2 produk-item">
                    <div class="col-6 col-lg-6 ps-0">
                        <select class="form-control" name="produk[${produkIndex}][id_product]" required>
                            <option value="" selected disabled>Pilih Produk</option>
                            @foreach ($product as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-5 col-lg-5 mx-0 pe-0">
                        <input type="number" class="form-control" placeholder="Jumlah produk" name="produk[${produkIndex}][jumlah]" required />
                    </div>
                    <div class="col-1 col-lg-1 mx-0 pe-0">
                        <button id="btn-delete-${produkIndex}" type="button" class="btn btn-rounded btn-light-danger btn-delete-produk" style="font-size:20px;">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
                `;
        
                $('#produk-container').append(html);
                produkIndex++;
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
                            $('#exampleModalCenter').modal('hide');
                            table.ajax.reload(null, false);
                            showToastSuccess(res.message);
                            $('#produk-container').empty();
                            produkIndex = 0;
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
        });
        
        $("#modalEdit").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-content").load(link.attr("href"));
        });
        @endif
        
        flatpickr(document.querySelector('#daterange_1'), {
            mode: 'range',
            dateFormat: "Y-m-d",
        });
            
        let table = $("#myTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('transaction.event.index') }}",
                data: function (d) {
                    d.daterange = $('#daterange_1').val();
                    d.search = $('#search').val();
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
                { data: 'event_date',       name: 'event_date', },
                { data: 'event_nama',       name: 'event_nama', },
                { data: 'status',           name: 'status', },
                { data: 'event_lokasi',     name: 'event_lokasi', },
                { data: 'id_customer',      name: 'id_customer', },
                { data: 'id_bank_account',  name: 'id_bank_account', },
                { data: 'updated_at',       name: 'updated_at', visible: true, },
                { data: 'action',           name: 'action', class: 'text-center', orderable: false, searchable: false, },
            ]
        });

        $('#search').keyup(function() {
            table.search($(this).val()).draw();
        });
        
        $('#daterange_1').on('change', function () {
            let range = $(this).val();
            $.ajax({
                url: "{{ route('transaction.event.ajax.getRevenue') }}",
                type: "GET",
                data: { range: range },
                success: function(res) {
                    $("#revenue_total").html(res.revenue_formatted);
                }
            });
            
            table.ajax.reload();
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
        
        @if (auth()->user()->is_admin)
        $(document).on('click', '.btn-delete', function () {
            let id = $(this).data('id');
            var url = "{{ route('transaction.event.destroy', ':id:') }}";
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
                    error: function (xhr) {
                        hideLoader();
                        showToastError("Error:" + xhr.responseText);
                        console.log(xhr.responseText);
                    }
                });
            }
        });
        @endif
    </script>
    
@endpush
