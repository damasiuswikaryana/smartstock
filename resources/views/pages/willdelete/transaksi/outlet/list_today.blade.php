@extends('layouts.main')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/plugins/uppy.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.min.css') }}" type="text/css" />
@endpush

@section('content')
    <x-page-header title="List Transaksi" module="{{ $data_outlet->nama }} - {{ tanggalIndo(date('Y-m-d')) }}" >
        <li class="breadcrumb-item">Transaksi</li>
        <li class="breadcrumb-item">Transaksi Outlet</li>
    </x-page-header>
    
    <div class="d-block mb-4 mt-3">
        <div class="col-12 mb-3">
            <a href="{{ route('transaction.outlet.create', $data_outlet->id) }}" class="btn btn-lg btn-shadow btn-secondary w-auto d-flex align-items-center">
                <h4 class="text-white mb-0"><i class="fas fa-plus-circle me-2"></i> Transaksi Baru</h4>
            </a>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="card statistics-card-1 overflow-hidden mb-0">
                  <div class="card-body">
                    <img src="{{ asset('assets/images/widget/img-status-4.svg') }}" alt="img" class="img-fluid img-bg">
                    <h5 class="mb-4">Pendapatan Cash</h5>
                    <div class="d-flex align-items-center mt-3">
                      <h3 class="f-w-300 d-flex align-items-center m-b-0">{{ rupiah($trans_cash) }}</h3>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card statistics-card-1 overflow-hidden mb-0">
                  <div class="card-body">
                    <img src="{{ asset('assets/images/widget/img-status-5.svg') }}" alt="img" class="img-fluid img-bg">
                    <h5 class="mb-4">Pendapatan Qris</h5>
                    <div class="d-flex align-items-center mt-3">
                      <h3 class="f-w-300 d-flex align-items-center m-b-0">{{ rupiah($trans_qris) }}</h3>
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
                      <h3 class="text-white f-w-300 d-flex align-items-center m-b-0">{{ rupiah($total_transaksi) }}</h3>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card statistics-card-1 overflow-hidden mb-0">
                  <div class="card-body">
                    <img src="{{ asset('assets/images/widget/img-status-5.svg') }}" alt="img" class="img-fluid img-bg">
                    <h5 class="mb-4">Pendapatan Cake</h5>
                    <div class="d-flex align-items-center mt-3">
                      <h3 class="f-w-300 d-flex align-items-center m-b-0">{{ rupiah($total_transaksi_cake) }}</h3>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <p class="text-muted mb-1 text-sm mb-0">Transaksi cash</p> 
                        <span class="mb-0 fw-bold">{{ rupiah($trans_cash_cake) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-1 mb-0">
                        <p class="text-muted mb-1 text-sm mb-0">Transaksi qris</p> 
                        <span class="mb-0 fw-bold">{{ rupiah($trans_qris_cake) }}</span>
                    </div>
                  </div>
                </div>
            </div>
        </div>
          
        <div class="col-12 mt-3">
            <div class="form-search" style="display:block;">
                <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                <input type="search" id="search" class="form-control" placeholder="Cari data disini..." style="max-width:100%;">
            </div>
        </div>
    </div>
    
    @php
        $thead = ['No', 'Customer', 'Total', 'Metode', 'Bayar', 'Kembali', 'Dibuat', 'Opsi'];
    @endphp
    <x-datatable :thead=$thead :filter="null">
    </x-datatable>
    
    <!--pengeluaran-->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div class="col-xl-12">
            <div class="px-0 mt-4">
                <ul class="list-group" id="wp_pengeluaran">
                  <li class="list-group-item list-group-item-gray">
                      <h4 class="card-title mb-0">Pengeluaran Outlet</h4>
                  </li>
                  @foreach ($data_pengeluaran as $keluar)
                  <li class="list-group-item">
                    <div id="wp-pengeluaran-approved-{{ $keluar->id }}" class="{{ $keluar->pengeluaran_status == 'Approved' ? 'd-flex' : 'd-none' }} justify-content-between align-items-center">
                        <div class="ms-2 me-auto col-6">
                          <div class="fw-bold">Nama Pengeluaran</div>
                          {{ $keluar->pengeluaran_nama }}
                        </div>
                        <div class="ms-2 me-auto col-6">
                          <div class="fw-bold">Jumlah</div>
                          {{ rupiah($keluar->pengeluaran_harga) }}
                        </div>
                    </div>
                    <div id="wp-pengeluaran-pending-{{ $keluar->id }}" class="{{ $keluar->pengeluaran_status == 'Approved' ? 'd-none' : 'd-flex' }} justify-content-between align-items-center">
                        <div class="form-floating mb-0 me-2 col-4">
                            <input type="text" class="form-control" id="pengeluaran-nama-{{ $keluar->id }}" name="pengeluaran-nama-{{ $keluar->id }}" placeholder="Nama Pengeluaran. Ex: Pembelian cup" value="{{ $keluar->pengeluaran_nama }}"
                            >
                            <label for="penjualan-cash">Nama Pengeluaran</label>
                        </div>
                        <div class="form-floating mb-0 me-2 col-4">
                            <input type="text" class="form-control number-separator" id="pengeluaran-harga-{{ $keluar->id }}" name="pengeluaran-harga-{{ $keluar->id }}" placeholder="Jumlah Pengeluaran (Rp)" value="{{ pecahTanpaRp($keluar->pengeluaran_harga) }}"
                            >
                            <label for="penjualan-cash">Jumlah Penjualan (Rp)</label>
                        </div>
                        <div class="form-floating mb-0 me-2 col-2">
                            <button data-id="{{ $keluar->id }}" id="uploadBtn-{{ $keluar->id }}" class="w-100 btn btn-light-secondary btn-lg btn-upload-lampiran">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="ph-duotone ph-camera-plus f-20 me-1"></i> 
                                    <p class="mb-0">Lampiran</p>
                                </div>
                            </button>
                        </div>
                        <div class="col-2 mb-0">
                            <div class="btn-group">
                              <button type="button" id="btn-edit-pengeluaran-{{ $keluar->id }}" class="w-100 btn btn-light-secondary btn-lg">
                                  <div class="d-flex align-items-center justify-content-center">
                                    <i class="ph-duotone ph-check-fat f-20 me-1"></i> 
                                    <p class="mb-0">Simpan</p>
                                  </div>
                              </button>
                              <button type="button" class="btn btn-light-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="sr-only">Toggle Dropdown</span></button>
                              <div class="dropdown-menu" style="">
                                <a class="dropdown-item" id="btn-approve-pengeluaran-{{ $keluar->id }}" href="javascript:void(0);">Approve Pengeluaran</a>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2 d-flex" id="wp-lampiran-{{ $keluar->id }}">
                        @if ($keluar->pengeluaran_photo != null)
                        <p class="p-0 mb-2 fw-bold">Lampiran : </p>
                        <a class="ms-3 text-decoration-underline text-danger" data-lightbox="{{ asset('storage/pengeluaran/'.$keluar->pengeluaran_photo) }}">
                            {{ $keluar->pengeluaran_photo }}
                        </a>
                        @endif
                    </div>
                  </li>
                  @endforeach
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="form-floating mb-0 me-2 col-5">
                        <input type="text" class="form-control" id="pengeluaran-nama" name="pengeluaran-nama" placeholder="Nama Pengeluaran. Ex: Pembelian cup"
                        >
                        <label for="penjualan-cash">Nama Pengeluaran</label>
                    </div>
                    <div class="form-floating mb-0 me-2 col-5">
                        <input type="text" class="form-control number-separator" id="pengeluaran-harga" name="pengeluaran-harga" placeholder="Jumlah Penjualan (Rp)"
                        >
                        <label for="penjualan-cash">Jumlah Pengeluaran (Rp)</label>
                    </div>
                    <div class="col-2 mb-0">
                        <button type="button" id="btn-tambah-pengeluaran" class="w-100 btn btn-light-danger btn-lg">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="ph-duotone ph-plus-circle f-20 me-1"></i> 
                                <p class="mb-0">Tambah</p>
                            </div>
                        </button>
                    </div>
                  </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div id="modalEdit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalEditTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
    
    <div class="modal fade modal-lightbox" id="lightboxModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-body text-center p-2">
              <img src="" alt="images" class="modal-image img-fluid" />
          </div>
        </div>
      </div>
    </div>
    
    @if (session('cetak'))
    <!-- Modal cetak struk -->
    <div class="modal fade" id="modalCetakStruk" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Transaksi Berhasil</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body text-center">
            <p class="mb-3">Ingin mencetak struk?</p>
            <span class="text-center mb-3"  id="status-message"></span>
            
            @if (session('printer') == "iware_xs_80ul")
            <a href="{{ route('transaction.outlet.print', session('cetak')) }}" target="_blank" class="btn btn-primary me-2">
                <i class="ph-duotone ph-printer me-2"></i> Cetak Struk
            </a>
            @else
            <button onclick="printReceipt({{ json_encode(session('data')) }})" class="btn btn-primary me-2">
                <i class="ph-duotone ph-printer me-2"></i> Cetak Struk
            </button>
            @endif
            
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
          </div>
        </div>
      </div>
    </div>
    @else
    <span class="text-center mb-3"  id="status-message"></span>
    <span class="text-center mb-3"  id="status-alert"></span>
    @endif
    
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/uppy.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
    <script src="{{ asset('assets/js/plugins/clipboard.min.js') }}"></script>
    <script src="{{ asset('assets/js/component.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dataTables.fixedColumns.min.js') }}"></script>
    @if (session('cetak'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const modal = new bootstrap.Modal(document.getElementById('modalCetakStruk'));
            modal.show();
        });
    </script>
    @endif
    <script type="module">
        import {
            Uppy,
            Dashboard,
            Webcam,
            XHRUpload,
        } from 'https://releases.transloadit.com/uppy/v3.23.0/uppy.min.mjs';
    
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
        // Loop setiap tombol
        document.querySelectorAll('.btn-upload-lampiran').forEach(button => {
            const pengeluaranId = button.dataset.id;
    
            const uppyInstance = new Uppy({
                debug: true,
                autoProceed: false,
            })
            .use(Dashboard, {
                trigger: `#uploadBtn-${pengeluaranId}`,
                inline: false,
                showProgressDetails: true,
                proudlyDisplayPoweredByUppy: false,
                closeAfterFinish: true,
            })
            .use(Webcam, { target: Dashboard })
            .use(XHRUpload, {
                endpoint: `/pengeluaran/${pengeluaranId}/upload-foto`,
                fieldName: 'file',
                formData: true,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                }
            });
    
            uppyInstance.on('complete', result => {
                console.log(`Upload selesai untuk pengeluaran ${pengeluaranId}`, result.successful);
                showToastSuccess(`Upload selesai untuk pengeluaran ${pengeluaranId}`, result.successful);
                const uploadedFiles = result.successful;
                        
                let lampiranHtml = `<p class="p-0 mb-2 fw-bold">Lampiran : </p>`;
            
                uploadedFiles.forEach(file => {
                    lampiranHtml += `
                        <a class="ms-3 text-decoration-underline text-danger" data-lightbox="${file.uploadURL}">${file.name}</a>`;
                });
                $(`#wp-lampiran-${pengeluaranId}`).html(lampiranHtml);
            });
        });
    </script>
    
    <script type="text/javascript">
        var lightboxModal = new bootstrap.Modal(document.getElementById('lightboxModal'));
        var elem = document.querySelectorAll('[data-lightbox]');
        for (var j = 0; j < elem.length; j++) {
          elem[j].addEventListener('click', function () {
            var images_path = event.target;
            if (images_path.tagName == 'IMG') {
              images_path = images_path.parentNode;
            }
            var recipient = images_path.getAttribute('data-lightbox');
            var image = document.querySelector('.modal-image');
            image.setAttribute('src', recipient);
            lightboxModal.show();
          });
        }
        
        const rupiah = n => (+n).toLocaleString('id-ID');
        
        function renderPengeluaranItem(p) {
            return $(`
              <li class="list-group-item" data-id="${p.id}">
                <div id="wp-pengeluaran-approved-${p.id}" class="d-none justify-content-between align-items-center">
                    <div class="ms-2 me-auto col-6">
                      <div class="fw-bold">Nama Pengeluaran</div>
                      ${p.nama}
                    </div>
                    <div class="ms-2 me-auto col-6">
                      <div class="fw-bold">Jumlah</div>
                      ${rupiah(p.harga)}
                    </div>
                </div>
                <div id="wp-pengeluaran-pending-${p.id}" class="d-flex justify-content-between align-items-center">
                    <div class="form-floating mb-0 me-2 col-5">
                        <input type="text" class="form-control" id="pengeluaran-nama-${p.id}"
                               name="pengeluaran-nama-${p.id}" value="${p.nama}">
                        <label>Nama Pengeluaran</label>
                    </div>
                    <div class="form-floating mb-0 me-2 col-5">
                        <input type="text" class="form-control number-separator"
                               id="pengeluaran-harga-${p.id}"
                               name="pengeluaran-harga-${p.id}"
                               value="${rupiah(p.harga)}">
                        <label>Jumlah Penjualan (Rp)</label>
                    </div>
                    <div class="col-2 mb-0">
                        <div class="btn-group">
                            <button type="button" id="btn-edit-pengeluaran-${p.id}" class="w-100 btn btn-light-secondary btn-lg">
                                <div class="d-flex align-items-center justify-content-center">
                                  <i class="ph-duotone ph-check-fat f-20 me-1"></i> 
                                  <p class="mb-0">Simpan</p>
                                </div>
                            </button>
                            <button type="button" class="btn btn-light-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="sr-only">Toggle Dropdown</span></button>
                            <div class="dropdown-menu" style="">
                                <a class="dropdown-item" id="btn-approve-pengeluaran-${p.id}" href="javascript:void(0);">Approve Pengeluaran</a>
                            </div>
                        </div>
                    </div>
                </div>
              </li>
            `);
        }
        
        $(function () {
            // tambah pengeluaran
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
            $('#btn-tambah-pengeluaran').on('click', function () {
                const nama   = $('#pengeluaran-nama').val().trim();
                const jumlah = $('#pengeluaran-harga').val();
                const id_outlet = "{{ $data_outlet->id }}";
                
                if (!nama || !jumlah) {
                    showToastError("Nama dan jumlah pengeluaran wajib diisi");
                    return;
                }
                $.ajax({
                    url: "{{ route('pengeluaran.store') }}",
                    method: 'POST',
                    data: {
                        pengeluaran_nama: nama,
                        pengeluaran_harga: jumlah,
                        pengeluaran_tipe: "Outlet",
                        id_outlet: id_outlet,
                        id_gerobak: null,
                    },
                    beforeSend: showLoader(),
                    success: function (res) {
                        if (res.success) {
                            showToastSuccess("Pengeluaran berhasil ditambahkan");
                            $('#pengeluaran-nama').val('');
                            $('#pengeluaran-harga').val('');
                            hideLoader();
                            // buat elemen
                            const liBaru = renderPengeluaranItem(res.pengeluaran);
                            $('#btn-tambah-pengeluaran').closest('li').before(liBaru);
                            $('.number-separator', liBaru).number(isNaN);
                        } else {
                            hideLoader();
                            showToastError("Gagal menambahkan pengeluaran");
                        }
                    },
                    error: function (xhr) {
                        hideLoader();
                        const errMsg = xhr.responseJSON?.message || 'Gagal menambah pengeluaran.';
                        showToastError(errMsg);
                    }
                });
            });
            // edit pengeluaran
            $(document).on('click', '[id^="btn-edit-pengeluaran-"]', function () {
                const id        = $(this).attr('id').split('-').pop();    // ambil ID record
                const nama      = $(`#pengeluaran-nama-${id}`).val().trim();
                const jumlah    = $(`#pengeluaran-harga-${id}`).val();
                var url         = "{{ route('pengeluaran.update', ':id') }}".replace(':id', id);
                if (!nama || !jumlah) {
                    showToastError("Nama dan jumlah pengeluaran wajib diisi");
                    return;
                }
                $.ajax({
                    url: url,
                    method: 'PUT',
                    data: {
                        pengeluaran_nama: nama,
                        pengeluaran_harga: jumlah,
                    },
                    beforeSend: showLoader(),
                    success: function (res) {
                        hideLoader();
                        if (res.success) {
                            showToastSuccess("Perubahan berhasil disimpan");
                        } else {
                            showToastError("Gagal mengedit pengeluaran");
                        }
                    },
                    error: function (xhr) {
                        hideLoader();
                        const errMsg = xhr.responseJSON?.message || 'Gagal mengedit pengeluaran.';
                        showToastError(errMsg);
                    }
                });
            });
            // approve pengeluaran
            $(document).on('click', '[id^="btn-approve-pengeluaran-"]', function () {
                const id        = $(this).attr('id').split('-').pop();
                var url         = "{{ route('pengeluaran.approve', ':id') }}".replace(':id', id);
                $.ajax({
                    url: url,
                    method: 'PUT',
                    data: {
                        status: "Approved",
                    },
                    beforeSend: showLoader(),
                    success: function (res) {
                        hideLoader();
                        if (res.success) {
                            $('#wp-pengeluaran-pending-'+id).removeClass('d-flex').addClass('d-none');
                            $('#wp-pengeluaran-approved-'+id).removeClass('d-none').addClass('d-flex');
                            showToastSuccess("Approval berhasil disimpan");
                        } else {
                            showToastError("Gagal approve pengeluaran");
                        }
                    },
                    error: function (xhr) {
                        hideLoader();
                        const errMsg = xhr.responseJSON?.message || 'Gagal approve pengeluaran.';
                        showToastError(errMsg);
                    }
                });
            });
        });
        
        $("#modalEdit").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-content").load(link.attr("href"));
        });
            
        let table = $("#myTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('transaction.outlet.listToday', $data_outlet->id) }}",
            scrollY: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
              leftColumns: 1,
              rightColumns: 1
            },
            lengthMenu: [20, 40, 60, 80, 100, 200],
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

        $('#search').keyup(function() {
            table.search($(this).val()).draw();
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
    
    <!-- Printing Script -->
    <script>
        // UI element references
        // const connectButton = document.getElementById('connect-button');
        const statusMessage = document.getElementById('status-message');
        // const statusAlert = document.getElementById('status-alert');

        // Bluetooth variables
        let device, server, characteristic;
        // Common UUIDs for BLE printers. You may need to change these.
        const SERVICE_UUID = '000018f0-0000-1000-8000-00805f9b34fb';
        const CHARACTERISTIC_UUID = '00002af1-0000-1000-8000-00805f9b34fb';
        
        // Outlet data from Laravel controller (assuming it's available globally)
        const outletData = <?php echo json_encode(session('outlet') ? session('outlet') : ($data_outlet ?? [])); ?>;
        // console.log(outletData);

        const formatRupiah = (amount) => {
            if (typeof amount !== 'number') {
                return 'Rp0';
            }
            // Use Intl.NumberFormat for proper Indonesian currency formatting
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'decimal',
                minimumFractionDigits: 0
            });
            return 'Rp' + formatter.format(amount);
        };
        
        /**
         * Helper function to format a date string.
         * @param {string} dateString The date string from the database.
         * @returns {string} The formatted date string.
         */
        const formatDate = (dateString) => {
            const date = new Date(dateString);
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return date.toLocaleDateString('id-ID', options);
        };
        
        /**
         * Generates the full set of ESC/POS commands for the receipt.
         * @param {object} receiptData The transaction data.
         * @returns {Promise<Uint8Array>} A promise that resolves with the ESC/POS commands.
         */
        async function generatePrintCommands(receiptData) {
            // console.log(receiptData);
            statusMessage.textContent = 'Generating printer commands...';
            // statusAlert.textContent = 'Generating printer commands...';
            const encoder = new TextEncoder();
            
            // Function to create a simple two-column layout using manual spacing
            const createLine = (item, price) => {
                const totalWidth = 32;
                const priceStr = formatRupiah(price);
                
                let shortItem = item;
                const maxItemLength = totalWidth - priceStr.length - 1;
                if (item.length > maxItemLength) {
                    shortItem = item.substring(0, maxItemLength - 3) + '...';
                }

                const spacesNeeded = totalWidth - shortItem.length - priceStr.length;
                return shortItem + ' '.repeat(spacesNeeded) + priceStr;
            };
            

            if (!receiptData || Object.keys(receiptData).length === 0) {
                statusMessage.textContent = 'Error: Receipt data is empty.';
                // statusAlert.textContent = 'Error: Receipt data is empty.';
                return new Uint8Array([]);
            }

            let commands = [];
            
            // Header
            commands.push(
                ...[0x1b, 0x40], // Initialize printer
                ...[0x1b, 0x61, 0x01], // Set alignment to center
                ...[0x1b, 0x45, 0x01], // Set bold on
                ...encoder.encode('SRUUPUT KOPI - ' + outletData.nama + '\n'),
                ...[0x1b, 0x45, 0x00], // Set bold off
                ...[0x1B, 0x4D, 0x01], // Set small font on
                ...encoder.encode(outletData.alamat_print + '\n' + outletData.alamat_print2 + '\n\n'),
                ...[0x1B, 0x4D, 0x00], // Set small font on
                ...[0x1b, 0x61, 0x00], // Set alignment to left
                ...encoder.encode(receiptData.trans_outlet_nama + '\n'),
                ...[0x1B, 0x4D, 0x01], // Set small font on
                ...encoder.encode(formatDate(receiptData.created_at) + '\n'),
                ...[0x1B, 0x4D, 0x00], // Set small font on
                ...encoder.encode('--------------------------------\n'),
                ...encoder.encode('ITEM'.padEnd(24) + 'SUBTOTAL\n'),
                ...encoder.encode('--------------------------------\n')
            );
            
            // Items
            let calculatedSubtotal = 0;
            receiptData.transaction_outlet.forEach(item => {
                const subtotal = item.product_harga * item.qty;
                calculatedSubtotal += subtotal;
                commands.push(...encoder.encode(createLine(item.product.nama, subtotal) + '\n'));
                commands.push(...encoder.encode(formatRupiah(item.product.harga) + ' (x' + item.qty + ')\n\n'));
            });
            
            const calculatedTotal = calculatedSubtotal;


            commands.push(
                ...[
                    ...encoder.encode('--------------------------------\n'),
                    ...encoder.encode(createLine('TOTAL', calculatedTotal) + '\n'),
                ]
            );

            // Conditional payment and change lines
            if (receiptData.trans_outlet_metode === "Cash") {
                const calculatedKembali = (receiptData.trans_outlet_bayar ?? 0) - calculatedTotal;
                commands.push(
                    ...encoder.encode(createLine('Cash', receiptData.trans_outlet_bayar) + '\n'),
                    ...encoder.encode(createLine('Kembali', calculatedKembali) + '\n\n')
                );
            } else {
                 commands.push(
                    ...encoder.encode(createLine(receiptData.trans_outlet_metode, receiptData.trans_outlet_bayar) + '\n\n')
                );
            }

            // Footer
            commands.push(
                ...[
                    ...[0x1b, 0x61, 0x01], // Set alignment to center
                    ...encoder.encode('Terima Kasih\n\n\n\n'),
                    // Cut paper
                    ...[0x1d, 0x56, 0x01]
                ]
            );

            return new Uint8Array(commands);
        }
        
        async function printReceipt(transactionData) {
            statusMessage.textContent = 'Searching for a Bluetooth device...';
            // statusAlert.textContent = 'Searching for a Bluetooth device...';
            let characteristic;
        
            try {
                let device;
                if (navigator.bluetooth && navigator.bluetooth.getDevices) {
                    const devices = await navigator.bluetooth.getDevices();
                    if (devices.length > 0) {
                        device = devices[0];
                        statusMessage.textContent = `Connecting to a previously paired device: "${device.name}"...`;
                        // statusAlert.textContent = `Connecting to a previously paired device: "${device.name}"...`;
                    } else {
                        statusMessage.textContent = 'No previously paired device found. Please select a device from the list.';
                        // statusAlert.textContent = 'No previously paired device found. Please select a device from the list.';
                        device = await navigator.bluetooth.requestDevice({
                            acceptAllDevices: true,
                            optionalServices: [SERVICE_UUID]
                        });
                    }
                } else {
                    statusMessage.textContent = 'Auto-connect is not supported in this browser. Please select a device from the list.';
                    // statusAlert.textContent = 'Auto-connect is not supported in this browser. Please select a device from the list.';
                    device = await navigator.bluetooth.requestDevice({
                        acceptAllDevices: true,
                        optionalServices: [SERVICE_UUID]
                    });
                }
        
                statusMessage.textContent = `Connecting to "${device.name}"...`;
                // statusAlert.textContent = `Connecting to "${device.name}"...`;
                const server = await device.gatt.connect();
        
                statusMessage.textContent = 'Getting the service...';
                // statusAlert.textContent = 'Getting the service...';
                const service = await server.getPrimaryService(SERVICE_UUID);
        
                statusMessage.textContent = 'Getting the characteristic...';
                // statusAlert.textContent = 'Getting the characteristic...';
                characteristic = await service.getCharacteristic(CHARACTERISTIC_UUID);
        
                statusMessage.textContent = `Connected to "${device.name}"! Generating print commands...`;
                // statusAlert.textContent = `Connected to "${device.name}"! Generating print commands...`;
                const escPosCommands = await generatePrintCommands(transactionData);
        
                statusMessage.textContent = 'Sending commands to printer...';
                // statusAlert.textContent = 'Sending commands to printer...';
                const chunkSize = 20;
                for (let i = 0; i < escPosCommands.length; i += chunkSize) {
                    const chunk = escPosCommands.slice(i, i + chunkSize);
                    await characteristic.writeValueWithoutResponse(chunk);
                }
        
                statusMessage.textContent = 'Print job sent successfully!';
                // statusAlert.textContent = 'Print job sent successfully!';
        
            } catch (error) {
                statusMessage.textContent = `Error: ${error.message}`;
                // statusAlert.textContent = `Error: ${error.message}`;
                console.error('Print error:', error);
            }
        }
    </script>
@endpush
