@php
    $styleRH = 'background-color: navy;color: white;';
    $styleNA = 'background-color: yellow;color: black;';
    $styleH = 'background-color: white;color: black;';
    $styleX = 'background-color: pink;color: red;';
@endphp
<table>
    <thead>
        <tr>
            <th>Laporan Pendapatan Harian</th>
        </tr>
        <tr>
            <th>{{ strtoupper($data_outlet->nama) }} Tanggal {{ tanggalIndo($tanggal) }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data_outlet->gerobak as $gerobak)
        <tr>
            <th></th>
        </tr>
        <tr>
            <th><b>Pendapatan {{ $gerobak->kode }}</b></th>
        </tr>
        <tr>
            <th><b>Petugas Gerobak: {{ $gerobak->employee->firstname }} {{ $gerobak->employee->lastname }}</b></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th><b>
                @if ($gerobak->trans_master != null && $gerobak->trans_master->trans_status == 'Approved')
                Approved
                @elseif ($gerobak->trans_master != null && $gerobak->trans_master->trans_status == 'Pending')
                Sedang Ditinjau
                @else
                @endif
            </b></th>
        </tr>
        <tr>
            <th style='border:1px solid #000; text-align:center; {{ $styleRH }}'>NO.</th>
            <th style='border:1px solid #000; text-align:center; {{ $styleRH }}'>PRODUK</th>
            <th style='border:1px solid #000; text-align:center; {{ $styleRH }}'>STOCK</th>
            <th style='border:1px solid #000; text-align:center; {{ $styleRH }}'>PENJUALAN QTY</th>
            <th style='border:1px solid #000; text-align:center; {{ $styleRH }}'>PENJUALAN NOMINAL</th>
            <th style='border:1px solid #000; text-align:center; {{ $styleRH }}'>SISA</th>
        </tr>
        @if ($gerobak->trans->count() > 0) 
            @php $total_penjualan = 0; @endphp
            @foreach ($gerobak->trans as $trans)
            @php 
                if ($trans->row_type == 'varian') {
                    $subtotal        = $trans->qty * $trans->product_harga; 
                } else {
                    $subtotal        = $trans->product_sales * $trans->product_harga; 
                } 
                $total_penjualan     = $total_penjualan + $subtotal;
            @endphp
            <tr>
                <th style='border:1px solid #000; text-align:center;'>{{ $loop->iteration }}</th>
                <th style='border:1px solid #000;'>
                    @if($trans->row_type == 'varian') 
                        |-> {{ $trans->product->nama }}
                    @else
                        {{ $trans->product->nama }}
                    @endif
                </th>
                <th style='border:1px solid #000; text-align:center;'>
                    @if($trans->row_type == 'varian') 
                    @else
                        {{ $gerobak->stock[$trans->product_id]->sum('stock') ?? 0 }}
                    @endif
                </th>
                <th style='border:1px solid #000; text-align:center;'>
                    @if($trans->row_type == 'varian') 
                        {{ $trans->qty }}
                    @else
                        {{ $trans->product_sales }}
                    @endif
                </th>
                <th style='border:1px solid #000;'>{{ rupiah($subtotal) }}</th>
                <th style='border:1px solid #000; text-align:center;'>
                    @if($trans->row_type == 'varian') 
                    @else
                        {{ $gerobak->stock[$trans->product_id]->sum('stock') - $trans->product_sales }}
                    @endif
                </th>
            </tr>
            @endforeach
        @endif
        
        @if ($gerobak->trans_nominal->count() > 0)
            @php $total_nominal = 0; @endphp
            @foreach ($gerobak->trans_nominal as $trans_nominal)
            @php 
              $total_nominal = $total_nominal + $trans_nominal->transaction_amount;
            @endphp
            <tr>
                <th style='border:1px solid #000; {{ $styleRH }}' colspan="2">PEMASUKAN {{ $trans_nominal->metode_bayar }}</th>
                <th style='border:1px solid #000; text-align: right;' colspan="4">{{ rupiah($trans_nominal->transaction_amount) }}</th>
            </tr>
            @endforeach
        @endif
        
        @if ($gerobak->trans->count() > 0 && $gerobak->trans_nominal->count() > 0)
            <tr>
                <th style='border:1px solid #000; {{ $styleRH }}' colspan="2">TOTAL PENJUALAN</th>
                <th style='border:1px solid #000; text-align: right;' colspan="4">
                    {{ rupiah($total_penjualan) }}
                </th>
            </tr>
            <tr>
                <th style='border:1px solid #000; {{ $styleRH }}' colspan="2">KETERANGAN</th>
                <th style='border:1px solid #000; text-align: right;' colspan="4">
                    @if ($total_nominal == $total_penjualan)
                    Balanced
                    @elseif ($total_nominal > $total_penjualan)
                    + {{ rupiah($total_nominal - $total_penjualan) }}
                    @else
                    - {{ rupiah($total_penjualan - $total_nominal) }}
                    @endif
                </th>
            </tr>
        @endif
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