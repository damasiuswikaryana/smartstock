@php
    $styleRH = 'background-color: navy;color: white;';
    $styleNA = 'background-color: yellow;color: black;';
    $styleH = 'background-color: white;color: black;';
    $styleX = 'background-color: pink;color: red;';
@endphp
<table>
    <thead>
        <tr>
            <th><b>Stock Opnam Harian - {{ $outlet->nama }} - {{ $tanggal }}</b></th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>NO.</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>TANGGAL</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>ITEM</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>JUMLAH DAN SATUAN</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>TERPAKAI</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>PETUGAS</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
            <td style='border:1px solid #000; text-align:center;'>{{ $loop->iteration }}</td>
            <td style='border:1px solid #000;'>
                @if ($row->opnam != null) {{ tanggalIndoWaktuLidgkap($row->opnam->created_at) }}
                @else {{ "" }}
                @endif
            </td>
            <td style='border:1px solid #000;'>
                {{ $row->nama }}
            </td>
            <td style='border:1px solid #000;'>
                @php
                    $isi = "";
                    if ($row->opnam != null) {
                        $isi .= $row->opnam->item_jumlah . " " . $row->opnam->satuan->satuan;
                        if ($row->opnam->item_jumlah_2 != null) {
                            $isi .= ", ". $row->opnam->item_jumlah_2 . " " . $row->opnam->satuan_lanjut->satuan;
                        }
                    }
                @endphp
                {{ $isi }}
            </td>
            <td style='border:1px solid #000;'>
                @if ($row->opnam != null) {{ $row->opnam->item_terpakai }}
                @else {{ "" }}
                @endif
            </td>
            <td style='border:1px solid #000;'>
                @if ($row->opnam != null) {{ $row->opnam->user->firstname . " " . $row->opnam->user->lastname }}
                @else {{ "" }}
                @endif
            </td>
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