@extends('layouts.main')

@push('css')
@endpush

@section('content')
    <x-page-header title="Detail Shift" module="Detail Shift" >
        <li class="breadcrumb-item">Management Shift</li>
        <li class="breadcrumb-item"><a href="{{ route('shift.index') }}">Shift</a></li>
    </x-page-header>
    
    <ul class="nav nav-pills mb-4 mt-3" id="pills-tab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active p-3" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Pemasukan Outlet</a>
      </li>
      @if ($tampilStockOpnam)
      <li class="nav-item">
        <a class="nav-link p-3" id="pills-profile-tab" data-bs-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Stock Opnam Harian</a>
      </li>
      @endif
      @if ($tampilChecklist)
      <li class="nav-item">
        <a class="nav-link p-3" id="pills-contact-tab" data-bs-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Checklist Outlet</a>
      </li>
      @endif
    </ul>
    <div class="tab-content" id="pills-tabContent">
        
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="d-block mb-4 mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center p-3">
                          <h4 class="card-title mb-0">Pemasukan Outlet</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0 pt-0">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Pemasukan Cash</p>
                                      <p class="mb-0">{{ $pemasukan != null ? rupiah($pemasukan->pemasukan_cash) : '-' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Pemasukan Qris</p>
                                      <p class="mb-0">{{ $pemasukan != null ? rupiah($pemasukan->pemasukan_qris) : '-' }}</p>
                                    </div>
                                  </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @if ($tampilStockOpnam)
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="d-block mb-4 mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center p-3">
                          <h4 class="card-title mb-0">Stock Opnam Harian</h4>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush border-top-0">
                                @forelse ($stockOpnam as $stock)  
                                <li class="list-group-item border-top-1 py-2">
                                  <div class="d-flex justify-content-between align-items-start flex-wrap">
                                    <div class="mx-0 me-2" style="width: 130px;">
                                      <h6 class="mb-1">{{ $stock->item->nama }}</h6>
                                      @if ($stock->item_jumlah <= 5)
                                      <p class="mb-0 text-danger">Akan habis!</p>
                                      @else
                                      <p class="mb-0 text-muted">Normal</p>
                                      @endif
                                    </div>
                                    <div class="mx-0 me-2" style="width: 130px;">
                                      <h6 class="mb-1">Stock</h6>
                                      <p class="text-muted mb-0">
                                          {{ $stock->item_jumlah }} {{ $stock->satuan->satuan }}
                                          @if ($stock->item_jumlah_2 != null)
                                          , {{ $stock->item_jumlah_2 }} {{ $stock->satuan_lanjut->satuan }}
                                          @endif
                                      </p>
                                    </div>
                                    <div class="mx-0 me-2" style="width: 130px;">
                                      <h6 class="mb-1">Terpakai</h6>
                                      <p class="text-muted mb-0">{{ $stock->item_terpakai }}</p>
                                    </div>
                                    <div class="mx-0 me-2" style="width: 130px;">
                                      <h6 class="mb-1">Petugas</h6>
                                      <p class="text-muted mb-0">{{ $stock->user->firstname }} {{ $stock->user->lastname }}</p>
                                    </div>
                                  </div>
                                </li>
                                @empty
                                <li class="list-group-item border-0">
                                  <div class="text-center align-items-center">
                                    <p class="mb-0 text-dark">Tidak ada stock opnam terinput</p>
                                  </div>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        @if ($tampilChecklist)
        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
            <div class="d-block mb-4 mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center p-3">
                          <h4 class="card-title mb-0">Checklist Outlet</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush border-top-0">
                                @forelse ($kegiatanData as $kd)  
                                <li class="list-group-item border-top-1 py-2 px-2">
                                  <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                      <div class="avtar avtar-xs bg-light-success rounded-circle">
                                        <i class="ti ti-check f-20"></i>
                                      </div>
                                    </div>  
                                    <div class="flex-grow-1 mx-2">
                                      <h6 class="mb-1">{{ $kd->kegiatan->nama }}</h6>
                                      <p class="mb-0 text-danger">{{ $kd->kegiatan_note }}</p>
                                    </div>
                                  </div>
                                </li>
                                @empty
                                <li class="list-group-item border-0 p-0">
                                  <div class="text-center align-items-center">
                                    <p class="mb-0 text-dark">Tidak ada checklist kegiatan terinput</p>
                                  </div>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <a href="{{ route('shift.cetak-laporan', $idShift) }}" target="_blank" class="btn btn-danger"><i class="fa fa-download me-2"></i> Download Laporan Harian</a>
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
    </script>
@endpush
