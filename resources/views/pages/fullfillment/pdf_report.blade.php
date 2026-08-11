<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mutation History Report</title>
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
            vertical-align: middle;
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
            padding: 2px;
            vertical-align: middle;
        }

        .tb_head {
            background-color: #333 !important;
            color: #fff !important;
        }

        .tb_category {
            background-color: #ffde00 !important;
            font-weight: bold;
            color: #000 !important;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-12 mb-2">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <h2 style="text-align:center; margin:0px; padding:0px;">Stock In (Masuk) History Report</h2>
                    <h3 style="text-align:center; margin:0px; padding:0px; font-weight:400;">
                        {{ $data->name . ' (' . $data->no_kontrak . ')' }}</h3>
                </div>
                <div class="clearboth"></div>
            </div>
        </div>
        <div class="clearboth"></div>
        <br>
        <div class="col-12 mb-2">
            <div class="table-responsive">
                <!--stock produk-->
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th class="tb_head">NO</th>
                            <th class="tb_head">DATE</th>
                            <th class="tb_head">ITEM</th>
                            <th class="tb_head">SOURCE</th>
                            <th class="tb_head">TARGET</th>
                            <th class="tb_head">QTY</th>
                            <th class="tb_head">NOTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_masuk as $item_in)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ tanggalIndo3($item_in->created_at) }}</td>
                                <td>
                                    {{ $item_in->item_varian->name_varian }}
                                    <br>{{ $item_in->item_varian->sku_varian }}
                                </td>
                                <td class="text-center">
                                    {{ $item_in->source_type }}
                                </td>
                                <td>
                                    {{ $item_in->gudangTarget->nama }}
                                </td>
                                <td class="text-center">
                                    {{ $item_in->jumlah }}
                                </td>
                                <td>
                                    {{ $item_in->keterangan }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada stok masuk di project ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <!--stock produk-->
            </div>
        </div>
    </div>
    <div style="page-break-after: always;"></div>

    <div class="row">
        <div class="col-12 mb-2">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <h2 style="text-align:center; margin:0px; padding:0px;">Stock Out (Keluar)</h2>
                    <h3 style="text-align:center; margin:0px; padding:0px; font-weight:400;">
                        {{ $data->name . ' (' . $data->no_kontrak . ')' }}</h3>
                </div>
                <div class="clearboth"></div>
            </div>
        </div>
        <div class="clearboth"></div>
        <br>
        <div class="col-12 mb-2">
            <div class="table-responsive">
                <!--stock produk-->
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th class="tb_head">NO</th>
                            <th class="tb_head">DATE</th>
                            <th class="tb_head">ITEM</th>
                            <th class="tb_head">SOURCE</th>
                            <th class="tb_head">TARGET</th>
                            <th class="tb_head">QTY</th>
                            <th class="tb_head">NOTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_keluar as $item_out)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ tanggalIndo3($item_out->created_at) }}</td>
                                <td>
                                    {{ $item_out->item_varian->name_varian }}
                                    <br>{{ $item_out->item_varian->sku_varian }}
                                </td>
                                <td class="text-center">
                                    {{ $item_out->source_type }}
                                </td>
                                <td>
                                    {{ $item_out->gudangTarget->nama }}
                                </td>
                                <td class="text-center">
                                    {{ $item_out->jumlah }}
                                </td>
                                <td>
                                    {{ $item_out->keterangan }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada stok keluar di project ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <!--stock produk-->
            </div>
        </div>
    </div>
    <div style="page-break-after: always;"></div>

    <div class="row">
        <div class="col-12 mb-2">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <h2 style="text-align:center; margin:0px; padding:0px;">Stock Transfer</h2>
                    <h3 style="text-align:center; margin:0px; padding:0px; font-weight:400;">
                        {{ $data->name . ' (' . $data->no_kontrak . ')' }}</h3>
                </div>
                <div class="clearboth"></div>
            </div>
        </div>
        <div class="clearboth"></div>
        <br>
        <div class="col-12 mb-2">
            <div class="table-responsive">
                <!--stock produk-->
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th class="tb_head">NO</th>
                            <th class="tb_head">DATE</th>
                            <th class="tb_head">ITEM</th>
                            <th class="tb_head">SOURCE</th>
                            <th class="tb_head">TARGET</th>
                            <th class="tb_head">QTY</th>
                            <th class="tb_head">NOTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_transfer as $item_transfer)
                            <tr>
                                <td class="text-center" style="width:2%;">{{ $loop->iteration }}</td>
                                <td class="text-center" style="width:10%;">
                                    {{ tanggalIndo3($item_transfer->created_at) }}</td>
                                <td style="width:30%;">
                                    {{ $item_transfer->item_varian->name_varian }}
                                    <br>{{ $item_transfer->item_varian->sku_varian }}
                                </td>
                                <td class="text-center">
                                    {{ $item_transfer->source_type }}
                                </td>
                                <td class="text-center">
                                    {{ $item_transfer->gudangTarget->nama }}
                                </td>
                                <td style="width:4%;" class="text-center">
                                    {{ $item_transfer->jumlah }}
                                </td>
                                <td style="width:30%;">
                                    {{ $item_transfer->keterangan }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada stok transfer di project ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <!--stock produk-->
            </div>
        </div>
        <p style="font-style:italic;">Dokumen ini dibuat otomatis dengan Smartwerehouse pada
            {{ now()->format('d F Y, h:i A') }}</p>
    </div>
</body>

</html>
