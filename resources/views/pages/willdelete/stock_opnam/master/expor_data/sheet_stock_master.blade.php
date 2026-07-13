@php
    $styleRH = 'background-color: navy;color: white;';
    $styleNA = 'background-color: yellow;color: black;';
    $styleH = 'background-color: white;color: black;';
    $styleX = 'background-color: pink;color: red;';
@endphp
<table>
    <thead>
        <tr>
            <th><b>Stock Master</b></th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>NO.</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>ITEM</th>
            <th style='border:1px solid #000; {{ $styleRH }} text-align:center;'>JUMLAH DAN SATUAN</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
            <td style='border:1px solid #000; text-align:center;'>{{ $loop->iteration }}</td>
            <td style='border:1px solid #000;'>{{ $row->nama }}</td>
            <td style='border:1px solid #000;'>
                @php
                    $isi = '';
                    if ($row->stock_master->isEmpty()) {
                        $isi = "0 stock";
                    }
                
                    $grouped = $row->stock_master->groupBy(function ($item) {
                        return $item->satuan->satuan;
                    });
                    
                    foreach ($grouped as $satuan => $items) {
                        $total = $items->sum('item_jumlah');
                        $isi .= "{$total} {$satuan} , ";
                    }
                @endphp
                {{ $isi }}
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