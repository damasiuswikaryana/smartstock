@extends('layouts.main')

@push('css')
<style>
    .tab-danger .nav-link.active {
        background-color: var(--bs-danger) !important;
        color: #fff !important;
    }
    
    .tab-danger .nav-link {
        color: var(--bs-danger);
    }
    
    .tab-danger .nav-link:hover {
        background-color: rgba(220, 53, 69, 0.1);
    }
    
    .tab-danger .nav-link.active i {
        color: #fff !important;
    }
</style>
@endpush

@section('content')
    <x-page-header title="Input Stock Opnam" module="Input Stock Opnam" >
        <li class="breadcrumb-item">Stock Opnam</li>
        <li class="breadcrumb-item">Stock Opnam Harian</li>
    </x-page-header>
    
    <div class="mb-4 mt-3">
        
        <div class="d-flex justify-content-between align-items-center">
            <div class="col-xl-12">
                <div class="row">
                      <div class="col-md-12 col-sm-12">
                        <div class="tab-content" id="v-pills-tabContent">
                          
                          <!--input stock opnam-->
                          <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                            <div class="card">
                              <form action="#" id="form_stock_opnam" method="POST">
                                @csrf
                                @method('POST')
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush border-top-0">
                                    @foreach($stockItem as $item)
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12">
                                                <h5 class="fw-bold mb-2">{{ $item->nama }}</h5>
                                            </div>
                                            <div class="col-3 align-items-center d-flex">
                                                <label class="text-muted"><i class="ms-2 me-2 material-icons-two-tone">subdirectory_arrow_right</i> Sisa Stock</label>
                                            </div>
                                            <div class="col-9">
                                                <div class="input-group">
                                                  <input 
                                                    type="text" 
                                                    class="form-control col-3" 
                                                    id="soJumlah-{{ $item->id }}"
                                                    name="soJumlah-{{ $item->id }}"
                                                    value="{{ $item->opnam->count() > 0 ? $item->opnam->item_jumlah : '' }}"
                                                    placeholder="Jum. stock" 
                                                    required
                                                    {{ $item->opnam->count() > 0 ? 'readonly' : '' }}>
                                                  <select 
                                                    class="form-select col-1" 
                                                    id="soSatuan-{{ $item->id }}"
                                                    name="soSatuan-{{ $item->id }}"
                                                    required>
                                                    <option value="" disabled>Pilih Satuan</option>
                                                    @foreach($satuan as $sat)
                                                    <option 
                                                        value="{{ $sat->id }}"
                                                        {{ $item->opnam->count() > 0 && $item->opnam->id_satuan == $sat->id ? 'selected' : '' }}
                                                        {{ $item->opnam->count() > 0 ? 'readonly' : '' }}
                                                    >{{ $sat->satuan }}</option>
                                                    @endforeach
                                                  </select>
                                                  <input 
                                                    type="text" 
                                                    class="form-control col-3" 
                                                    id="soJumlah2-{{ $item->id }}"
                                                    name="soJumlah2-{{ $item->id }}"
                                                    value="{{ $item->opnam->count() > 0 ? $item->opnam->item_jumlah_2 : '' }}"
                                                    placeholder="Stock lanjutan" 
                                                    {{ $item->opnam->count() > 0 ? 'readonly' : '' }}>
                                                  <select 
                                                    class="form-select col-1" 
                                                    id="soSatuan2-{{ $item->id }}"
                                                    name="soSatuan2-{{ $item->id }}">
                                                    <option value="" disabled>Pilih Satuan</option>
                                                    @foreach($satuan as $sat)
                                                    <option 
                                                        value="{{ $sat->id }}"
                                                        {{ $item->opnam->count() > 0 && $item->opnam->id_satuan_2 == $sat->id ? 'selected' : '' }}
                                                        {{ $item->opnam->count() > 0 ? 'readonly' : '' }}
                                                    >{{ $sat->satuan }}</option>
                                                    @endforeach
                                                  </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-3 align-items-center d-flex">
                                                <label class="text-muted"><i class="ms-2 me-2 material-icons-two-tone">subdirectory_arrow_right</i> Terpakai</label>
                                            </div>
                                            <div class="col-9">
                                                <div class="input-group terpakai-item">
                                                  <input 
                                                    type="hidden" 
                                                    name="terpakai[{{ $item->id }}][id_item]"
                                                    value="{{ $item->id }}">
                                                  <select 
                                                    class="form-select col-1" 
                                                    id=""
                                                    name="terpakai[{{ $item->id }}][id_satuan]">
                                                    <option value="" selected disabled>Pilih Satuan</option>
                                                    @foreach($item->satuan as $sat)
                                                    <option 
                                                        value="{{ $sat['id'] }}"
                                                        {{ $item->opnam->count() > 0 && $item->opnam->id_satuan_terpakai == $sat['id'] ? 'selected' : '' }}
                                                    >{{ $sat['satuan'] }}</option>
                                                    @endforeach
                                                  </select>
                                                  <input 
                                                    type="text" 
                                                    class="form-control col-4 jumlah-input-terpakai" 
                                                    id=""
                                                    name="terpakai[{{ $item->id }}][jumlah]"
                                                    value="{{ $item->opnam->count() > 0 ? $item->opnam->item_terpakai : '' }}"
                                                    placeholder="Terpakai">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </div>
                                
                                <div class="card-footer p-2">
                                    <button type="submit" id="btn_input_stock_opnam" class="btn btn-danger w-100">
                                        @if ($sudahInputStockOpnam)
                                        Update Data Stock Opnam
                                        @else
                                        Input Stock Opnam
                                        @endif
                                    </button>
                                </div>
                              </form>
                            </div>
                          </div>
                          
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).on('change', 'select[name^="terpakai"][name$="[id_satuan]"]', function () {
            const $row          = $(this).closest('.terpakai-item');
            const satuanId      = $(this).val();
            const idOutlet      = "{{ $idOutlet }}";
            const itemId        = $row.find('input[name^="terpakai"][name$="[id_item]"]').val();
            const jumlahInput   = $row.find('input[name^="terpakai"][name$="[jumlah]"]');
            if (!itemId || !satuanId) return;
            $.ajax({
                url: `/stock-opnam/get-stock-max?id_outlet=${idOutlet}`,
                method: 'GET',
                data: { item_id: itemId, satuan_id: satuanId },
                success: function (res) {
                    jumlahInput.attr('max', res.max_stock);
                    jumlahInput.attr('placeholder', `Max: ${res.max_stock}`);
                }
            });
        });
        $(document).on('input', '.jumlah-input-terpakai', function () {
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
            
        $('#form_stock_opnam').on('submit', function (e) {
            e.preventDefault();
            var url = "{{ route('stock-opnam.harian.store_stockOpnam') }}";
            
            if (confirm('Submit stock opnam sekarang? Harap cek ulang data.')) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $(this).serialize(),
                    beforeSend: function() {
                        showLoader();
                    },
                    success: function (res) {
                        if (res.success) {
                            hideLoader();
                            $('#btn_input_stock_opnam').html('Update Data Stock Opnam');
                            $('#btn_input_stock_opnam').removeClass('btn-danger').addClass('btn-warning');
                            showToastSuccess("Berhasil input stock opnam hari ini. Terima kasih!");
                        } else {
                            hideLoader();
                            showToastError(res.message);
                        }
                    },
                    error: function () {
                        hideLoader();
                        showToastError("Terjadi kesalahan saat input stock opnam");
                    }
                });
            }
        });
    </script>
@endpush
