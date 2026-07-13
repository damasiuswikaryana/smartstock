<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Laporan</title>
    <link rel="icon" href="{{ asset('img/logo/logo_sruuput.png') }}" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <style>
        @if ($printer == "iware_xs_80ul" || $printer == "iware_xs_80bt")
            /*setingan untuk printer iware xs80ul*/
            @page { margin: 0 !important; }
            body { margin: 0 !important; padding: 1mm !important; width: 70mm; font-size: 10px; }
            h4 { font-size: 12px !important; margin: .5px; }
            h5 { font-size: 10px !important; margin: 0px; }
            h6 { font-size: 10px !important; margin: 0px; }
            p { font-size: 10px !important; padding: 0px; margin-top: 0px; margin-bottom: 5px; }
        @else
            /*setingan untuk printer thermal universal*/
            @page { margin: 0 !important; }
            body { margin: 0 !important; padding: 1mm !important; width: 60mm; font-size: 7px; }
            h4 { font-size: 10px !important; margin: .5px; }
            h5 { font-size: 8px !important; margin: 0px; }
            h6 { font-size: 7.5px !important; margin: 0px; }
            p { font-size: 8px !important; padding: 0px; margin-top: 0px; margin-bottom: 5px; }
        @endif
        
        * { box-sizing: border-box; }
        body, h2, h3, h4, h5, h6, p { font-family: 'Public Sans', sans-serif; }
        h2 { font-size: 7px; }
        h3 { font-size: 6.5px; }
        
        .row { display: flex; padding: 0px; margin: auto; flex-wrap: wrap; }
        .g-3 { --bs-gutter-x: .5rem; --bs-gutter-y: .5rem; }
        .col-sm-6, .col-6 { width: 50%; float: left; }
        .col-sm-12, .col-12 { width: 100%; float: none; }
        .clearboth { float: none; clear: both; }
        .text-sm-end { text-align: right; }
        .mb-2 { margin-bottom: 2mm !important; }
        .text-muted { opacity: 0.8; }
        .border { border: 1px solid #000; }
        .col { flex: 1 0 0%; }
        .col-auto { flex: 0 0 auto; width: auto; }
        .page-break { page-break-after: always; }
        .fw-bold { font-weight: bold; }
        .text-success { color: rgb(29, 233, 182); }
        .text-danger { color: rgb(244, 66, 54); }
        table, tr, td { vertical-align: baseline; }
        .table-bordered, .table-bordered th, .table-bordered td { border: 1px solid #000; }
        .table { width: 100%; color: #000; background-color: transparent; border-collapse: collapse; border: 0px; }
        .table th, .table td { padding: 2px; vertical-align: baseline; }
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
            <h4 style="text-align:center">Laporan Assign Stock</h4>
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
            </table>
          </div>
          <div class="clearboth"></div>
        </div>
      </div>
      <div class="clearboth"></div>
      <div class="col-12 mb-2">
        <div class="table-responsive">
          <!--stock produk-->
          <table class="table table-bordered mb-0">
            <thead>
                <tr>
                  <th>No</th>
                  <th>Produk</th>
                  <th>Qty</th>
                  <th>Tipe</th>
                  <th>Assign</th>
                </tr>
            </thead>
            <tbody>
                @php $total_cup = 0; @endphp
                @foreach ($data_product as $pd)
                    @php $total_cup = $total_cup + $pd->stock->sum('stock'); @endphp
                    @if ($pd->stock->count() <= 1)
                        <tr>
                          <td style="text-align:center; vertical-align:middle;">{{ $loop->iteration }}</td>
                          <td style="vertical-align:middle;">{{ $pd->nama }}</td>
                          @foreach($pd->stock as $s)
                          <td style="text-align:center; vertical-align:middle;">{{ $pd->stock->sum('stock') }}</td>
                          <td style="text-align:center; vertical-align:middle;">{{ $s->stock_tipe == 'Penambahan' ? '+Addon' : 'Awal' }}</td>
                          <td>{{ Illuminate\Support\Str::limit($s->admin->firstname, 13) }}</td>
                          @endforeach
                        </tr>
                    @else
                        @php $first = $pd->stock->first(); @endphp
                        <tr>
                            <td style="text-align:center; vertical-align:middle;" rowspan="{{ $pd->stock->count() }}">{{ $loop->iteration }}</td>
                            <td style="vertical-align:middle;" rowspan="{{ $pd->stock->count() }}">{{ $pd->nama }}</td>
                            <td style="text-align:center; vertical-align:middle;">{{ $first->stock }}</td>
                            <td style="text-align:center; vertical-align:middle;">{{ $first->stock_tipe == 'Penambahan' ? '+Addon' : 'Awal' }}</td>
                            <td>{{ Illuminate\Support\Str::limit($first->admin->firstname, 13) }}</td>
                        </tr>
                        @foreach ($pd->stock->skip(1) as $s)
                        <tr>
                            <td style="text-align:center; vertical-align:middle;">{{ $s->stock }}</td>
                            <td style="text-align:center; vertical-align:middle;">{{ $s->stock_tipe == 'Penambahan' ? '+Addon' : 'Awal' }}</td>
                            <td>{{ Illuminate\Support\Str::limit($s->admin->firstname, 13) }}</td>
                        </tr>
                        @endforeach
                    @endif
                @endforeach
                <tr>
                    <td colspan="2"><b>TOTAL CUP DIBAWA:</b></td>
                    <td><b>{{ $total_cup }}</b></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
          </table>
          <!--stock produk-->
          <br>
          <h6>Laporan dibuat pada: <span class="f-w-400">{{ tanggalIndoWaktuLidgkap(date("Y-m-d H:i")) }}</span></h6>
          
        </div>
      </div>
    </div>
    <p>---------------------------------------------------------------------</p>
</body>

</html>
