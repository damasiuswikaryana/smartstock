<!doctype html>
<html lang="en">
@php
    $total_penjualan    = 0;
    $total_terbawa      = 0;
    $total_terjual      = 0;    
    if ($data_trans != null) {
        $cashNominal = $data_trans->transaction_nominal->firstWhere('metode_bayar', 'Cash');
        $qrisNominal = $data_trans->transaction_nominal->firstWhere('metode_bayar', 'Qris');
    } else {
        $cashNominal = null;
        $qrisNominal = null;
    }
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Laporan</title>
    <link rel="icon" href="{{ asset('logo_sruuput.png') }}" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <style>
        @if ($printer == "iware_xs_80ul" || $printer == "iware_xs_80bt")
            /*setingan untuk printer iware xs80ul*/
            @page { margin: 0 !important; }
            body { margin: 0 !important; padding: 1mm !important; width: 70mm; font-size: 9px; }
            h4 { font-size: 12px !important; margin: 0px; }
            h5 { font-size: 10px !important; margin: 0px; }
            h6 { font-size: 10px !important; margin: 0px; }
            p { font-size: 9px !important; padding: 0px; margin-top: 0px; margin-bottom: 5px; }
        @else
            /*setingan untuk printer thermal universal*/
            @page { margin: 0 !important; }
            body { margin: 0 !important; padding: 1mm !important; width: 60mm; font-size: 7px; }
            h4 { font-size: 10px !important; margin: 0px; }
            h5 { font-size: 8px !important; margin: 0px; }
            h6 { font-size: 7.5px !important; margin: 0px; }
            p { font-size: 8px !important; padding: 0px; margin-top: 0px; margin-bottom: 5px; }
        @endif
        
        * { box-sizing: border-box; }
        body, h2, h3, h4, h5, h6, p { font-family: 'Public Sans', sans-serif; }
        h2 { font-size: 18px; }
        h3 { font-size: 16px; }
        
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
        .border { border: 1px solid #000; }
        .wid-40 { width: 20%; }
        .col { flex: 1 0 0%; }
        .col-auto { flex: 0 0 auto; width: auto; }
        .page-break { page-break-after: always; }
        .fw-bold { font-weight: bold; }
        .text-success { color: rgb(29, 233, 182); }
        .text-danger { color: rgb(244, 66, 54); }
        table, tr, td { vertical-align: baseline; }
        .table-bordered, .table-bordered th, .table-bordered td { border: 1px solid #000; }
        .table { width: 100%; color: #000; background-color: transparent; border-collapse: collapse; border: 0px; }
        .table th, .table td { padding: 2px; vertical-align: middle; }
    </style>
</head>

<body>
    <div class="row">
      <div class="col-12 mb-2">
        <div class="row align-items-center">
          <div class="col-sm-12">
            <div class="d-flex align-items-center navbar-brand">
              <img src="{{ asset('logo_sruuput.webp') }}" class="img-fluid logo-lg" alt="Logo Sruuput" width="95">
            </div>
            <h4 style="text-align:center">Ringkasan Transaksi Harian</h4>
            <h5 style="text-align:center">{{ tanggalIndo($tglNow) }}</h5>
            
            <table class="table" style="margin-top: 2mm;">
                <tr>
                    <td>Gerobak</td>
                    <td>:</td>
                    <td>{{ $data_gerobak->kode }}</td>
                </tr>
                <tr>
                    <td>Petugas</td>
                    <td>:</td>
                    <td>{{ $data_gerobak->employee->firstname . " " . $data_gerobak->employee->lastname }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>
                        @if ($data_trans != null && $data_trans->trans_status == "Pending")
                            Pending
                        @endif
                        @if ($data_trans != null && $data_trans->trans_status == "Approved")
                            Approved
                        @endif  
                    </td>
                </tr>
            </table>
          </div>
          <div class="clearboth"></div>
        </div>
      </div>
      
      <div class="clearboth"></div>
      
      <div class="col-12">
        <div class="table-responsive">
          <!--penjualan produk-->
          <table class="table table-bordered mb-0">
            <thead>
                <tr>
                  <th>No.</th>
                  <th width="120px">Produk</th>
                  <th>Harga</th>
                  <th>Out</th>
                  <th>Stok</th>
                  <th>Sisa</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_product as $pd)
                <tr>
                  <td style="text-align:center;">{{ $loop->iteration }}</td>
                  <td width="120px">{{ $pd->nama }}</td>
                  <td>
                      @if ($data_trans != null && $pd->trans->count() > 0)
                      {{ rupiah($pd->trans->product_harga) }}
                      @endif
                  </td>
                  <td style="text-align:center;">
                      @if ($data_trans != null  && $pd->trans->count() > 0)
                      {{ $pd->trans->product_sales }}
                      @php
                        $total_terjual = $total_terjual + $pd->trans->product_sales;
                      @endphp
                      @endif
                      
                  </td>
                  @php
                    if ($data_trans != null  && $pd->trans->count() > 0) {
                        $subtotal       = $pd->trans->product_sales * $pd->trans->product_harga;
                    }
                    $total_penjualan    += $subtotal;
                  @endphp
                  <td style="text-align:center;">
                      @if ($data_stock->count() > 0 && $pd->stock != null)
                      {{ $pd->stock->sum('stock') }}
                      @php
                        $total_terbawa = $total_terbawa + $pd->stock->sum('stock');
                      @endphp
                      @endif
                  </td>
                  @php
                      $total_penjualan_variant = 0;
                      if ($pd->variants->count() > 0) {
                          foreach ($pd->variants as $variant) {
                              if ($variant->trans->count() > 0) {
                              $total_penjualan_variant += $variant->trans->product_sales;
                              } else {
                              $total_penjualan_variant += 0;
                              }
                          }
                      }
                  @endphp
                  <td style="text-align:center;">
                      @if ($data_trans != null  && $pd->trans->count() > 0)
                      {{ $pd->stock->sum('stock') - $pd->trans->product_sales - $total_penjualan_variant }}
                      @else
                      {{ $pd->stock->sum('stock') }}
                      @endif
                  </td>
                </tr>
                <!--PRODUCT VARIANTS-->
                @if ($pd->variants->count() > 0)
                    @foreach ($pd->variants as $variant)
                        <tr>
                          <td width="120px" colspan="2"> > {{ $variant->nama }}</td>
                          <td>
                              @if ($data_trans != null  && $variant->trans->count() > 0)
                              {{ rupiah($variant->trans->product_harga) }}
                              @endif
                          </td>
                          <td style="text-align:center;">
                              @if ($data_trans != null  && $variant->trans->count() > 0)
                              {{ $variant->trans->product_sales }}
                              @php
                                $total_terjual = $total_terjual + $variant->trans->product_sales;
                              @endphp
                              @endif
                          </td>
                          @php
                            $subtotalVar = 0;
                            if ($data_trans != null  && $variant->trans->count() > 0) {
                                $subtotalVar       = $variant->trans->product_sales * $variant->trans->product_harga;
                            }
                            $total_penjualan    += $subtotalVar;
                          @endphp
                          <td style="text-align:center;">-</td>
                          <td style="text-align:center;">-</td>
                        </tr>
                    @endforeach
                @endif
                <!--PRODUCT VARIANTS-->
                @endforeach
            </tbody>
          </table>
          <!--penjualan produk-->
          
          <!--penjualan nominal-->
          <p class="m-0 mt-2 mb-1"><b>Total Aktual Penjualan</b></p>
          <table class="table table-bordered mb-0">
            <thead>
                <tr>
                  <th>Metode</th>
                  <th>Jumlah</th>
                </tr>
            </thead>
                <tr>
                  <td>Cash</td>
                  <td>
                      @if ($data_trans != null && $data_trans->transaction_nominal->count() > 0)
                      {{ pecahTanpaRp(optional($cashNominal)->transaction_amount ?? 0) }}
                      @endif
                  </td>
                </tr>
                <tr>
                  <td>Qris</td>
                  <td>
                      @if ($data_trans != null && $data_trans->transaction_nominal->count() > 0)
                      {{ pecahTanpaRp(optional($qrisNominal)->transaction_amount ?? 0) }}
                      @endif
                  </td>
                </tr>
                <tr>
                  <td><b>TOTAL</b></td>
                  <td>
                      <b>
                      @if ($data_trans != null && $data_trans->transaction_nominal->count() > 0)
                      @php
                      $total_nominal = optional($qrisNominal)->transaction_amount + optional($cashNominal)->transaction_amount ?? 0;
                      @endphp
                      {{ pecahTanpaRp($total_nominal) }}
                      @endif
                      </b>
                  </td>
                </tr>
                @if ($data_pengeluaran->count() > 0)
                <tr>
                  <td><b>Total Pengeluaran</b></td>
                  <td>
                      <b>
                      {{ pecahTanpaRp($data_pengeluaran->sum('pengeluaran_harga')) }}
                      <!--{{ pecahTanpaRp($data_pengeluaran->sum('pengeluaran_harga')) }}-->
                      </b>
                  </td>
                </tr>
                <!--<tr>-->
                <!--  <td><b>Total Setelah Pengeluaran</b></td>-->
                <!--  <td>-->
                <!--      <b>-->
                <!--      {{ pecahTanpaRp($total_nominal - $data_pengeluaran->sum('pengeluaran_harga')) }}-->
                <!--      </b>-->
                <!--  </td>-->
                <!--</tr>-->
                @endif
                <tr>
                  <td><b>Total By Sistem</b></td>
                  <td>
                      <b>
                      {{ pecahTanpaRp($total_penjualan) }}
                      
                      @if ($total_nominal > $total_penjualan)
                        (+ {{ pecahTanpaRp($total_nominal - $total_penjualan) }})
                      
                      @elseif ($total_nominal < $total_penjualan)
                        (- {{ pecahTanpaRp($total_penjualan - $total_nominal) }})
                     
                      @else
                      (Balanced)
                      @endif
                      </b>
                  </td>
                </tr>
            <!--<tbody>-->
            <!--</tbody>-->
          </table>
          <!--penjualan nominal-->
          
          @if ($data_pengeluaran->count() > 0)
          <!--pengeluaran produk-->
          <p class="m-0 mt-2 mb-1"><b>Pengeluaran</b></p>
          <table class="table table-bordered mb-0">
            <thead>
                <tr>
                  <th>No.</th>
                  <th>Nama Pengeluaran</th>
                  <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_pengeluaran as $keluar)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $keluar->pengeluaran_nama }}</td>
                    <td>{{ rupiah($keluar->pengeluaran_harga) }}</td>
                </tr>
                @endforeach
            </tbody>
          </table>
          @endif
          
          <!--keterangan-->
          @if ($data_trans != null && $data_trans->trans_keterangan != null)
          <p class="m-0 mt-2"><b>Keterangan :</b> {{ $data_trans->trans_keterangan }}</p>
          @endif
          <!--keterangan-->
          
          <!--summary-->
          <p class="m-0 mt-2 mb-1"><b>Summary</b></p>
          <table class="table table-bordered mb-0">
            <thead>
                <tr>
                  <th>No.</th>
                  <th>Keterangan</th>
                  <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align:center;">1.</td>
                    <td>Total Cup Terbawa</td>
                    <td style="text-align:center;">{{ $total_terbawa }}</td>
                </tr>
                <tr>
                    <td style="text-align:center;">2.</td>
                    <td>Total Cup Terjual</td>
                    <td style="text-align:center;">{{ $total_terjual }}</td>
                </tr>
                <tr>
                    <td style="text-align:center;">3.</td>
                    <td>Sisa Cup</td>
                    <td style="text-align:center;">{{ $total_terbawa - $total_terjual }}</td>
                </tr>
            </tbody>
          </table>
          <!--summary-->
          
          @if ($data_trans != null && $data_trans->trans_status == "Approved")
          <p class="m-0 mt-2"><b>Telah diapprove oleh petugas :</b> 
            @if ($data_trans->id_approval != null)
            {{ $data_trans->approval_by->firstname . " " . $data_trans->approval_by->lastname}}
            @else
            {{ "[Tidak ada ID Petugas Ditemukan / NULL]" }}
            @endif
          </p>
          @endif
          
          <br>
          <p class="m-0 mt-2"><b>Approval Management</b> 
          <br><br><br><br><br>
          <p class="m-0 mt-0">Rizky Rahmat H.</p>
          <p class="m-0 mt-0">Operations Manager</p>
        </div>
      </div>
    </div>
    <p>---------------------------------------------------------------------</p>
</body>

</html>
