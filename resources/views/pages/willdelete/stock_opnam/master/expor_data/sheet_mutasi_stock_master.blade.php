@php
    $styleRH = 'background-color: navy;color: white;';
    $styleNA = 'background-color: yellow;color: black;';
    $styleH = 'background-color: white;color: black;';
    $styleX = 'background-color: pink;color: red;';
@endphp
<table>
    <thead>
        <tr>
            <th>Mutasi Stock Master</th>
        </tr>
        <tr>
            <th>Periode {{ TanggalBulan($awal).' - '. TanggalBulan($akhir) }}</th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th><b>Mutasi Stock Master</b></th>
        </tr>
        <tr>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>NO.</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>TANGGAL</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>JENIS</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>ITEM</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>JUMLAH</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>SATUAN</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>KETERANGAN</th>
        </tr>
    </thead>
    <tbody>
        @foreach($riwayatMaster as $row)
            @php
                if ($row->source_type == 'Master') { $styleRow = $styleX; }
                elseif ($row->target_type == 'Master') { $styleRow = ""; }
            @endphp
        <tr>
            <td style='border:1px solid #000; {{ $styleRow }} text-align:center;'>{{ $loop->iteration }}</td>
            <td style='border:1px solid #000; {{ $styleRow }}'>{{ tanggalIndoWaktuLidgkap($row->created_at) }}</td>
            <td style='border:1px solid #000; {{ $styleRow }} text-align:center;'>
                @if ($row->source_type == 'Master')
                    Keluar
                @elseif ($row->target_type == 'Master')
                    Masuk
                @endif
            </td>
            <td style='border:1px solid #000; {{ $styleRow }}'>{{ $row->item->nama }}</td>
            <td style='border:1px solid #000; {{ $styleRow }} text-align:center;'>{{ $row->jumlah }}</td>
            <td style='border:1px solid #000; {{ $styleRow }} text-align:center;'>{{ $row->satuan->satuan }}</td>
            <td style='border:1px solid #000; {{ $styleRow }}'>{{ $row->keterangan }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


@php
    $summary = [];
    
    foreach ($riwayatMaster as $row) {
        $item   = $row->item->nama;
        $satuan = $row->satuan->satuan;
        $key    = $item . '|' . $satuan;

        // Siapkan data awal jika belum ada
        if (!isset($summary[$key])) {
            $summary[$key] = [
                'item'              => $item,
                'satuan'            => $satuan,
                'masuk'             => 0,
                'keluar'            => 0,
                'outlet_gadung'     => 0,
                'outlet_sesetan'    => 0,
            ];
        }

        if ($row->source_type == 'Master') {
            $summary[$key]['keluar'] += $row->jumlah;
            
            if (stripos($row->keterangan, 'Outlet Gadung') !== false) {
                $summary[$key]['outlet_gadung'] += $row->jumlah;
            } elseif (stripos($row->keterangan, 'Outlet Sesetan') !== false) {
                $summary[$key]['outlet_sesetan'] += $row->jumlah;
            }
        } elseif ($row->target_type == 'Master') {
            $summary[$key]['masuk'] += $row->jumlah;
        }
    }
    
    usort($summary, function ($a, $b) {
        return strcmp($a['item'], $b['item']);
    });
@endphp

<table>
    <thead>
        <tr>
            <th><b>Ringkasan Mutasi Stock Master</b></th>
        </tr>
        <tr>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>NO.</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>ITEM</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>SATUAN</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>TOTAL MASUK</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>TOTAL KELUAR</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>OUTLET GADUNG</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>OUTLET SESETAN</th>
        </tr>
    </thead>
    <tbody>
        @foreach($summary as $index => $data)
        <tr>
            <td style="border:1px solid #000; text-align:center;">{{ $loop->iteration }}</td>
            <td style="border:1px solid #000; text-align:center;">{{ $data['item'] }}</td>
            <td style="border:1px solid #000; text-align:center;">{{ $data['satuan'] }}</td>
            <td style="border:1px solid #000; text-align:center;">{{ $data['masuk'] }}</td>
            <td style="border:1px solid #000; text-align:center;">{{ $data['keluar'] }}</td>
            <td style="border:1px solid #000; text-align:center;">{{ $data['outlet_gadung'] }}</td>
            <td style="border:1px solid #000; text-align:center;">{{ $data['outlet_sesetan'] }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th></th>
            <th colspan="5">Diekspor melalui Management App pada : {{ Carbon\Carbon::now()->locale('id')->isoFormat('dddd, LL HH:mm'); }}</th>
        </tr>
    </tfoot>
</table>