<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk Pembelian - {{ $data->id }}</title>
    <style>
        body {
            font-family: monospace;
            width: 58mm;
            margin: 0;
            padding: 10px;
            font-size: 12px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .line { border-top: 1px dashed #000; margin: 5px 0; }
        @media print {
            @page { margin: 0 !important; }
            body { margin: 0 !important; padding: 1mm !important; width: 70mm; font-size: 12px; }
        }
    </style>
</head>
<body onload="window.print(); window.onafterprint = function() { window.close(); }">
    @php $total = 0; @endphp
    <div class="text-center">
        <strong>SRUUPUT KOPI - {{ $data->outlet->nama }}</strong><br>
        {{ $data->outlet->alamat }}<br>
    </div>
    <div class="line"></div>

    <p>No: {{ $data->id }}<br>
    Customer: {{ $data->trans_outlet_nama }}<br>
    Tanggal: {{ $data->created_at->format('d/m/Y H:i') }}<br>
    
    @if ($data->shift_id != null)
        Petugas: {{ $data->shift->user->firstname }}
    @endif
    </p>

    <table width="100%">
        @foreach ($data->transaction_outlet as $item)
            <tr>
                <td colspan="2">{{ $item->product->nama }}</td>
            </tr>
            @php
                $subtotal = $item->qty * $item->product_harga;
                $total    = $total + $subtotal;
            @endphp
            <tr>
                <td>{{ $item->qty }} x {{ rupiah($item->product_harga) }}</td>
                <td class="text-right">{{ rupiah($subtotal) }}</td>
            </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table width="100%">
        <tr>
            <td><strong>Total</strong></td>
            <td class="text-right"><strong>{{ rupiah($total) }}</strong></td>
        </tr>
        <tr>
            <td>Metode Bayar</td>
            <td class="text-right">{{ $data->trans_outlet_metode }}</td>
        </tr>
        @if ($data->trans_outlet_metode == "Cash")
        <tr>
            <td>Bayar</td>
            <td class="text-right">{{ rupiah($data->trans_outlet_bayar) }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="text-right">{{ rupiah($data->trans_outlet_kembali) }}</td>
        </tr>
        @else
        <tr>
            <td>Bayar</td>
            <td class="text-right">{{ rupiah($data->trans_outlet_bayar) }}</td>
        </tr>
        @endif
    </table>
    
    @if ($data->trans_keterangan != null)
    <table width="100%">
        <tr>
            <td><strong>Keterangan</strong></td>
            <td class="text-right"><strong>{{ $data->trans_keterangan }}</strong></td>
        </tr>
    </table>
    @endif

    <div class="text-center" style="margin-top: 10px;">
        ~ Terima Kasih ~
    </div>
</body>
</html>