@php
    $styleRH = 'background-color: navy;color: white;';
    $styleNA = 'background-color: yellow;color: black;';
    $styleH = 'background-color: white;color: black;';
    $styleX = 'background-color: pink;color: red;';
@endphp
<table>
    <thead>
        <tr>
            <th>Laporan Pendapatan Bulanan</th>
        </tr>
        <tr>
            <th>{{ strtoupper($data_outlet->nama) }} PERIODE {{ TanggalBulan($awal).' - '. TanggalBulan($akhir) }}</th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th><b>Pendapatan Gerobak</b></th>
        </tr>
        <tr>
            <th style='border:1px solid #000; {{ $styleRH }}'>NO.</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>GEROBAK</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>PRODUK TERJUAL</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>CASH</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>QRIS</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>TOTAL</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data_gerobak as $dt_gerobak)
        @php
            $total_produk_terjual   = 0;
            $total_trans_cash        = 0;
            $total_trans_qris       = 0;
            $total_all              = 0;
            $trans                  = $dt_gerobak->trans->where('trans_status', 'Approved');
            
            foreach ($trans as $t) {
                $trans_prod             = $t->transaction_produk;
                $produk_terjual         = $trans_prod->sum('product_sales');
                $total_produk_terjual   = $total_produk_terjual + $produk_terjual;
                $trans_nom              = $t->transaction_nominal;
                
                $cash_method            = $trans_nom->where('metode_bayar', 'Cash')->sum('transaction_amount');
                $qris_method            = $trans_nom->where('metode_bayar', 'Qris')->sum('transaction_amount');
                $total_trans_cash       = $total_trans_cash + $cash_method;
                $total_trans_qris       = $total_trans_qris + $qris_method;
                $total_all              += $trans_nom->whereIn('metode_bayar', ['Cash', 'Qris'])->sum('transaction_amount');
            }
        @endphp
        <tr>
            <td style='border:1px solid #000;'>{{ $loop->iteration }}</td>
            <td style='border:1px solid #000;'>{{ $dt_gerobak->nama }}</td>
            <td style='border:1px solid #000;'>{{ $total_produk_terjual }}</td>
            <td style='border:1px solid #000;'>{{ rupiah($total_trans_cash) }}</td>
            <td style='border:1px solid #000;'>{{ rupiah($total_trans_qris) }}</td>
            <td style='border:1px solid #000;'>{{ rupiah($total_all) }}</td>
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