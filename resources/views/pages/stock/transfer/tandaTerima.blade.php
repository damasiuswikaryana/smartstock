<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tanda Terima</title>
    <link rel="icon" href="" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <style>
        @page {
            margin: 0 !important;
        }

        body {
            margin: 0 !important;
            padding: 2em !important;
            font-size: 14px;
        }

        * {
            box-sizing: border-box;
        }

        body,
        h2,
        h3,
        h4,
        h5,
        h6,
        p {
            font-family: 'Public Sans', sans-serif;
        }

        .row {
            display: flex;
            padding: 0px;
            margin: auto;
            flex-wrap: wrap;
        }

        .g-3 {
            --bs-gutter-x: .5rem;
            --bs-gutter-y: .5rem;
        }

        .col-sm-6,
        .col-6 {
            width: 50%;
            float: left;
        }

        .col-sm-3,
        .col-3 {
            width: 25%;
            float: left;
        }

        .col-sm-4,
        .col-4 {
            width: 33.3%;
            float: left;
        }

        .col-sm-8,
        .col-8 {
            width: 66.6%;
            float: left;
        }

        .col-sm-12,
        .col-12 {
            width: 100%;
            float: none;
        }

        .clearboth {
            float: none;
            clear: both;
        }

        .text-sm-end {
            text-align: right;
        }

        .mb-2 {
            margin-bottom: 2mm !important;
        }

        .text-muted {
            opacity: 0.8;
        }

        .border {
            border: 1px solid #000;
        }

        .col {
            flex: 1 0 0%;
        }

        .col-auto {
            flex: 0 0 auto;
            width: auto;
        }

        .page-break {
            page-break-after: always;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-success {
            color: rgb(29, 233, 182);
        }

        .text-center {
            text-align: center;
        }

        .text-danger {
            color: rgb(244, 66, 54);
        }

        table,
        tr,
        td {
            vertical-align: baseline;
        }

        .table-bordered,
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000;
        }

        .table {
            width: 100%;
            color: #000;
            background-color: transparent;
            border-collapse: collapse;
            border: 0px;
        }

        .table th,
        .table td {
            padding: 2px 5px;
            vertical-align: baseline;
        }

        .tb_head {
            background-color: #333 !important;
            color: #fff !important;
        }

        .no-border,
        .no-border tr,
        .no-border tr td,
        {
        border: 0px !important;
        }

        .float-left {
            float: left;
        }

        .float-right {
            float: right;
        }

        .clear-both {
            clear: both;
        }
    </style>
</head>

<body>
    <table class="table table-bordered">
        {{-- header --}}
        <tr>
            <td colspan="6" style="padding:10px;">
                <div class="row">
                    <div class="col-12" style="">
                        <h2 class="text-center" style="margin:5px 0px 0px 0px; padding:0px;">
                            TANDA TERIMA BARANG GUDANG
                        </h2>
                    </div>
                </div>
            </td>
        </tr>
        {{-- header --}}
        {{-- company sender and destination --}}
        <tr>
            <td colspan="2" style="padding:10px;">
                <table class="table no-border">
                    <tr>
                        <td width="40%">No. Tanda Terima</td>
                        <td width="2%">:</td>
                        <td>{{ $data->stock_transfer_number }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{{ tanggalIndo3($data->transfer_date) }}</td>
                    </tr>
                    <tr>
                        <td>Target</td>
                        <td>:</td>
                        <td>{{ $data->gudangAsal->nama }}</td>
                    </tr>
                </table>
            </td>
            <td colspan="4" style="padding:10px;">
                <table class="table no-border">
                    <tr>
                        <td width="40%">No SRF</td>
                        <td width="2%">:</td>
                        <td>{{ $data->transfer_srf }}</td>
                    </tr>
                    <tr>
                        <td>Project</td>
                        <td>:</td>
                        <td>{{ $data->pekerjaan->name }}</td>
                    </tr>
                    <tr>
                        <td>Destination</td>
                        <td>:</td>
                        <td>{{ $data->gudangTarget->nama }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        {{-- items --}}
        <tr>
            <th width="20%">CODE</th>
            <th width="37%">ITEM(S) NAME</th>
            <th width="8%">SRF</th>
            <th width="8%">OUT</th>
            <th width="5%">UNIT</th>
            <th width="25%">KETERANGAN</th>
        </tr>
        @foreach ($data->child as $index => $item)
            <tr>
                <td class="text-center">{{ $item->varian->sku_varian }}</td>
                <td>{{ $item->varian->name_varian }}</td>
                <td class="text-center">{{ $item->qty }}</td>
                <td class="text-center">{{ $item->qty }}</td>
                <td class="text-center">{{ $item->satuan_id != null ? $item->satuan->satuan : '-' }}</td>
                <td class="text-center"></td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6" style="padding:10px;">
                <span>Notes :</span><br><br>
                {{ $data->note }}
            </td>
        </tr>
        <tr>
            <td colspan="6" style="height:180px; padding:15px;">
                <div class="row">
                    <div class="col-3 text-center">
                        <b>Dikeluarkan Oleh</b>
                        <br><br>
                        <br><br>
                        <br><br>
                        {{ '(' . $data->createdBy->firstname . ' ' . $data->createdBy->lastname . ')' }}
                    </div>
                    <div class="col-3 text-center">
                        <b>Diterima Oleh</b>
                        <br><br>
                        <br><br>
                        <br><br>
                        {{ '(' . $data->received_by . ')' }}
                    </div>
                    <div class="col-3 text-center">
                        <b>Diketahui Oleh</b>
                        <br><br>
                        <br><br>
                        <br><br>
                        {{ '(I Komang Sudarna)' }}
                    </div>
                    <div class="col-3 text-center">
                        @php
                            $pengadaan = app\Models\User::role('pengadaan')->first();
                        @endphp
                        <b>Diketahui Oleh</b>
                        <br><br>
                        <br><br>
                        <br><br>
                        {{ '(' . $pengadaan->firstname . ' ' . $pengadaan->lastname . ')' }}
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="5" style="padding:10px;">
                <b>Additional Notes :</b>
                <br>- Barang telah diperiksa dan diterima sesuai dengan jumlah yang tercantum, kecuali jika ada catatan
                pada kolom Keterangan.
                <br>- Setelah ditandatangani, tanggung jawab barang beralih ke Penerima.
                <br>- This document generated at {{ tanggalIndoWaktuLidgkap(date('Y-m-d H:i:s')) }}
            </td>
            <td colspan="1" style="padding:10px;">
                <img src="{{ $icon }}" width="43"><br>
                <span style="font-size:12px;">Powered by</span><br>
                <span style="font-size:12px;">Smartwarehouse App</span>
            </td>
        </tr>
    </table>

</body>

</html>
