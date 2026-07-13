@extends('layouts.main')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/uppy.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.min.css') }}" type="text/css" />
@endpush

@php
    if ($data_trans != null) {
        $cashNominal = $data_trans->transaction_nominal->firstWhere('metode_bayar', 'Cash');
        $qrisNominal = $data_trans->transaction_nominal->firstWhere('metode_bayar', 'Qris');
    } else {
        $cashNominal = null;
        $qrisNominal = null;
    }
@endphp
@section('content')
    <x-page-header title="Detail Transaksi Gerobak" module="Transaksi {{ $data_gerobak->nama }} - {{ tanggalIndo($tanggal) }}" >
        <li class="breadcrumb-item">Transaksi</li>
        <li class="breadcrumb-item">Transaksi Gerobak</li>
    </x-page-header>
    
    <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
        <div class="col-xl-12">
            <div id="aler_tinjau">
                @if ($data_trans != null && $data_trans->trans_status == "Pending")
                <div class="alert alert-info" role="alert">
                    <h4 class="alert-heading"><i class="fa fa-info-circle me-2"></i> Transaksi Review</h4>
                    <p class="mb-0"> Transaksi Anda saat ini sedang dalam proses peninjauan oleh tim admin. Setelah disetujui, transaksi akan dinyatakan sah dan tercatat secara resmi.</p>
                </div>
                @endif
                @if ($data_trans != null && $data_trans->trans_status == "Approved")
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading"><i class="fa fa-check-circle me-2"></i> Transaksi Valid</h4>
                    <p class="mb-0"> Transaksi ini kini dinyatakan sah dan telah tercatat secara resmi dalam sistem. Terima kasih atas partisipasi dan kepercayaannya.</p>
                </div>
                @endif
            </div>
                    
            <form class="modal-body" action="#" method="post" id="form-edit">
                @csrf
                @method('POST')
                <!--transaksi produk-->
                <input type="hidden" name="tanggal" value="{{ $tanggal }}"/>
                <div class="px-0">
                    <ul class="list-group">
                      <li class="list-group-item list-group-item-warning">
                          <h3 class="card-title mb-0">Penjualan Produk</h3>
                      </li>
                      @foreach ($data_product as $pd)
                      <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <!-- nama produk -->
                        <div class="ms-2 me-auto mb-2 mb-md-0">
                          <div class="fw-bold">{{ $pd->nama }}</div>
                          {{ $pd->category->title }}
                        </div>
                        <!-- nama produk -->
                        
                        <!-- form input -->
                        <div class="d-flex flex-wrap gap-2">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="penjualan-master-{{ $pd->id }}" name="penjualan-{{ $pd->id }}" placeholder="Jumlah Penjualan" required
                                @if ($data_trans != null  && $pd->trans != null)
                                    value="{{ $pd->trans->count() == 0 ? 0 : $pd->trans->product_sales }}"
                                @endif
                                @if (auth()->user()->is_admin)
                                @else
                                    @if ($data_trans != null && $data_trans->trans_status == "Approved")
                                    disabled
                                    @endif
                                @endif
                                >
                                <label for="penjualan-master-{{ $pd->id }}">Jumlah Penjualan</label>
                            </div>
                            <div class="form-floating">
                                <input style="width:120px;" type="number" class="form-control" id="stock-{{ $pd->id }}" name="stock-{{ $pd->id }}" placeholder="Jumlah Stok"
                                @if ($data_stock->count() > 0 && $pd->stock != null)
                                value="{{ $pd->stock->sum('stock') }}"
                                disabled
                                @endif
                                >
                                <label for="stock-{{ $pd->id }}">Jumlah Stock</label>
                            </div>
                            @php
                                $total_penjualan_variant = 0;
                                if ($pd->variants->count() > 0) {
                                    foreach ($pd->variants as $variant) {
                                        if ($variant->trans->count() > 0) {
                                            $total_penjualan_variant += $variant->trans->count() == 0 ? 0 : $variant->trans->product_sales ;
                                        } else {
                                            $total_penjualan_variant += 0;
                                        }
                                    }
                                }
                            @endphp
                            <div class="form-floating">
                                <input style="width:120px;" type="number" class="form-control" id="sisa-{{ $pd->id }}" name="sisa-{{ $pd->id }}" placeholder="Jumlah Stok"
                                @if ($data_trans != null  && $pd->trans != null)
                                value="{{ $pd->stock->sum('stock') - ($pd->trans->count() == 0 ? 0 : $pd->trans->product_sales) - $total_penjualan_variant }}"
                                @else
                                value="{{ $pd->stock->sum('stock') }}"
                                @endif
                                disabled
                                >
                                <label for="stock-{{ $pd->id }}">Sisa Stock</label>
                            </div>
                        </div>
                      </li>
                      <!--PRODUCT VARIANTS-->
                      @if ($pd->variants->count() > 0)
                        @foreach ($pd->variants as $variant)
                        <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <!-- nama produk -->
                            <div class="ms-2 me-auto mb-2 mb-md-0">
                              <div class="d-flex justify-content-between align-items-center">
                                  <i class="ms-3 me-3 material-icons-two-tone">subdirectory_arrow_right</i>
                                  <div>
                                      <div class="fw-bold">{{ $variant->nama }}</div>
                                      {{ $variant->category->title }}
                                  </div>
                              </div>
                            </div>
                            <!-- nama produk -->
                            
                            <!-- form input -->
                            <div class="d-flex flex-wrap gap-2">
                                <div class="form-floating">
                                <input type="hidden" value="{{ $pd->id }}" id="master-id-{{ $variant->id }}" />
                                <input type="number" class="form-control" id="penjualan-varian-{{ $variant->id }}" name="penjualan-{{ $variant->id }}" placeholder="Jumlah Penjualan" required
                                @if ($data_trans != null  && $variant->trans != null)
                                value="{{ $variant->trans->count() == 0 ? 0 : $variant->trans->product_sales }}"
                                @endif
                                @if (auth()->user()->is_admin)
                                @else
                                    @if ($data_trans != null && $data_trans->trans_status == "Approved")
                                    disabled
                                    @endif
                                @endif
                                >
                                <label for="penjualan-varian-{{ $variant->id }}">Jumlah Penjualan</label>
                            </div>
                            </div>
                            <!---->
                          </li>
                        @endforeach
                      @endif
                      <!--PRODUCT VARIANTS-->
                      @endforeach
                    </ul>
                </div>
                
                <!--pengeluaran-->
                <div class="px-0 mt-4">
                    <ul class="list-group" id="wp_pengeluaran">
                      <li class="list-group-item list-group-item-warning">
                          <h3 class="card-title mb-0">Pengeluaran</h3>
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
                
                <!--transaksi nominal-->
                <div class="px-0 mt-4">
                    <ul class="list-group">
                      <li class="list-group-item list-group-item-warning">
                          <h3 class="card-title mb-0">Penjualan by Nominal</h3>
                      </li>
                      <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                          
                        <div class="d-flex flex-wrap gap-2">
                            <img class="" src="{{ asset('storage/metode_pembayaran/rupiah.png') }}" width="40" />
                            
                            <div class="ms-2 me-auto">
                              <div class="fw-bold">Cash</div>
                              Total penjualan dengan metode pembayaran cash
                            </div>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2">
                            <div class="form-floating mb-0 me-2">
                            <input style="width:300px;" type="text" class="form-control number-separator" id="penjualan-cash" name="penjualan-cash" placeholder="Jumlah Penjualan (Rp)" required
                            @if ($data_trans != null && $data_trans->transaction_nominal->count() > 0)
                            value="{{ pecahTanpaRp(optional($cashNominal)->transaction_amount ?? 0) }}"
                            @endif
                            @if (auth()->user()->is_admin)
                            @else
                                @if ($data_trans != null && $data_trans->trans_status == "Approved")
                                disabled
                                @endif
                            @endif
                            >
                            <label for="penjualan-cash">Jumlah Penjualan (Rp)</label>
                        </div>
                        </div>
                      </li>
                      <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <img class="me-3" src="{{ asset('storage/metode_pembayaran/qris.png') }}" width="40" />
                        <div class="ms-2 me-auto">
                          <div class="fw-bold">Qris</div>
                          Total penjualan dengan metode pembayaran qris
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <div class="form-floating mb-0 me-2">
                            <input style="width:300px;" type="text" class="form-control number-separator" id="penjualan-qris" name="penjualan-qris" placeholder="Jumlah Penjualan (Rp)" required
                            @if ($data_trans != null && $data_trans->transaction_nominal->count() > 0)
                            value="{{ pecahTanpaRp(optional($qrisNominal)->transaction_amount ?? 0) }}"
                            @endif
                            @if (auth()->user()->is_admin)
                            @else
                                @if ($data_trans != null && $data_trans->trans_status == "Approved")
                                disabled
                                @endif
                            @endif
                            >
                            <label for="penjualan-qris">Jumlah Penjualan (Rp)</label>
                        </div>
                        </div>
                      </li>
                    </ul>
                </div>
                
                <!--transaksi keterangan-->
                <div class="px-0 mt-4">
                    <ul class="list-group">
                      <li class="list-group-item list-group-item-warning">
                          <h3 class="card-title mb-0">Keterangan</h3>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="form-floating mb-0 me-2 w-100">
                            <input type="text" class="form-control w-100" id="penjualan-cash" name="keterangan" placeholder="Masukan keterangan ..." required
                            @if ($data_trans != null && $data_trans->trans_keterangan != null)
                            value="{{ $data_trans->trans_keterangan }}"
                            @endif
                            @if (auth()->user()->is_admin)
                            @else
                                @if ($data_trans != null && $data_trans->trans_status == "Approved")
                                disabled
                                @endif
                            @endif>
                            <label for="penjualan-cash">Keterangan Transaksi</label>
                        </div>
                      </li>
                    </ul>
                </div>
                
                <div class="px-0 mt-4">
                    <button id="btn-submit" value="action_submit" type="submit" class="w-100 btn {{ $data_trans != null && $data_trans->trans_status == 'Approved' ? 'btn-secondary' : 'btn-primary' }} mb-3" form="form-edit"
                    
                    @if ($data_trans != null && $data_trans->trans_status == "Approved") disabled @endif>
                    Input Penjualan</button>
                    
                    @if (auth()->user()->is_admin)
                        @if ($data_trans != null) 
                        <button id="btn-submit" value="action_update" type="submit" class="w-100 btn btn-secondary mb-3" form="form-edit">
                            Update Penjualan
                        </button>
                        @endif
                    @endif
                    
                    @if ($data_trans != null && $data_trans->trans_status == "Pending")
                        @if ($cek_gerobak_roles == null || auth()->user()->is_admin)
                        <button id="btn-approve" value="action_approve" type="submit" class="w-100 btn btn-success" form="form-edit">Approve Penjualan</button>
                        @endif
                    @endif
                </div>
                
            </form>
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
    
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/uppy.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
    <script src="{{ asset('assets/js/plugins/clipboard.min.js') }}"></script>
    <script src="{{ asset('assets/js/component.js') }}"></script>
    
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
  
        function removeClassByPrefix(node, prefix) {
          for (let i = 0; i < node.classList.length; i++) {
            let value = node.classList[i];
            if (value.startsWith(prefix)) {
              node.classList.remove(value);
            }
          }
        }
        
        let clickedButton = null;
        const rupiah = n => (+n).toLocaleString('id-ID');
        
        $("button[form='form-edit']").on("click", function() {
            clickedButton = $(this).val();
        });
        
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
        
        function hitungSisaStokMaster(idMaster) {
            const stokAwal = parseFloat($('#stock-' + idMaster).val()) || 0;
            const qtyMaster = parseFloat($('#penjualan-master-' + idMaster).val()) || 0;
            let totalVarian = 0;
            
            $('input[id^="penjualan-varian-"]').each(function () {
                const idVarian = this.id.split('-')[2];
                const varianMasterId = $('#master-id-' + idVarian).val();
                if (varianMasterId == idMaster) {
                    totalVarian += parseFloat($(this).val()) || 0;
                }
            });
        
            const totalTerpakai = qtyMaster + totalVarian;
            const sisa = Math.max(stokAwal - totalTerpakai, 0);
            $('#sisa-' + idMaster).val(sisa);
        }
        
        $(function () {
            $(document).on('input', 'input[id^="penjualan-master-"]', function () {
                const idMaster = this.id.split('-')[2];
                hitungSisaStokMaster(idMaster);
            });
            $(document).on('input', 'input[id^="penjualan-varian-"]', function () {
                const idVarian = this.id.split('-')[2];
                const idMaster = $('#master-id-' + idVarian).val();
                hitungSisaStokMaster(idMaster);
            });
            // tambah pengeluaran
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
            $('#btn-tambah-pengeluaran').on('click', function () {
                const nama   = $('#pengeluaran-nama').val().trim();
                const jumlah = $('#pengeluaran-harga').val();
                const id_gerobak = "{{ $data_gerobak->id }}";
                
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
                        pengeluaran_tipe: "Gerobak",
                        id_outlet: null,
                        id_gerobak: id_gerobak,
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
        
        $('#form-edit').on('submit', function (e) {
            e.preventDefault();
            var nama_gerobak = "{{ $data_gerobak->nama }}";
            const id = "{{ $data_gerobak->id }}";
            const idTrans = "{{ $data_trans->id ?? null }}"
            var url = "";

            if (clickedButton === "action_submit") {
                url = "{{ route('transaction.gerobak.store-trans', ':id:') }}".replace(':id:', id);
                var confirm_msg = "Submit penjualan sekarang? Harap cek ulang data yang diinput.";
            } else if (clickedButton === "action_approve") {
                url = "{{ route('transaction.gerobak.approve-trans', ':id:') }}".replace(':id:', idTrans);
                var confirm_msg = "Apakah Anda yakin ingin approve transaksi ini?";
            } else if (clickedButton === "action_update") {
                url = "{{ route('transaction.gerobak.update-trans', ':idTrans:') }}".replace(':idTrans:', idTrans);
                var confirm_msg = "Update data penjualan? Harap cek ulang data yang diinput.";
            } else {
                // showToastError("Tombol tidak dikenali.");
                return;
            }
            
            if (confirm(confirm_msg)) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $(this).serialize(),
                    beforeSend: showLoader(),
                    success: function (res) {
                        if (res.success) {
                            hideLoader();
                            if (clickedButton === "action_submit") {
                                showToastSuccess("Berhasil input penjualan pada gerobak " + nama_gerobak);
                                $('#aler_tinjau').html(`<div class="alert alert-info" role="alert">
                                                            <h4 class="alert-heading"><i class="fa fa-info-circle me-2"></i> Transaksi Review</h4>
                                                            <p class="mb-0"> Transaksi Anda saat ini sedang dalam proses peninjauan oleh tim admin. Setelah disetujui, transaksi akan dinyatakan sah dan tercatat secara resmi.</p>
                                                        </div>`);
                            }
                            else if (clickedButton === "action_update") {
                                showToastSuccess("Berhasil update data penjualan");
                            }
                            else {
                                showToastSuccess("Berhasil approve transaksi pada gerobak " + nama_gerobak);
                                $("input[id^='penjualan-']").prop("disabled", true);
                                $("#btn-approve").hide();
                                $("button[form='form-edit']").removeClass("btn-primary").addClass("btn-secondary").prop("disabled", true);
                                $('#aler_tinjau').html(`<div class="alert alert-success" role="alert">
                                                            <h4 class="alert-heading"><i class="fa fa-check-circle me-2"></i> Transaksi Valid</h4>
                                                            <p class="mb-0"> Transaksi ini kini dinyatakan sah dan telah tercatat secara resmi dalam sistem. Terima kasih atas partisipasi dan kepercayaannya.</p>
                                                        </div>`);
                            }
                        } else {
                            hideLoader();
                            showToastError(res.message);
                        }
                    },
                    error: function () {
                        hideLoader();
                        if (clickedButton === "action_submit") {
                            showToastError("Terjadi kesalahan saat input penjualan gerobak");
                        } else {
                            showToastError("Terjadi kesalahan saat approve transaksi gerobak");
                        }
                    }
                });
            }
        });
        // approve-trans
    </script>
@endpush
