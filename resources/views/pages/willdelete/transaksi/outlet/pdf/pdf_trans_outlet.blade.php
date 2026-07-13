<!doctype html>
<html lang="en">
    
@php
    $total_penjualan    = 0;
    $total_terbawa      = 0;
    $total_terjual      = 0;
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Laporan</title>
    <link rel="icon" href="{{ asset('logo_sruuput.png') }}" type="image/x-icon" />
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
        .mt-4 { margin-top: 30px; }
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
            <h4>Ringkasan Transaksi Harian</h4>
            <h5>{{ $data_shift->outlet->nama }}</h5>
          </div>
          <div class="col-sm-6 text-sm-end">
            <h6>Tanggal <span class="text-muted f-w-400">{{ tanggalIndo($tglNow) }}</span></h6>
            <h6>Petugas <span class="text-muted f-w-400">{{ $data_shift->user->firstname . " " . $data_shift->user->lastname }}</span></h6>
          </div>
          <div class="clearboth"></div>
        </div>
      </div>
      
      <div class="clearboth"></div>
      
      <div class="col-12 mt-2">
        <div class="table-responsive">
          
          <!--penjualan produk-->
          <p class="m-0 mb-1"><b>Penjualan Produk Shift {{ $data_shift->shift_no }} 
          ({{ TampilJamMidit($data_shift->shift_in) }} - {{ $data_shift->shift_out != null ? TampilJamMidit($data_shift->shift_out) : 'x' }})</b></p>
          <table class="table table-bordered mb-0">
            <thead>
                <tr>
                  <th>No.</th>
                  <th width="180px">Produk</th>
                  <th>Total Terjual</th>
                  <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @php
                $total = 0;
                @endphp
                @foreach ($data_trans as $pd)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td width="180px">{{ $pd->product->nama }}</td>
                  <td>
                      {{ $pd->total_terjual }}
                  </td>
                  <td>
                      {{ rupiah($pd->total_harga) }}
                  </td>
                </tr>
                @php
                $total = $total + $pd->total_harga;
                @endphp
                @endforeach
                <tr>
                    <td colspan="3"><b>Total Pemasukan</b></td>
                    <td><b>{{ rupiah($total) }}</b></td>
                </tr>
                <tr>
                    <td colspan="3"><b>Total Cash</b></td>
                    <td><b>{{ rupiah($total_cash) }}</b></td>
                </tr>
                <tr>
                    <td colspan="3"><b>Total Qris</b></td>
                    <td><b>{{ rupiah($total_qris) }}</b></td>
                </tr>
                @if ($dataShiftSebelumnya != null)
                @php
                    $totalSebelumnya = 0;
                    foreach ($transaksiShift1 as $trans1) {
                        $totalSebelumnya = $totalSebelumnya + $trans1->total_harga;
                    }
                @endphp
                <!--<tr>-->
                <!--    <td colspan="4"><b>Shift Sebelumnya</b></td>-->
                <!--</tr>-->
                <!--<tr>-->
                <!--    <td colspan="3">Total Pendapatan</td>-->
                <!--    <td><b>{{ rupiah($totalSebelumnya) }}</b></td>-->
                <!--</tr>-->
                <!--<tr>-->
                <!--    <td colspan="3">Cash</td>-->
                <!--    <td><b>{{ rupiah($total_cashShift1) }}</b></td>-->
                <!--</tr>-->
                <!--<tr>-->
                <!--    <td colspan="3">Qris</td>-->
                <!--    <td><b>{{ rupiah($total_qrisShift1) }}</b></td>-->
                <!--</tr>-->
                @endif
            </tbody>
          </table>
          <!--penjualan produk-->
          
          <!--penjualan cake-->
          <p class="m-0 mb-1" style="margin-top: 30px;"><b>Penjualan Cake</b></p>
          <table class="table table-bordered mb-0">
            <thead>
                <tr>
                  <th>No.</th>
                  <th width="180px">Produk</th>
                  <th>Total Terjual</th>
                  <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @php
                $total_cake = 0;
                @endphp
                @foreach ($transaksi_cake as $pd)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td width="180px">{{ $pd->product->nama }}</td>
                  <td>
                      {{ $pd->total_terjual }}
                  </td>
                  <td>
                      {{ rupiah($pd->total_harga) }}
                  </td>
                </tr>
                @php
                $total_cake = $total_cake + $pd->total_harga;
                @endphp
                @endforeach
                <tr>
                    <td colspan="3"><b>Total Pemasukan</b></td>
                    <td><b>{{ rupiah($total_cake) }}</b></td>
                </tr>
                <tr>
                    <td colspan="3"><b>Total Cash</b></td>
                    <td><b>{{ rupiah($total_cash_cake) }}</b></td>
                </tr>
                <tr>
                    <td colspan="3"><b>Total Qris</b></td>
                    <td><b>{{ rupiah($total_qris_cake) }}</b></td>
                </tr>
            </tbody>
          </table>
          <!--penjualan cake-->
        
        <!--transaksi gabungan-->
        @if ($data_shift->shift_no == 2 && isset($transaksiGabungan))
        <p class="m-0 mt-4 mb-1"><b>Total Penjualan Shift 1 + Shift 2</b></p>
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th width="180px">Produk</th>
                    <th>Total Terjual</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; $totalGabungan = 0; @endphp
                @foreach ($transaksiGabungan as $pd)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $pd->product->nama ?? '-' }}</td>
                        <td>{{ $pd->total_terjual }}</td>
                        <td>{{ rupiah($pd->total_harga) }}</td>
                    </tr>
                @php
                $totalGabungan = $totalGabungan + $pd->total_harga;
                @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;">GRAND TOTAL CASH</td>
                    <td><b>{{ rupiah($total_cash_all) }}</b></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">GRAND TOTAL QRIS</td>
                    <td><b>{{ rupiah($total_qris_all) }}</b></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">GRAND TOTAL PENJUALAN</td>
                    <td><b>{{ rupiah($totalGabungan) }}</b></td>
                </tr>
            </tfoot>
        </table>
        @endif
          
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
        </div>
      </div>
      
    </div>
</body>

</html>
