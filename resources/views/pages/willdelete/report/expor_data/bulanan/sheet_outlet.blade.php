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
            <th><b>Pendapatan Outlet</b></th>
        </tr>
        <tr>
            <th style='border:1px solid #000; {{ $styleRH }}'>NO.</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>CUSTOMER</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>TOTAL</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>METODE</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>BAYAR</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>KEMBALI</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>DIBUAT</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data_trans_outlet as $dt_out)
        @php
            $produk = $dt_out->transaction_outlet;
            $total  = 0;
            foreach ($produk as $p) {
                $subtotal   = $p->qty * $p->product_harga;
                $total      = $total + $subtotal;
            }
        @endphp
        <tr>
            <td style='border:1px solid #000;'>{{ $loop->iteration }}</td>
            <td style='border:1px solid #000;'>{{ $dt_out->trans_outlet_nama }}</td>
            <td style='border:1px solid #000;'>{{ rupiah($total) }}</td>
            <td style='border:1px solid #000;'>{{ $dt_out->trans_outlet_metode }}</td>
            <td style='border:1px solid #000;'>{{ rupiah($dt_out->trans_outlet_bayar) }}</td>
            <td style='border:1px solid #000;'>{{ rupiah($dt_out->trans_outlet_kembali) }}</td>
            <td style='border:1px solid #000;'>{{ tanggalIndoWaktuLidgkap($dt_out->updated_at) }}</td>
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