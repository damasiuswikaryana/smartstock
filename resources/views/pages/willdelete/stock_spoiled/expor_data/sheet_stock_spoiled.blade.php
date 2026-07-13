@php
    $styleRH = 'background-color: navy;color: white;';
    $styleNA = 'background-color: yellow;color: black;';
    $styleH = 'background-color: white;color: black;';
    $styleX = 'background-color: pink;color: red;';
@endphp
<table>
    <thead>
        <tr>
            <th>Stock Spoiled</th>
        </tr>
        <tr>
            <th>Periode {{ TanggalBulan($awal).' - '. TanggalBulan($akhir) }}</th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th><b>Data Stock Spoiled</b></th>
        </tr>
        <tr>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>NO.</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>TANGGAL</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>SOURCE</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>ITEM / PRODUK</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>JUMLAH</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>SATUAN</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>KETERANGAN</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
            <td style='border:1px solid #000; {{ $styleRow }} text-align:center;'>{{ $loop->iteration }}</td>
            <td style='border:1px solid #000; {{ $styleRow }}'>{{ tanggalIndoWaktuLidgkap($row->created_at) }}</td>
            <td style='border:1px solid #000; {{ $styleRow }} text-align:center;'>
                @if ($row->source_type == "Outlet")
                    {{ namaOutlet($row->source_id) }}
                @elseif ($row->source_type == "Gerobak") {
                    {{ namaGerobak($row->source_id) }}
                @else
                    {{ "-" }}
                @endif
            </td>
            <td style='border:1px solid #000; {{ $styleRow }}'>
                @if ($row->item_id != null)
                    {{ $row->item->nama }}
                @else
                    {{ $row->product->nama }}
                @endif
            </td>
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