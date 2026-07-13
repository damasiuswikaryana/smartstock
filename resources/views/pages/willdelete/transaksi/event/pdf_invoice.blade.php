<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Invoice</title>
    <link rel="icon" href="{{ asset('img/logo/logo_sruuput.png') }}" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <style>
        @page {
            margin: .2in;
        }
        body, h2, h3, h4, h5, h6, p {
            font-family: 'Public Sans', sans-serif;
        }
        body { font-size: 12px; }
        h2 { font-size: 18px; }
        h3 { font-size: 16px; }
        h4 { font-size: 14px; margin: 1px; }
        h5 { font-size: 13px; margin: 0px; }
        h6 { font-size: 12px; margin: 0px; }
        p { font-size: 12px; padding: 0px; margin-top: 0px; margin-bottom: 0px; }
        .row { display: flex; padding: 0px; margin: auto; --bs-gutter-x: 1.5rem; --bs-gutter-y: 0; flex-wrap: wrap; }
        .g-3 { --bs-gutter-x: 1rem; --bs-gutter-y: 1rem; }
        .col-sm-6, .col-6 { width: 50%; float: left; }
        .col-sm-12, .col-12 { width: 100%; float: none; }
        .float-left { float: left; }
        .clearboth { float: none; clear: both; }
        .text-uppercase { text-transform: uppercase; }
        .text-sm-end { text-align: right; }
        .text-center { text-align: center; }
        .text-end { text-align: right !important; }
        .text-start { text-align: left !important; }
        .mb-2 { margin-bottom: 10px; }
        .ms-1 { margin-left: 7px; }
        .mt-2 { margin-top: 10px; }
        .mt-1 { margin-top: 5px; }
        .p-3 { padding: 0.5rem; }
        .pe-0 { padding-right: 0 !important; }
        .text-muted { opacity: 0.6; }
        .rounded { border-radius: 10px; }
        .border { border: 1px solid #dbe0e5; }
        .wid-40 { width: 20%; }
        .col { flex: 1 0 0%; }
        .col-auto { flex: 0 0 auto; width: auto; }
        .page-break { page-break-after: always; }
        .fw-bold { font-weight: bold; }
        .text-success { color: rgb(29, 233, 182); }
        .text-danger { color: rgb(244, 66, 54); }
        
        table, tr, td { vertical-align: baseline; }
        .table-bordered, .table-bordered th, .table-bordered td { border: 1px solid #dbe0e5; }
        .table {
            width: 100%;
            color: #000;
            background-color: transparent;
            border-collapse: collapse;
            border: 0px;
        }
        .table th, .table td {
            padding: 5px;
            vertical-align: baseline;
        }
    </style>
</head>

<body>
    <div class="row g-3">
      <div class="col-12 mb-2">
        <div class="row align-items-center g-3">
          <div class="col-sm-6">
            <div class="d-flex align-items-center mb-2 navbar-brand">
              <!--<img src="{{ asset('img/logo/'. $logo) }}" class="img-fluid logo-lg" alt="Logo Sruuput" width="200">-->
              <img src="{{ asset('logo_sruuput.webp') }}" class="img-fluid logo-lg" alt="Logo Sruuput" width="95">
            </div>
            <h4>No. Invoice: <span class="text-muted f-w-400">INV-{{ $data->no_invoice }}</span></h4>
          </div>
          <div class="col-sm-6 text-sm-end">
            @if ($data->status == "Paid")  
            <h4 class="mb-2"><span class="badge text-success rounded-pill ms-2">Paid</span></h4>
            @elseif ($data->status == "Cancel")
            <h4 class="mb-2"><span class="badge text-danger rounded-pill ms-2">Dibatalkan</span></h4>
            @else
            <h4 class="mb-2"><span class="badge text-secondary rounded-pill ms-2">Menunggu Pembayaran</span></h4>
            @endif
            <h6>Tanggal <span class="text-muted f-w-400">{{ tglIndo4($data->created_at) }}</span></h6>
            <h6>Tenggat Waktu <span class="text-muted f-w-400">{{ tglIndo4($data->due_date) }}</span></h6>
          </div>
          <div class="clearboth"></div>
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
      <div class="clearboth"></div>
      
      <div class="col-sm-12">
        <div class="border rounded p-3">
          <h5>Lokasi Acara</h5>
          <p class="mb-0">{{ $data->event_lokasi }}</p>
          <p class="mb-0">Waktu: {{ $data->event_waktu_awal }} - {{ $data->event_waktu_akhir }}</p>
        </div>
      </div>
      
      <div class="col-sm-12 mt-2">
        <div class="border rounded p-3">
          <h5>Catatan</h5>
          <p class="mb-0">{{ $data->event_note }}</p>
        </div>
      </div>
      
      <div class="col-12 mt-2">
        <div class="table-responsive">
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th>No.</th>
                <th width="180px">Produk</th>
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
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                    @if ($transProd->product->photo != null)
                    <img src="{{ asset('storage/produk/'.$transProd->product->photo) }}" alt="{{ $transProd->product->nama }}" class="wid-40 rounded float-left" />
                    @else
                    <img src="{{ asset('storage/produk/img_1.jpg') }}" alt="{{ $transProd->product->nama }}" class="wid-40 rounded float-left">
                    @endif
                    <div class="float-left ms-1">
                      <h6 class="mb-1">{{ $transProd->product->nama }}</h6>
                      <p class="text-muted f-12 mb-0">{{ $transProd->product->kode }}</p>
                    </div>
                    <div class="clearboth"></div>
                </td>
                <td class="text-center">{{ $transProd->product->category->title }}</td>
                <td>{{ rupiah($transProd->harga) }}</td>
                <td class="text-center">{{ $transProd->jumlah }}</td>
                @php 
                    $subtotal = $transProd->jumlah * $transProd->harga;
                    $total_trans = $total_trans + $subtotal;
                @endphp
                <td>{{ rupiah($subtotal) }}</td>
              </tr>
              @endforeach
              @foreach($data->eventBiayaLain as $biayaLain)
              <tr>
                  <td colspan="6"><b>Biaya Lain</b></td>
              </tr>
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td colspan="4">
                    {{ $biayaLain->ebl_nama }}
                </td>
                <td>{{ rupiah($biayaLain->ebl_amount) }}</td>
              </tr>
              @php 
                  $total_trans = $total_trans + $biayaLain->ebl_amount;
              @endphp
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
                                  
      <div class="col-12" style="width: 60%; margin-left: 40%;">
        <div class="">
          <div class="">
            @if ($data->discount != null)  
            <div class="col-sm-6">
              <div class="mb-3">
                <label class="form-label">Discount</label>
                <h5>{{ $data->discount.'%' }}</h5>
              </div>
            </div>
            @endif
            @if ($data->taxes != null)
            <div class="col-sm-6 text-end">
              <div class="mb-3">
                <label class="form-label">Taxes</label>
                <h5>{{ $data->taxes.'%' }}</h5>
              </div>
            </div>
            @endif
            <div class="clearboth"></div>
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
                                        
            <div class="col-6 mt-1"> <p class="text-muted mb-1 text-start">Sub Total :</p></div>
            <div class="col-6 mt-1"> <p class="f-w-600 mb-1 text-end">{{ rupiah($total_trans) }}</p></div>
            <div class="clearboth"></div>
                                        
            @if ($discount > 0)
            <div class="col-6"> <p class="text-muted mb-1 text-start">Discount :</p></div>
            <div class="col-6"> <p class="f-w-600 mb-1 text-end text-success">{{ rupiah($discount) }}</p></div>
            <div class="clearboth"></div>
            <div class="col-6"> <p class="text-muted mb-1 text-start">Total After Discount :</p></div>
            <div class="col-6"> <p class="f-w-600 mb-1 text-end">{{ rupiah($totalAfterDisc) }}</p></div>
            <div class="clearboth"></div>
            @endif
                                        
            @if ($taxes > 0)  
            <div class="col-6 mt-1"> <p class="text-muted mb-1 text-start">Taxes :</p></div>
            <div class="col-6 mt-1"> <p class="mb-1 text-end">{{ rupiah($taxes) }}</p></div>
            <div class="clearboth"></div>
            @endif
                                        
            @php
                $grand_total    = $totalAfterDisc + $taxes;
                $pembayaran     = $data->eventPembayaran->sum('pembayaran_jumlah');
                $total_tagihan  = $grand_total - $pembayaran;
            @endphp
            @if ($pembayaran > 0)
            <div class="col-6 mt-1 mt-2"> <p class="fw-bold mb-1 text-start">Grand Total :</p></div>
            <div class="col-6 mt-1 mt-2"> <p class="fw-bold mb-1 text-end">{{ rupiah($grand_total) }}</p></div>
            <div class="clearboth"></div>
            <div class="col-6"> <p class="fw-bold mb-1 text-start">Pembayaran :</p></div>
            <div class="col-6"> <p class="fw-bold mb-1 text-end text-success">{{ rupiah($pembayaran) }}</p></div>
            <div class="clearboth"></div>
            <div class="col-6"> <p class="fw-bold mb-1 text-start">Total Tagihan :</p></div>
            <div class="col-6"> <p class="fw-bold mb-1 text-end text-danger">{{ rupiah($total_tagihan) }}</p></div>
            <div class="clearboth"></div>
            @else
            <div class="col-6"> <p class="fw-bold mb-1 text-start">Total Tagihan :</p></div>
            <div class="col-6"> <p class="fw-bold mb-1 text-end text-danger">{{ rupiah($total_tagihan) }}</p></div>
            <div class="clearboth"></div>
            @endif
          </div>
        </div>
      </div>
      <div class="clearboth"></div>
      
      <div class="col-12" style="margin-top: 10px;">
        <label class="form-label">Catatan</label>
        <p class="mb-2">Harap melakukan transfer ke nomor rekening dibawah sesuai total tagihan yang tertera.</p>
        <div class="d-flex" style="margin-bottom: 20px;">
            <img src="{{ asset('assets/images/bank/'. $data->bankAccount->bank->bank_image) }}" width="100" class="float-left" />
            <div class="ms-2 float-left">
                <p class="mb-0">{{ $data->bankAccount->bank->bank_name }}</p>
                <p class="mb-0 fw-bold">{{ $data->bankAccount->bank_account_name }}</p>
                <p class="mb-0 fw-bold">{{ $data->bankAccount->bank_account_number }}</p>
            </div>
            <div class="clearboth"></div>
        </div>
        <p class="mb-0 text-center">Senang sekali bisa bekerja dengan Anda. Kami harap Anda akan mengingat kami untuk proyek di masa mendatang. Terima kasih!</p>
      </div>
    </div>
</body>

</html>
