@php
    $styleRH = 'background-color: navy;color: white;';
    $styleNA = 'background-color: yellow;color: black;';
    $styleH = 'background-color: white;color: black;';
    $styleX = 'background-color: pink;color: red;';
@endphp
<table>
    <thead>
        <tr>
            <th>Mutasi Stock {{ $outlet->nama }}</th>
        </tr>
        <tr>
            <th>Periode {{ TanggalBulan($awal).' - '. TanggalBulan($akhir) }}</th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th><b>Mutasi Stock {{ $outlet->nama }}</b></th>
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
        @foreach($riwayatOutlet as $row)
            @php
                if ($row->source_type == 'Outlet') { $styleRow = $styleX; }
                elseif ($row->target_type == 'Outlet') { $styleRow = ""; }
            @endphp
        <tr>
            <td style='border:1px solid #000; {{ $styleRow }} text-align:center;'>{{ $loop->iteration }}</td>
            <td style='border:1px solid #000; {{ $styleRow }}'>{{ tanggalIndoWaktuLidgkap($row->created_at) }}</td>
            <td style='border:1px solid #000; {{ $styleRow }} text-align:center;'>
                @if ($row->source_type == 'Outlet')
                    Keluar
                @elseif ($row->target_type == 'Outlet')
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