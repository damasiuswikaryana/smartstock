@extends('layouts.main')

@push('css')
@endpush

@php
$id_user            = auth()->user()->id;
$cek_gerobak_roles  = \App\Models\Gerobak::select('id')->where('user_id', $id_user)->first();
@endphp

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <x-page-header title="List Outlet" module="Assign Stok - {{ tanggalIndo(date('Y-m-d')) }}" >
                <li class="breadcrumb-item">Transaksi</li>
                <li class="breadcrumb-item">Assign Stok</li>
            </x-page-header>
        </div>
        @if ($cek_gerobak_roles == null)
        <div class="mb-0 row" style="width: 40%;">
            <div class="col-12 text-end">
              <a target="_blank" href="{{ route('transaction.assignStok.cetak-laporan-all').'?tgl='.$tglNow }}" class="btn btn-outline-dark w-75"><i class="fa fa-download me-2"></i> Rekap Semua Gerobak</a>
            </div>
        </div>
        @endif
    </div>
    
    
    <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
        <div class="col-xl-12">
            <div class="row">
                @foreach($data_outlet as $dataOutlet)
                <div class="col-12 mb-4">
                    <h3>{{ $dataOutlet->nama }}</h3>
                    <p class="mb-3">{{ $dataOutlet->alamat }}</p>
                    <div class="row">
                        @foreach($dataOutlet->gerobak as $gerobak)
                        
                        <div class="col-4">
                                <div class="pc-component">
                                    <div class="card text-center">
                                        <div class="card-header py-3">
                                            <div class="d-flex align-items-center justify-content-between text-start">
                                                <div class="">
                                                    <h5 class="me-2">{{ $gerobak->nama }}</h5>
                                                    <p class="card-text mb-1">
                                                        @if ($gerobak->employee != null)
                                                        Petugas: {{ $gerobak->employee->firstname }} {{ $gerobak->employee->lastname }}
                                                        @else
                                                        Petugas: Petugas tidak ditemukan
                                                        @endif
                                                    </p>
                                                    <div id="badge-stock-gerobak-{{ $gerobak->id }}">
                                                        @if ($gerobak->stock->count() > 0)
                                                        <span class="badge bg-success"><i class="fa fa-check-circle"></i> Assign Stock</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="dropdown">
                                                  <a target='_blanks' class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical f-18"></i>
                                                  </a>
                                                  <div class="dropdown-menu dropdown-menu-end" style="">
                                                    <a target='_blanks' class="dropdown-item" href="{{ route('transaction.assignStok.cetak-laporan', $gerobak->id).'?tgl='.$tglNow }}">Download Laporan</a>
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <a href="{{ route('transaction.assignStok.assign', $gerobak->id) }}" id="btnModal">
                                                <span id="btn-stock-gerobak-{{ $gerobak->id }}" class="btn {{ $gerobak->stock->count() > 0 ? 'btn-secondary' : 'btn-primary' }}">{{ $gerobak->kode }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
             
    </div>
    
    <div id="modalEdit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalEditTitle">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <button class="btn btn-primary lh-1" type="button" disabled="">
                  <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                  Loading...
                </button>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).on('click', '#btnModal', function (e) {
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
        
        $('#modalEdit').on('hidden.bs.modal', function () {
            $('#modalEdit .modal-content').html(`<button class="btn btn-primary lh-1" type="button" disabled="">
                  <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                  Loading...
                </button>`);
        });
    </script>
@endpush
