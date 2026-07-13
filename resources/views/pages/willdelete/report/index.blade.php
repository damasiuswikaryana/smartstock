@extends('layouts.main')

@push('css')
<style>
    .card-img-overlay {
      background: rgba(0, 0, 0, 0.5);
    }
</style>
@endpush

@section('content')
    <x-page-header title="Pilih Outlet" module="Pilih Outlet" >
        <li class="breadcrumb-item">Laporan</li>
        <li class="breadcrumb-item">Transaksi Harian</li>
    </x-page-header>
    
    <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
        <div class="col-xl-12">
            <div class="row">
                @foreach($data_outlet as $dataOutlet)
                <a href="{{ route('report.harian', ['ido' => $dataOutlet->id, 'tgl' => $tanggal]) }}" class="col-12 col-lg-6 col-xl-6 col-md-6 col-sm-12">
                    <div class="card bg-dark">
                      @if ($dataOutlet->photo != null)    
                      <img class="img-fluid card-img" src="{{ asset('storage/outlet/'.$dataOutlet->photo) }}" alt="{{ $dataOutlet->nama }}" />
                      @else
                      <img class="img-fluid card-img" src="{{ asset('storage/outlet/outlet_1.jpg') }}" alt="{{ $dataOutlet->nama }}">
                      @endif
                      <div class="card-img-overlay">
                        <h5 class="card-title text-white">{{ $dataOutlet->nama }}</h5>
                        <p class="card-text text-white">{{ $dataOutlet->alamat }}</p>
                      </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('js')
<script type="text/javascript">
</script>
@endpush
