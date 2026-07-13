@extends('layouts.main')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/datepicker-bs5.min.css') }}" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <x-page-header title="List Transaksi Gerobak" module="Transaksi Gerobak - {{ tanggalIndo($tglNow) }}" >
                <li class="breadcrumb-item">Transaksi</li>
                <li class="breadcrumb-item">Transaksi Gerobak</li>
            </x-page-header>
        </div>
        @if (auth()->user()->is_admin)
        <div class="mb-0 row" style="width: 40%;">
            <div class="col-12">
              <label for="date_transHarian" class="mb-1 fw-bold">Pilih Tanggal</label>
              <div class="input-group date">
                <input type="text" class="form-control" placeholder="Pilih Tanggal" value="{{ converttanggal($tglNow) }}" id="date_transHarian" />
                <span class="input-group-text"><i class="feather icon-calendar"></i></span>
              </div>
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
                        
                        <div @if ($dataOutlet->gerobak->count() > 1) class="col-4" @else class="col-12" @endif>
                            <a href="{{ route('transaction.gerobak.assign-trans', $gerobak->id).'?tgl='.$tglNow }}">
                                <div class="pc-component">
                                    <div class="card">
                                      <div class="card-header d-flex align-items-center justify-content-between py-3">
                                        <div class="d-flex align-items-center justify-content-start mb-2">
                                            <h5 class="me-2">{{ $gerobak->nama }}</h5> 
                                            @if ($gerobak->trans_master != null && $gerobak->trans_master->trans_status == 'Approved')
                                            <span class="badge rounded-pill text-bg-success">Approved</span>
                                            @elseif ($gerobak->trans_master != null && $gerobak->trans_master->trans_status == 'Pending')
                                            <span class="badge rounded-pill text-bg-info">Sedang Ditinjau</span>
                                            @else
                                            @endif
                                        </div>
                                        <p class="mb-0 mt-0" style="font-size: 13px;">
                                            @if ($gerobak->employee != null)
                                            {{ $gerobak->employee->firstname }} {{ $gerobak->employee->lastname }}
                                            @else
                                            <span class="text-danger">User Tidak Ditemukan</span>
                                            @endif
                                        </p>
                                        <div class="dropdown">
                                          <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ti ti-dots-vertical f-18"></i>
                                          </a>
                                          <div class="dropdown-menu dropdown-menu-end" style="">
                                            <a target="_blanks" class="dropdown-item" href="{{ route('transaction.gerobak.assign-trans', $gerobak->id).'?tgl='.$tglNow }}">Detail</a>
                                            @if ($gerobak->trans->count() > 0)
                                            <a target="_blanks" class="dropdown-item" href="{{ route('transaction.gerobak.cetak-laporan', $gerobak->id).'?tgl='.$tglNow }}">Download Laporan</a>
                                            @endif
                                            <!--<a class="dropdown-item" href="#">Approve</a>-->
                                          </div>
                                        </div>
                                      </div>
                                      <ul class="list-group list-group-flush border-top-0">
                                        @php 
                                            $total_penjualan = 0; 
                                        @endphp
                                        @if ($gerobak->trans->count() > 0) 
                                            @foreach ($gerobak->trans as $trans)
                                            <li class="list-group-item" style="padding: 5px 25px;">
                                              <div class="d-flex align-items-center">
                                                @if($trans->row_type == 'varian')
                                                    <i class="ms-2 me-2 material-icons-two-tone">subdirectory_arrow_right</i>
                                                @endif  
                                                <div class="flex-grow-1 me-2">
                                                  <h6 class="mb-0">{{ $trans->product->nama }}</h6>
                                                  @php 
                                                    if ($trans->row_type == 'varian') {
                                                        $subtotal        = $trans->qty * $trans->product_harga; 
                                                    } else {
                                                        $subtotal        = $trans->original_product_sales * $trans->product_harga; 
                                                    }
                                                    $total_penjualan = $total_penjualan + $subtotal;
                                                  @endphp
                                                  <p class="mt-1 mb-0"> {{ rupiah($subtotal) }}</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                  @if($trans->row_type == 'varian')
                                                  <span class="badge bg-secondary rounded-pill">{{ $trans->qty }}</span>
                                                  @else
                                                    @php
                                                        $stockItem = $gerobak->stock[$trans->product_id] ?? null;
                                                    @endphp
                                                    @if ($stockItem)
                                                    <span class="badge bg-secondary rounded-pill">{{ $trans->product_sales }} / {{ $gerobak->stock[$trans->product_id]->sum('stock') ?? 0 }}</span>
                                                    @endif
                                                  @endif
                                                </div>
                                              </div>
                                            </li>
                                            @endforeach
                                        @endif
                                        @if ($gerobak->trans_nominal->count() > 0)
                                            @php 
                                                $total_nominal = 0; 
                                            @endphp
                                            @foreach ($gerobak->trans_nominal as $trans_nominal)
                                            <li class="list-group-item" style="padding: 5px 25px;">
                                              <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-2">
                                                  <h6 class="mb-0">{{ $trans_nominal->metode_bayar }}</h6>
                                                </div>
                                                <div class="flex-shrink-0">
                                                  @php 
                                                    $total_nominal = $total_nominal + $trans_nominal->transaction_amount;
                                                  @endphp
                                                  <p class="mt-1 mb-0">{{ rupiah($trans_nominal->transaction_amount) }}</p>
                                                </div>
                                              </div>
                                            </li>
                                            @endforeach
                                        @endif
                                        @if ($gerobak->pengeluaran->count() > 0)
                                            <li class="list-group-item" style="padding: 5px 25px;">
                                              <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-2">
                                                  <h6 class="mb-0">Total Pengeluaran</h6>
                                                </div>
                                                <div class="flex-shrink-0">
                                                  <p class="my-1 text-danger">{{ rupiah($gerobak->pengeluaran->sum('pengeluaran_harga')) }}</p>
                                                </div>
                                              </div>
                                            </li>
                                        @endif
                                        @php
                                            if ($gerobak->pengeluaran->count() > 0) {
                                                $total_penjualan_after_pengeluaran = $total_penjualan - $gerobak->pengeluaran->sum('pengeluaran_harga');
                                            } else {
                                                $total_penjualan_after_pengeluaran = $total_penjualan;
                                            }
                                        @endphp
                                        @if ($gerobak->trans->count() > 0 && $gerobak->trans_nominal->count() > 0)
                                            <li class="list-group-item" style="padding: 5px 25px;">
                                              <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-2">
                                                  <h6 class="mb-0">Total Penjualan</h6>
                                                  @if ($total_nominal == $total_penjualan_after_pengeluaran)
                                                  <p class="mt-1 mb-0 text-success">Balanced</p>
                                                  @elseif ($total_nominal > $total_penjualan_after_pengeluaran)
                                                  <p class="mt-1 mb-0 text-info">+ {{ rupiah($total_nominal - $total_penjualan_after_pengeluaran) }}</p>
                                                  @else
                                                  <p class="mt-1 mb-0 text-danger">- {{ rupiah($total_penjualan_after_pengeluaran - $total_nominal) }}</p>
                                                  @endif
                                                </div>
                                                <div class="flex-shrink-0 text-end">
                                                  <p class="mt-1 mb-0">{{ rupiah($total_penjualan) }}</p>
                                                  @if ($gerobak->pengeluaran->count() > 0)
                                                  <p class="mt-0 mb-0 text-danger">-{{ rupiah($gerobak->pengeluaran->sum('pengeluaran_harga')) }}</p>
                                                  <p class="mt-0 mb-0 fw-bold">{{ rupiah($total_penjualan_after_pengeluaran) }}</p>
                                                  @endif
                                                </div>
                                              </div>
                                            </li>
                                        @endif
                                      </ul>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
             
    </div>
    
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
    <script type="text/javascript">
        function formatDateToYMD(dateString) {
            const parts = dateString.split('/');
            const month = parts[0];
            const day   = parts[1];
            const year  = parts[2];
            return `${year}-${month}-${day}`;
        }
        
        (function () {
          const d_week = new Datepicker(document.querySelector('#date_transHarian'), {
            buttonClass: 'btn',
            autohide: true,
          });
        })();
        
        $('#date_transHarian').on('changeDate', function () {
            const selectedDate = $(this).val(); // Format: YYYY-MM-DD
            const formatedSelectedDate = formatDateToYMD(selectedDate);
            if (selectedDate) {
                let route = "{{ route('transaction.gerobak.index') }}"; 
                // tambahin query string ?tgl=xxxx
                route += "?tgl=" + encodeURIComponent(formatedSelectedDate);
                showLoader(),
                window.location.href = route;
            }
        });
    </script>
@endpush
