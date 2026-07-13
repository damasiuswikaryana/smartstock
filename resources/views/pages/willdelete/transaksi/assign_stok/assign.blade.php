<style>
    .modal-blur {
      filter: blur(5px);
      pointer-events: none;
    }
</style>

<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Assign Stok ke {{ $data_gerobak->nama }} <br><small>{{ tanggalIndo(date('Y-m-d')) }}</small></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="modal-body" action="#" method="post" id="form-edit">
    <div class="px-4">
        @csrf
        @method('POST')
        <ul class="list-group">
          @foreach ($data_product as $pd)
          <li class="list-group-item">
            <div class="d-flex justify-content-between align-items-center">  
                <div class="ms-2 me-auto">
                  <div class="fw-bold">{{ $pd->nama }}</div>
                  {{ $pd->category->title }}
                </div>
                <div class="form-floating mb-0" id="wp-awal-{{ $pd->id }}">
                    <input type="number" class="form-control" id="stock-{{ $pd->id }}" name="stock-{{ $pd->id }}" required placeholder="Jumlah Stok"
                    @if ($data_stock->count() > 0 && $pd->stock->count() > 0)
                    value="{{ $pd->stock->sum('stock') }}" disabled
                    @endif
                    >
                    <label for="stock-{{ $pd->id }}">Jumlah Stok</label>
                </div>
                <!--penampbahan stock-->
                <div class="form-floating mb-0" id="wp-penambahan-{{ $pd->id }}" style="display:none;">
                    <input type="number" class="form-control" id="stock-penambahan-{{ $pd->id }}" name="stock-penambahan-{{ $pd->id }}" placeholder="Jumlah Penambahan" value="" min="1">
                    <label for="stock-penambahan-{{ $pd->id }}">Penambahan Stok</label>
                </div>
                @php
                    if (Auth::user()->is_admin) {
                        $tampilkan = true;
                    } else {
                        if ($data_stock->count() > 0 && $pd->stock != null && $bisa_diedit) {
                            $tampilkan = true;
                        } else {
                            $tampilkan = false;
                        }
                    }
                @endphp
                @if ($tampilkan)
                <a href="javascript:void(0);" data-id="{{ $pd->id }}" class="btn btn-secondary btn-lg ms-2 rounded-sm btn-penambahan" data-bs-toggle="tooltip" title="Penambahan Stok">
                    <i class="fas fa-plus"></i>
                </a>
                <a href="javascript:void(0);" data-id="{{ $pd->id }}" class="btn btn-danger btn-lg ms-2 rounded-sm btn-store-penambahan" data-bs-toggle="tooltip" title="Submit Penambahan" style="display: none">
                    <i class="fas fa-check-circle"></i>
                </a>
                @endif
                <!--end of penambahan stock-->
            </div>
            
            @if (auth()->user()->is_admin)
            <ul class="list-group mt-3">
              @foreach($pd->stock as $s)
                <li class="list-group-item p-2" id="{{ $s->id }}">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center justify-content-between">
                            <i class="ms-3 material-icons-two-tone">subdirectory_arrow_right</i> 
                            <span>{{ $s->stock }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="javascript:void(0);" 
                               class="btn-sm btn btn-outline-secondary me-1 btn-edit-stock" 
                               data-id="{{ $s->id }}" 
                               data-stock="{{ $s->stock }}">
                               Edit
                            </a>
                            <form action="{{ route('transaction.assignStok.hapus', $s->id) }}" method="POST">
                                @csrf
                                @method("DELETE")
                                <button onclick="return confirm('Apakah kamu yakin ingin menghapus data ini?')" type="submit" class="btn-sm btn btn-outline-danger ms-1">Delete</button>
                            </form>
                        </div>
                    </div>
                </li>
              @endforeach
            </ul>
            @endif
            <!--<span class="badge bg-primary rounded-pill">14</span>-->
          </li>
          @endforeach
        </ul>
    </div>
</form>
<div class="modal-footer p-2">
    <button {{ $data_stock->count() > 0 ? 'disabled' : '' }} 
        type="submit" 
        class="w-100 btn {{ $data_stock->count() > 0 ? 'btn-secondary' : 'btn-primary' }}" 
        form="form-edit">Assign Stok
    </button>
</div>

<script>
    // penambahan stock
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
    $(document).off('click', '.btn-penambahan');
    $(document).on('click', '.btn-penambahan', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        $('#wp-awal-' + id).hide();
        $('#wp-penambahan-' + id).show();
        $('.btn-penambahan[data-id="' + id + '"]').hide();
        $('.btn-store-penambahan[data-id="' + id + '"]').show();
    });
    $(document).off('click', '.btn-store-penambahan');
    $(document).on('click', '.btn-store-penambahan', function (e) {
        e.preventDefault();
        console.log("btn store diklik");
        const $btn      = $(this);
        $btn.prop('disabled', true);
        const idProduct = $(this).data('id');
        const idGerobak = "{{ $data_gerobak->id }}";
        var url         = "{{ route('transaction.assignStok.assign_penambahan_store', ['idGerobak' => ':idg:', 'idProduct' => ':idp:']) }}";
        var url         = url.replace(':idg:', idGerobak).replace(':idp:', idProduct);
        var tambah      = $('#stock-penambahan-' + idProduct).val();
        console.log(tambah);
        if (tambah !== null && tambah !== "" && tambah != 0) {
            if (confirm('Submit penambahan stock sekarang?')) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        stock_penambahan: tambah,
                    },
                    beforeSend: function () {
                        showLoader();
                    },
                    success: function (res) {
                        if (res.success) {
                            hideLoader();
                            $('#stock-' + idProduct).val(res.stok_baru);
                            $('#stock-penambahan-' + idProduct).val("");
                            $('#wp-awal-' + idProduct).show();
                            $('#wp-penambahan-' + idProduct).hide();
                            $('.btn-penambahan[data-id="' + idProduct + '"]').show();
                            $('.btn-store-penambahan[data-id="' + idProduct + '"]').hide();
                            showToastSuccess("Berhasil tambah stok pada produk " + res.nama_produk);
                            $btn.prop('disabled', false);
                        } else {
                            hideLoader();
                            showToastError(res.message);
                            $btn.prop('disabled', false);
                        }
                    },
                    error: function () {
                        hideLoader();
                        showToastError("Terjadi kesalahan saat tambah stok");
                        $btn.prop('disabled', false);
                    }
                });
            }
        } else {
            alert("Penambahan stock tidak boleh kosong.");
            $btn.prop('disabled', false);
        }
        
    });

    $('#form-edit').on('submit', function (e) {
        e.preventDefault();
        var nama_gerobak = "{{ $data_gerobak->nama }}";
        const id = "{{ $data_gerobak->id }}";
        var url = "{{ route('transaction.assignStok.assign_store', ':id:') }}";
        var url = url.replace(':id:', id);
        
        if (confirm('Submit stock sekarang? Harap cek ulang stock.')) {
            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                beforeSend: function() {
                    $('#modalEdit').modal('hide');
                    showLoader();
                },
                success: function (res) {
                    if (res.success) {
                        hideLoader();
                        showToastSuccess("Berhasil assign stok pada gerobak " + nama_gerobak);
                        $('#badge-stock-gerobak-'+id).html('<span class="badge bg-success"><i class="fa fa-check-circle"></i> Assign Stock</span>');
                        $('#btn-stock-gerobak-'+id).removeClass('btn-primary').addClass('btn-secondary');
                    } else {
                        hideLoader();
                        showToastError(res.message);
                    }
                },
                error: function () {
                    hideLoader();
                    showToastError("Terjadi kesalahan saat assign stok");
                }
            });
        }
    });
    
    $('#modalEditStock').on('show.bs.modal', function () {
        $('#modalEdit .modal-content').addClass('modal-blur');
    });
    
    $('#modalEditStock').on('hidden.bs.modal', function () {
        $('#modalEdit .modal-content').removeClass('modal-blur');
    });

    $(document).on('click', '.btn-edit-stock', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const stock = $(this).data('stock');
    
        // isi modal dengan data stok yang dipilih
        $('#edit-stock-id').val(id);
        $('#edit-stock-value').val(stock);
    
        // atur action form sesuai route edit
        var url = "{{ route('transaction.assignStok.update', ':id') }}";
        url = url.replace(':id', id);
        $('#form-edit-stock').attr('action', url);
    
        // tampilkan modal
        $('#modalEditStock').modal('show');
    });
</script>