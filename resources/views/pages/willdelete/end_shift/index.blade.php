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
    <x-page-header title="Akhiri Shift" module="Management Shift" >
        <li class="breadcrumb-item">Management</li>
        <li class="breadcrumb-item">Management Shift</li>
    </x-page-header>
    
    <div class="mb-4 mt-5">
        <!--<div class="col-12">-->
        <!--    <div class="alert alert-success d-flex align-items-center" role="alert">-->
        <!--      <svg class="bi flex-shrink-0 me-2" width="24" height="24">-->
        <!--        <use xlink:href="#check-circle-fill"></use>-->
        <!--      </svg>-->
        <!--      <div> An example success alert with an icon </div>-->
        <!--    </div>-->
        <!--</div>-->
        
        <div class="d-flex justify-content-between align-items-center">
            <div class="col-xl-12">
                <div class="row">
                      <div class="col-md-12 col-sm-12 mb-4">
                        <ul class="nav nav-pills tab-danger" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                          <li>
                              <a class="nav-link p-3 active" id="v-pills-home-tab" 
                                data-bs-toggle="pill" 
                                href="#v-pills-home" 
                                role="tab" 
                                aria-controls="v-pills-home" 
                                aria-selected="true">
                                  <div class="d-flex align-items-center justify-content-between">
                                    <span>Pemasukan</span>
                                    <i class="ph-duotone ph-caret-circle-down ms-2 fs-3"></i>
                                  </div>
                              </a>
                          </li>
                          @if ($tampilChecklist)
                          <li>
                              <a class="nav-link p-3" id="v-pills-messages-tab" 
                                data-bs-toggle="pill" 
                                href="#v-pills-messages" 
                                role="tab" 
                                aria-controls="v-pills-messages" 
                                aria-selected="false">
                                  <div class="d-flex align-items-center justify-content-between">
                                    <span>Checklist Tutup Toko</span>
                                    <i class="ph-duotone ph-caret-circle-down ms-2 fs-3"></i>
                                  </div>
                              </a>
                          </li>
                          @endif
                        </ul>
                      </div>
                      <div class="col-md-12 col-sm-12">
                        <div class="tab-content" id="v-pills-tabContent">
                          <!--input pemasukan-->
                          <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                            <div class="card">
                              <div class="card-header">
                                  <h4 class="card-title mb-0">Pemasukan Outlet</h4>
                              </div>
                              <form id="form_pemasukan" action="#" method="POST">
                                  @csrf
                                  @method('POST')
                                  <div class="card-body">
                                    <div class="col-12 mb-3">
                                        <div class="form-floating mb-0">
                                            <input type="text" class="form-control number-separator" 
                                                    id="pemasukan_cash" 
                                                    name="pemasukan_cash" 
                                                    required placeholder="Pemasukan Cash"
                                                    @if ($pemasukan != null)
                                                    value="{{ pecahTanpaRp($pemasukan->pemasukan_cash) }}"
                                                    @endif
                                            >
                                            <label for="pemasukan_cash">Pemasukan Cash</label>
                                            <small id="pemasukan_cashHelp" class="form-text text-danger">Input dalam jumlah rupiah.</small>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-0">
                                        <div class="form-floating mb-0">
                                            <input type="text" class="form-control number-separator" 
                                                    id="pemasukan_qris" 
                                                    name="pemasukan_qris" 
                                                    required placeholder="Pemasukan Qris"
                                                    @if ($pemasukan != null)
                                                    value="{{ pecahTanpaRp($pemasukan->pemasukan_qris) }}"
                                                    @endif
                                            >
                                            <label for="pemasukan_qris">Pemasukan Qris</label>
                                            <small id="pemasukan_qrisHelp" class="form-text text-danger">Input dalam jumlah rupiah.</small>
                                        </div>
                                    </div>
                                  </div>
                                  <div class="card-footer p-2">
                                      <button type="submit" id="btn_input_pemasukan" class="btn {{ $sudahInputPemasukan ? 'btn-warning' : 'btn-danger' }} w-100">
                                          @if ($sudahInputPemasukan)
                                          Update Data Pemasukan
                                          @else
                                          Input Pemasukan
                                          @endif
                                      </button>
                                  </div>
                              </form>
                            </div>
                          </div>
                          
                          @if ($tampilChecklist)
                          <!--input kegiatan-->
                          <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                            <div class="card">
                              <div class="card-header">
                                  <h4 class="card-title mb-0">Checklist Tutup Toko</h4>
                              </div>
                              <form id="form_kegiatan" action="#" method="POST">
                                <div class="card-body">
                                    @csrf
                                    @method('POST')
                                    @foreach($kegiatan as $kg)
                                    <div class="col-12 mb-3">
                                      <div class="input-group input-group-lg">
                                        <div class="input-group-text col-1">
                                          <input 
                                            id="kegiatancheck-{{ $kg->id }}" 
                                            name="kegiatancheck[]" 
                                            class="form-check-input" 
                                            required 
                                            type="checkbox" 
                                            value="{{ $kg->id }}"
                                            {{ $kg->kegiatanCheck->count() > 0 ? 'checked' : '' }}
                                        >
                                        </div>
                                        <div class="input-group-text col-4">
                                          <p class="mb-0">{{ $kg->nama }}</p>
                                        </div>
                                        <input 
                                            type="text" 
                                            id="kegiatanket-{{ $kg->id }}" 
                                            name="kegiatanket-{{ $kg->id }}" 
                                            class="form-control col-7" 
                                            placeholder="Tulis keterangan disini ..."
                                            value="{{ $kg->kegiatanCheck->count() > 0 ? $kg->kegiatanCheck->kegiatan_note : '' }}"
                                        >
                                      </div>
                                      <small id="kegiatanHelp-{{ $kg->id }}" class="form-text text-danger">{{ $kg->keterangan }}</small>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="card-footer p-2">
                                    <button type="submit" id="btn_input_checklist" class="btn {{ $sudahInputChecklist ? 'btn-warning' : 'btn-danger' }} w-100">
                                        @if ($sudahInputChecklist)
                                        Update Data Checklist
                                        @else
                                        Input Checklist
                                        @endif
                                    </button>
                                </div>
                              </form>
                            </div>
                          </div>
                          @endif
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function () {
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('a[href="' + activeTab + '"]').tab('show');
            }
            $('a[data-bs-toggle="pill"], a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
        });
        
        $('#form_pemasukan').on('submit', function (e) {
            e.preventDefault();
            var url = "{{ route('endshift.store_pemasukan') }}";
            
            if (confirm('Submit pemasukan outlet sekarang? Harap cek ulang data.')) {
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
                            $('#btn_input_pemasukan').html('Update Data Pemasukan');
                            $('#btn_input_pemasukan').removeClass('btn-danger').addClass('btn-warning');
                            // 
                            // 
                            showToastSuccess("Berhasil input pemasukan outlet hari ini. Terima kasih!");
                        } else {
                            hideLoader();
                            showToastError(res.message);
                        }
                    },
                    error: function () {
                        hideLoader();
                        showToastError("Terjadi kesalahan saat input pemasukan outlet");
                    }
                });
            }
        });
        
        @if ($tampilChecklist)
        $(document).on('click', '[id^="kegiatanket-"]', function () {
            const id = $(this).attr('id').split('-').pop();
            $('#kegiatancheck-'+id).prop('checked', true);
        });
        $('#form_kegiatan').on('submit', function (e) {
            e.preventDefault();
            var url = "{{ route('endshift.store_kegiatan') }}";
            
            if (confirm('Submit checklist kegiatan sekarang? Harap cek ulang data.')) {
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
                            $('#btn_input_checklist').html('Update Data Checklist');
                            $('#btn_input_checklist').removeClass('btn-danger').addClass('btn-warning');
                            showToastSuccess("Berhasil input checklist kegiatan hari ini. Terima kasih!");
                        } else {
                            hideLoader();
                            showToastError(res.message);
                        }
                    },
                    error: function () {
                        hideLoader();
                        showToastError("Terjadi kesalahan saat input checklist kegiatan");
                    }
                });
            }
        });
        @endif
    </script>
@endpush
