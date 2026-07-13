<!doctype html>
<html lang="en">
    
@php
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Laporan</title>
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
            <h4>Laporan Assign Stock</h4>
            <h5>Semua Gerobak</h5>
          </div>
          <div class="col-sm-6 text-sm-end">
            <h6>Tanggal <span class="text-muted f-w-400">{{ tanggalIndo($tglNow) }}</span></h6>
          </div>
          <div class="clearboth"></div>
        </div>
      </div>
      
      <div class="clearboth"></div>
      
      <div class="col-12 mt-2">
        <div class="table-responsive">
          
          <!--stock produk-->
          <p class="m-0 mb-1"><b>Stock Produk Gerobak</b></p>
          <table class="table table-bordered mb-0">
            <thead>
                <tr>
                  <th>No.</th>
                  <th width="180px">Gerobak</th>
                  <th>Petugas</th>
                  <th>Total Stock</th>
                </tr>
            </thead>
            <tbody>
                @php $total_semua_cup_gerobak = 0; @endphp
                @foreach ($data_gerobak as $gb)
                    @php $total_cup = 0; @endphp
                    
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td width="180px">{{ $gb->kode }}</td>
                      <td>{{ $gb->employee->firstname }}</td>
                      @foreach ($gb->product as $pd)
                        @php 
                        $total_cup = $total_cup + $pd->stock->sum('stock'); 
                        @endphp
                      @endforeach
                      <td>{{ $total_cup }}</td>
                      @php
                        $total_semua_cup_gerobak = $total_semua_cup_gerobak + $total_cup;
                      @endphp
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2"><b>TOTAL SEMUA CUP:</b></td>
                    <td><b>{{ $total_semua_cup_gerobak }}</b></td>
                    <td></td>
                </tr>
            </tbody>
          </table>
          <!--stock produk-->
          <br>
          <h6>Laporan dibuat pada: <span class="text-muted f-w-400">{{ tanggalIndoWaktuLidgkap(date("Y-m-d H:i")) }}</span></h6>
        </div>
      </div>
      
    </div>
</body>

</html>
