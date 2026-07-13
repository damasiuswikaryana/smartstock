@extends('layouts.main')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="Detail Transaksi" module="Detail Transaksi Event" >
        <li class="breadcrumb-item">Transaksi</li>
        <li class="breadcrumb-item">Transaksi Event</li>
        <li class="breadcrumb-item"><a href="{{ route('transaction.event.index') }}">List Transaksi</a></li>
    </x-page-header>
    
    <ul class="nav nav-pills mb-4 mt-3" id="pills-tab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active p-3" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Detail Transaksi</a>
      </li>
      <li class="nav-item">
        <a class="nav-link p-3" id="pills-bl-tab" data-bs-toggle="pill" href="#pills-bl" role="tab" aria-controls="pills-bl" aria-selected="false">Biaya Lainnya</a>
      </li>
      <li class="nav-item">
        <a class="nav-link p-3" id="pills-profile-tab" data-bs-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Riwayat Pembayaran</a>
      </li>
      <li class="nav-item">
        <a class="nav-link p-3" id="pills-contact-tab" data-bs-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Tagihan Pembayaran</a>
      </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
      <!--event detail-->
      <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
        <div class="d-block mb-4 mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center p-3">
                      <h4 class="card-title mb-0">Event Detail</h4>
                      <h2 class="mb-0">
                          @if ($data->status == "Waiting for Payment")
                          <span class="badge bg-light-secondary">Menunggu Pembayaran</span>
                          @elseif ($data->status == "Paid")
                          <span class="badge bg-light-success">Lunas</span>
                          @else
                          <span class="badge bg-light-danger">Cancel</span>
                          @endif
                      </h2>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 pt-0">
                              <div class="row">
                                <div class="col-md-6">
                                  <p class="mb-1 text-muted">Nama Event</p>
                                  <p class="mb-0">{{ $data->event_nama }}</p>
                                </div>
                                <div class="col-md-6">
                                  <p class="mb-1 text-muted">Tanggal Event</p>
                                  <p class="mb-0">{{ tanggalIndo($data->event_date) }}</p>
                                </div>
                              </div>
                            </li>
                            <li class="list-group-item px-0">
                              <p class="mb-1 text-muted">Lokasi Event</p>
                              <p class="mb-0">{{ $data->event_lokasi }} <br>Waktu: {{ $data->event_waktu_awal }} - {{ $data->event_waktu_akhir }}</p>
                            </li>
                            <li class="list-group-item px-0">
                              <div class="row">
                                <div class="col-md-6">
                                  <p class="mb-1 text-muted">Customer</p>
                                  <p class="mb-0">{{ $data->customer->nama }}</p>
                                </div>
                                <div class="col-md-6">
                                  <p class="mb-1 text-muted">Perusahaan</p>
                                  <p class="mb-0">{{ $data->company }}</p>
                                </div>
                              </div>
                            </li>
                            <li class="list-group-item px-0">
                              <div class="row">
                                <div class="col-md-6">
                                  <p class="mb-1 text-muted">Email</p>
                                  <p class="mb-0">{{ $data->customer->email }}</p>
                                </div>
                                <div class="col-md-6">
                                  <p class="mb-1 text-muted">Nomor Telepon</p>
                                  <p class="mb-0">{{ $data->customer->phone }}</p>
                                </div>
                              </div>
                            </li>
                            <li class="list-group-item px-0">
                              <div class="row">
                                <div class="col-md-6">
                                  <p class="mb-1 text-muted">Bank</p>
                                  <p class="mb-0">{{ $data->bankAccount->bank->bank_name }}</p>
                                </div>
                                <div class="col-md-6">
                                  <p class="mb-1 text-muted">Akun Bank</p>
                                  <p class="mb-0">{{ $data->bankAccount->bank_account_number }} - {{ $data->bankAccount->bank_account_name }}</p>
                                </div>
                              </div>
                            </li>
                            <li class="list-group-item px-0">
                              <div class="row">
                                <div class="col-md-6">
                                  <p class="mb-1 text-muted">Dibuat oleh</p>
                                  <p class="mb-0">{{ $data->user->firstname }} {{ $data->user->lastname }}</p>
                                </div>
                                <div class="col-md-6">
                                  <p class="mb-1 text-muted">Outlet</p>
                                  <p class="mb-0">{{ $data->outlet->nama }}</p>
                                </div>
                              </div>
                            </li>
                            <li class="list-group-item px-0">
                              <div class="row">
                                <div class="col-md-6">
                                  <p class="mb-1 text-muted">Dibuat pada</p>
                                  <p class="mb-0">{{ tanggalIndoWaktuLidgkap($data->created_at) }}</p>
                                </div>
                                <div class="col-md-6">
                                  <p class="mb-1 text-muted">Terakhir Update</p>
                                  <p class="mb-0">{{ tanggalIndoWaktuLidgkap($data->updated_at) }}</p>
                                </div>
                              </div>
                            </li>
                            <li class="list-group-item px-0">
                              <p class="mb-1 text-muted">Catatan</p>
                              <p class="mb-0">{{ $data->event_note }}</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header p-3">
                      <h4 class="card-title mb-0">Product Order</h4>
                    </div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                          <table class="table table-styling mb-0">
                            <thead>
                              <tr>
                                <th>No.</th>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                              </tr>
                            </thead>
                            <tbody>
                              @php
                                $total_trans = 0;
                              @endphp
                              @foreach($data->eventProduct as $transProd)    
                              <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="row">
                                        <div class="col-auto pe-0">
                                          @if ($transProd->product->photo != null)
                                          <img src="{{ asset('storage/produk/'.$transProd->product->photo) }}" alt="{{ $transProd->product->nama }}" class="wid-40 rounded" />
                                          @else
                                          <img src="{{ asset('storage/produk/img_1.jpg') }}" alt="{{ $transProd->product->nama }}" class="wid-40 rounded">
                                          @endif
                                        </div>
                                        <div class="col">
                                          <h6 class="mb-1">{{ $transProd->product->nama }}</h6>
                                          <p class="text-muted f-12 mb-0">{{ $transProd->product->kode }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $transProd->product->category->title }}</td>
                                <td>{{ rupiah($transProd->harga) }}</td>
                                <td>{{ $transProd->jumlah }}</td>
                                @php 
                                    $subtotal = $transProd->jumlah * $transProd->harga;
                                    $total_trans = $total_trans + $subtotal;
                                @endphp
                                <td>{{ rupiah($subtotal) }}</td>
                              </tr>
                              @endforeach
                            </tbody>
                            <tfoot class="table-info">
                              <tr>
                                <td class="fw-bold">#</td>
                                <td class="fw-bold">Total Transaksi</td>
                                <td class="fw-bold"></td>
                                <td class="fw-bold"></td>
                                <td class="fw-bold">{{ $data->eventProduct->sum('jumlah') }}</td>
                                <td class="fw-bold"><h4 class="mb-0">{{ rupiah($total_trans) }}</h4></td>
                              </tr>
                              @if ($data->discount_amount != null)
                              <tr>
                                <td class="fw-bold"></td>
                                <td class="fw-bold">Discount</td>
                                <td class="fw-bold"></td>
                                <td class="fw-bold"></td>
                                <td class="fw-bold">{{ $data->discount != null ? $data->discount.'%' : '' }}</td>
                                <td class="fw-bold"><h4 class="mb-0">{{ rupiah($data->discount_amount) }}</h4></td>
                              </tr>
                              @endif
                              @if ($data->taxes_amount != null)
                              <tr>
                                <td class="fw-bold"></td>
                                <td class="fw-bold">Pajak</td>
                                <td class="fw-bold"></td>
                                <td class="fw-bold"></td>
                                <td class="fw-bold">{{ $data->taxes != null ? $data->taxes.'%' : '' }}</td>
                                <td class="fw-bold"><h4 class="mb-0">{{ rupiah($data->taxes_amount) }}</h4></td>
                              </tr>
                              @endif
                            </tfoot>
                          </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <!--event detail-->
      
      <!--event biaya lainnya-->
      <div class="tab-pane fade" id="pills-bl" role="tabpanel" aria-labelledby="pills-bl-tab">
        <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
            <div class="col-6">
                <button type="button" class="btn btn-shadow btn-danger me-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalAddBiayaLainnya"><i class="ph-duotone ph-plus-circle icon-search me-2"></i> Input Biaya Lainnya</button>
            </div>
            <div class="col-6 text-end">
                <div class="form-search">
                    <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                    <input type="search" id="search2" class="form-control" placeholder="Cari data disini...">
                </div>
            </div>
        </div>
        @php
            $thead = ['No', 'Nama Biaya', 'Jumlah', 'Opsi'];
        @endphp
        <x-datatable :thead=$thead :filter="null" id="myTable2">
        </x-datatable>
      </div>
      <!--event biaya lainnya-->
      
      <!--event pembayaran-->
      <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
        <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
            <div class="col-6">
                <button type="button" class="btn btn-shadow btn-danger me-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalAddPembayaran"><i class="ph-duotone ph-plus-circle icon-search me-2"></i> Input Pembayaran</button>
            </div>
            <div class="col-6 text-end">
                <div class="form-search">
                    <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                    <input type="search" id="search" class="form-control" placeholder="Cari data disini...">
                </div>
            </div>
        </div>
        @php
            $thead = ['Tanggal', 'Metode', 'Jumlah', 'Sisa', 'Bukti Bayar', 'Dibuat', 'Opsi'];
        @endphp
        <x-datatable :thead=$thead :filter="null">
        </x-datatable>
      </div>
      <!--event pembayaran-->
      
      <!--event invoice-->
      <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
          <div class="d-block mb-4 mt-3">
              <div class="col-12">
                  <div class="card">
                      <div class="card-body">
                        <div class="mb-3 d-print-none">
                          <ul class="list-inline ms-auto mb-0 d-flex justify-content-end flex-wrap">
                            <li class="list-inline-item align-bottom me-2">
                              <a href="{{ route('transaction.event.cetak-invoice', $data->id) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
                                <i class="ph-duotone ph-download-simple f-22"></i>
                              </a>
                            </li>
                          </ul>
                        </div>
                        
                        <div class="card shadow-none bg-body mb-0">
                          <div class="card-body">
                            <div class="card">
                              <div class="card-body">
                                <div class="row g-3">
                                  <div class="col-12">
                                    <div class="row align-items-center g-3">
                                      <div class="col-sm-6">
                                        <div class="d-flex align-items-center mb-2 navbar-brand">
                                          <!--<img src="{{ asset('img/logo/logo_wide_2.png') }}" class="img-fluid logo-lg" alt="images" width="300">-->
                                          <img src="{{ asset('logo_sruuput.webp') }}" class="img-fluid logo-lg" alt="Logo Sruuput" width="150">
                                        </div>
                                        <h4>No. Invoice: <span class="text-muted f-w-400">INV-{{ $data->no_invoice }}</span></h4>
                                        <p class="mb-0"></p>
                                      </div>
                                      <div class="col-sm-6 text-sm-end">
                                        @if ($data->status == "Paid")  
                                        <h4 class="mb-2"><span class="badge bg-success rounded-pill ms-2">Paid</span></h4>
                                        @elseif ($data->status == "Cancel")
                                        <h4 class="mb-2"><span class="badge bg-danger rounded-pill ms-2">Dibatalkan</span></h4>
                                        @else
                                        <h4 class="mb-2"><span class="badge bg-secondary rounded-pill ms-2">Menunggu Pembayaran</span></h4>
                                        @endif
                                        <h6>Tanggal <span class="text-muted f-w-400">{{ tglIndo4($data->created_at) }}</span></h6>
                                        <h6>Tenggat Waktu <span class="text-muted f-w-400">{{ tglIndo4($data->due_date) }}</span></h6>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-sm-6">
                                    <div class="border rounded p-3">
                                      <h6 class="mb-0">Dari:</h6>
                                      <h5>Sruuput Kopi</h5>
                                      <p class="mb-0">Jl. Gadung No. 8, Dangin Puri Kangin, Kec. Denpasar Timur, Denpasar, Bali 80236</p>
                                      <p class="mb-0">+62851-7339-1344</p>
                                    </div>
                                  </div>
                                  <div class="col-sm-6">
                                    <div class="border rounded p-3">
                                      <h6 class="mb-0">Kepada:</h6>
                                      <h5>{{ $data->customer->nama }} - <span class="text-muted">{{ $data->company }}</span></h5>
                                      <p class="mb-0">{{ $data->customer->alamat }}</p>
                                      <p class="mb-0">{{ $data->customer->phone }}</p>
                                      <p class="mb-0">{{ $data->customer->email }}</p>
                                    </div>
                                  </div>
                                  <div class="col-sm-12">
                                    <div class="border rounded p-3">
                                      <h5>Lokasi Acara</h5>
                                      <p class="mb-0">{{ $data->event_lokasi }}</p>
                                      <p class="mb-0">Waktu: {{ $data->event_waktu_awal }} - {{ $data->event_waktu_akhir }}</p>
                                    </div>
                                  </div>
                                  <div class="col-sm-12">
                                    <div class="border rounded p-3">
                                      <h5>Catatan</h5>
                                      <p class="mb-0">{{ $data->event_note }}</p>
                                    </div>
                                  </div>
                                  <div class="col-12">
                                    <div class="table-responsive">
                                      <table class="table table-hover mb-0">
                                        <thead>
                                          <tr>
                                            <th>No.</th>
                                            <th>Produk</th>
                                            <th>Kategori</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          @php
                                            $total_trans = 0;
                                          @endphp    
                                          @foreach($data->eventProduct as $transProd)    
                                          <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-auto pe-0">
                                                      @if ($transProd->product->photo != null)
                                                      <img src="{{ asset('storage/produk/'.$transProd->product->photo) }}" alt="{{ $transProd->product->nama }}" class="wid-40 rounded" />
                                                      @else
                                                      <img src="{{ asset('storage/produk/img_1.jpg') }}" alt="{{ $transProd->product->nama }}" class="wid-40 rounded">
                                                      @endif
                                                    </div>
                                                    <div class="col">
                                                      <h6 class="mb-1">{{ $transProd->product->nama }}</h6>
                                                      <p class="text-muted f-12 mb-0">{{ $transProd->product->kode }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $transProd->product->category->title }}</td>
                                            <td>{{ rupiah($transProd->harga) }}</td>
                                            <td>{{ $transProd->jumlah }}</td>
                                            @php 
                                                $subtotal = $transProd->jumlah * $transProd->harga;
                                                $total_trans = $total_trans + $subtotal;
                                            @endphp
                                            <td>{{ rupiah($subtotal) }}</td>
                                          </tr>
                                          @endforeach
                                          <tr>
                                              <td colspan="6"><b>Biaya Lainnya</b></td>
                                          </tr>
                                          @foreach($biaya_lain as $bl)
                                          <tr>
                                              <td>{{ $loop->iteration }}</td>
                                              <td colspan="4">{{ $bl->ebl_nama }}</td>
                                              <td>{{ rupiah($bl->ebl_amount) }}</td>
                                              @php
                                                $total_trans = $total_trans + $bl->ebl_amount;
                                              @endphp
                                          </tr>
                                          @endforeach
                                        </tbody>
                                      </table>
                                    </div>
                                    <div class="text-start">
                                      <hr class="mb-2 mt-1">
                                    </div>
                                  </div>
                                  
                                  <div class="col-12">
                                    <div class="invoice-total ms-auto">
                                      <div class="row">
                                        @if ($data->discount != null)  
                                        <div class="col-5">
                                          <div class="mb-3">
                                            <label class="form-label">Discount</label>
                                            <h5>{{ $data->discount.'%' }}</h5>
                                          </div>
                                        </div>
                                        @endif
                                        @if ($data->taxes != null)
                                        <div class="col-6 text-end">
                                          <div class="mb-3">
                                            <label class="form-label">Taxes</label>
                                            <h5>{{ $data->taxes.'%' }}</h5>
                                          </div>
                                        </div>
                                        @endif
                                        
                                        @php
                                            if ($data->discount_amount != null) {
                                                $discount = $data->discount_amount;
                                            } elseif ($data->discount != null) {
                                                $discount = $total_trans * $data->discount / 100;
                                            } else {
                                                $discount = 0;
                                            }
                                            
                                            if ($data->taxes_amount != null) {
                                                $taxes = $data->taxes_amount;
                                            } elseif ($data->taxes != null) {
                                                $taxes = ($total_trans - $discount) * $data->taxes / 100;
                                            } else {
                                                $taxes = 0;
                                            }
                                            
                                            $totalAfterDisc = $total_trans - $discount;
                                        @endphp
                                        
                                        <div class="col-6"> <p class="text-muted mb-1 text-start">Sub Total :</p></div>
                                        <div class="col-6"> <p class="f-w-600 mb-1 text-end">{{ rupiah($total_trans) }}</p></div>
                                        
                                        @if ($discount > 0)
                                        <div class="col-6"> <p class="text-muted mb-1 text-start">Discount :</p></div>
                                        <div class="col-6"> <p class="f-w-600 mb-1 text-end text-success">{{ rupiah($discount) }}</p></div>
                                        <div class="col-6"> <p class="text-muted mb-1 text-start">Total After Discount :</p></div>
                                        <div class="col-6"> <p class="f-w-600 mb-1 text-end">{{ rupiah($totalAfterDisc) }}</p></div>
                                        @endif
                                        
                                        @if ($taxes > 0)  
                                        <div class="col-6 mt-3"> <p class="text-muted mb-1 text-start">Taxes :</p></div>
                                        <div class="col-6 mt-3"> <p class="f-w-600 mb-1 text-end">{{ rupiah($taxes) }}</p></div>
                                        @endif
                                        
                                        @php
                                            $grand_total    = $totalAfterDisc + $taxes;
                                            $pembayaran     = $data->eventPembayaran->sum('pembayaran_jumlah');
                                            $total_tagihan  = $grand_total - $pembayaran;
                                        @endphp
                                        @if ($pembayaran > 0)
                                        <div class="col-6 mt-3"> <p class="f-w-600 mb-1 text-start">Grand Total :</p></div>
                                        <div class="col-6 mt-3"> <p class="f-w-600 mb-1 text-end">{{ rupiah($grand_total) }}</p></div>
                                        <div class="col-6"> <p class="f-w-600 mb-1 text-start">Pembayaran :</p></div>
                                        <div class="col-6"> <p class="f-w-600 mb-1 text-end text-success">{{ rupiah($pembayaran) }}</p></div>
                                        <div class="col-6"> <p class="f-w-600 mb-1 text-start">Total Tagihan :</p></div>
                                        <div class="col-6"> <p class="f-w-600 mb-1 text-end text-danger">{{ rupiah($total_tagihan) }}</p></div>
                                        @else
                                        <div class="col-6"> <p class="f-w-600 mb-1 text-start">Total Tagihan :</p></div>
                                        <div class="col-6"> <p class="f-w-600 mb-1 text-end text-danger">{{ rupiah($total_tagihan) }}</p></div>
                                        @endif
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12">
                                    <label class="form-label">Catatan</label>
                                    <p class="mb-2">Harap melakukan transfer ke nomor rekening dibawah sesuai dengan total tagihan yang tertera.</p>
                                    <div class="d-flex mb-5">
                                        <img src="{{ asset('assets/images/bank/'. $data->bankAccount->bank->bank_image) }}" width="100" />
                                        <div class="ms-2">
                                            <p class="mb-0">{{ $data->bankAccount->bank->bank_name }}</p>
                                            <p class="mb-0 fw-bold">{{ $data->bankAccount->bank_account_name }}</p>
                                            <p class="mb-0 fw-bold">{{ $data->bankAccount->bank_account_number }}</p>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-center">Senang sekali bisa bekerja dengan Anda. Kami harap Anda akan mengingat kami untuk proyek di masa mendatang. Terima kasih!</p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
              </div>
          </div>
          
      </div>
      <!--event invoice-->
    </div>
    
    <!--Add Pembayaran-->
    <div id="modalAddPembayaran" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalAddPembayaranTitle">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Input Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body was-validated" action="#" method="post" id="form-input-pembayaran">
                    <div class="px-4">
                        @csrf
                        @method('POST')
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Sisa Pembayaran:</label>
                            <div class="col-lg-8">
                                <input type="hidden" value="{{ $sisa_pembayaran }}" id="pembayaran_sisa_awal" />
                                <input type="text" class="form-control number-separator" value="{{ pecahTanpaRp($sisa_pembayaran) }}" name="pembayaran_sisa" id="pembayaran_sisa" readonly />
                                <small id="pembayaran_sisaHelp_nonLunas" class="form-text text-danger">Pembayaran belum lunas</small>
                                <small id="pembayaran_sisaHelp_lunas" class="form-text text-success">Pembayaran lunas</small>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Jumlah Pembayaran:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control is_invalid number-separator" placeholder="Jumlah Pembayaran" name="pembayaran_jumlah" required />
                                <div class="invalid-feedback">Pembayaran dalam bentuk rupiah.</div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Metode Pembayaran:</label>
                            <div class="col-lg-8">
                                <select class="form-control is_invalid" name="pembayaran_method" required>
                                    <option disabled selected value="">Pilih Metode Pembayaran</option>
                                    <option value="Transfer">Transfer Bank</option>
                                    <option value="Cash">Cash</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="form-input-pembayaran">Input Pembayaran</button>
                </div>
            </div>
        </div>
    </div>
    
    <!--Add Biaya Lainnya-->
    <div id="modalAddBiayaLainnya" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalAddBiayaLainnyaTitle">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Input Biaya Lainnya</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body was-validated" action="#" method="post" id="form-input-biaya-lainnya">
                    <div class="px-4">
                        @csrf
                        @method('POST')
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Nama Biaya:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control is_invalid" placeholder="Biaya Lainnya" name="bl_name" required />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Jumlah Biaya:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control is_invalid number-separator" placeholder="Jumlah Pembayaran" name="bl_jumlah" required />
                                <div class="invalid-feedback">Pembayaran dalam bentuk rupiah.</div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="form-input-biaya-lainnya">Input Biaya Lainnya</button>
                </div>
            </div>
        </div>
    </div>
    
    
    <div id="modalEdit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalEditTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/dataTables.fixedColumns.min.js') }}"></script>
    <script type="text/javascript">
        $("#modalEdit").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-content").load(link.attr("href"));
        });
        
        // MODUL PEMBAYARAN
        $(document).ready(function () {
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('a[href="' + activeTab + '"]').tab('show');
            }
            $('a[data-bs-toggle="pill"], a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            
            function parseRupiah(str) {
                return parseInt(str.replace(/[^\d]/g, '')) || 0;
            }
            $('input[name="pembayaran_jumlah"]').on('input', function () {
                const totalSisaAwal = $('#pembayaran_sisa_awal').val();
                const jumlahBayar = parseRupiah($(this).val());
                const sisaBaru = totalSisaAwal - jumlahBayar;
        
                $('#pembayaran_sisa').val(sisaBaru.toLocaleString('id-ID'));
                
                if (sisaBaru <= 0) {
                    $('#pembayaran_sisaHelp_lunas').show();
                    $('#pembayaran_sisaHelp_nonLunas').hide();
                } else {
                    $('#pembayaran_sisaHelp_lunas').hide();
                    $('#pembayaran_sisaHelp_nonLunas').show();
                }
            });
            $('#pembayaran_sisaHelp_lunas').hide();
            $('#pembayaran_sisaHelp_nonLunas').hide();
        });
        
        let table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('transaction.event.detail', $data->id) }}",
                data: { type: 'pembayaran' }
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
                { data: 'pembayaran_date',      name: 'pembayaran_date', class: 'text-center' },
                { data: 'pembayaran_method',    name: 'pembayaran_method', class: 'text-center' },
                { data: 'pembayaran_jumlah',    name: 'pembayaran_jumlah', class: 'text-center' },
                { data: 'pembayaran_sisa',      name: 'pembayaran_sisa', class: 'text-center' },
                { data: 'pembayaran_bukti',     name: 'pembayaran_bukti', class: 'text-center' },
                { data: 'created_at',           name: 'created_at', class: 'text-center', visible: true, },
                { data: 'action',               name: 'action', class: 'text-center', orderable: false, searchable: false, },
            ]
        });
        
        let table2 = $('#myTable2').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('transaction.event.detail', $data->id) }}",
                data: { type: 'biayalain' }
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
                { data: null, name: 'no', class: 'text-center', orderable: false, searchable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'ebl_nama',      name: 'ebl_nama', class: 'text-center' },
                { data: 'ebl_amount',    name: 'ebl_amount', class: 'text-center' },
                { data: 'action',        name: 'action', class: 'text-center', orderable: false, searchable: false, },
            ]
        });

        $('#search').keyup(function() {
            table.search($(this).val()).draw();
        });
        
        // $('#search2').keyup(function() {
        //     table2.search($(this).val()).draw();
        // });
        
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
        
        $('#form-input-pembayaran').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
              url: "{{ route('transaction.event.pembayaran_store', $data->id) }}",
              method: 'POST',
              data: $(this).serialize(),
              beforeSend: showLoader(),
              success: function(response) {
                if (response.success)  {
                    $('#form-input-pembayaran')[0].reset();
                    table.ajax.reload(null, false);
                    hideLoader();
                    $('#modalAddPembayaran').modal('hide');
                    showToastSuccess("Pembayaran berhasil ditambahkan");
                } else {
                    hideLoader();
                    showToastError(response.message);
                }
              },
              error: function(xhr) {
                hideLoader();
                showToastError("Gagal menambahkan data");
              }
            });
        });
        
        $(document).on('click', '.btn-delete-pembayaran', function () {
            let id = $(this).data('id');
            var url = "{{ route('transaction.event.pembayaran_destroy', ':id:') }}";
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
                        showToastSuccess("Berhasil menghapus pembayaran");
                    },
                    error: function () {
                        hideLoader();
                        showToastError("Terjadi kesalahan saat menghapus pembayaran");
                    }
                });
            }
        });
        
        $('#form-input-biaya-lainnya').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
              url: "{{ route('transaction.event.biayalain_store', $data->id) }}",
              method: 'POST',
              data: $(this).serialize(),
              beforeSend: showLoader(),
              success: function(response) {
                if (response.success)  {
                    $('#form-input-pembayaran')[0].reset();
                    // table2.ajax.reload(null, false);
                    hideLoader();
                    $('#modalAddBiayaLainnya').modal('hide');
                    showToastSuccess("Biaya lainnya berhasil ditambahkan");
                } else {
                    hideLoader();
                    showToastError(response.message);
                }
              },
              error: function(xhr) {
                hideLoader();
                showToastError("Gagal menambahkan data");
              }
            });
        });
        
        $(document).on('click', '.btn-delete-biaya-lain', function () {
            let id = $(this).data('id');
            var url = "{{ route('transaction.event.biayalain_destroy', ':id:') }}";
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
                        showToastSuccess("Berhasil menghapus biaya lain");
                    },
                    error: function () {
                        hideLoader();
                        showToastError("Terjadi kesalahan saat menghapus biaya lain");
                    }
                });
            }
        });
        
    </script>
@endpush
