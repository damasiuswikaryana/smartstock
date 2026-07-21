<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stock Report</title>
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
            padding: 2px;
            vertical-align: baseline;
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
    @foreach ($stocks as $warehouse => $categories)
        <div class="row">
            <div class="col-12 mb-2">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <h2 style="text-align:center; margin:0px; padding:0px;">{{ $warehouse }}</h2>
                        <h3 style="text-align:center; margin:0px; padding:0px; font-weight:400;">Stock Report On
                            {{ now()->format('d F Y, h:i A') }}</h3>
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
                                <th class="tb_head">SKU</th>
                                <th class="tb_head">ITEM</th>
                                <th class="tb_head">VARIANT</th>
                                <th class="tb_head">ENTITY</th>
                                <th class="tb_head">QTY</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category => $items)
                                <tr>
                                    <td class="tb_category" colspan="6">{{ $category }}</td>
                                </tr>
                                @foreach ($items as $stock)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $stock->item_varian->sku_varian }}</td>
                                        <td>{{ $stock->item_varian->itemMaster->nama }}</td>
                                        <td class="text-center">{{ $stock->item_varian->kode_varian }}</td>
                                        <td class="text-center">
                                            <small class="text-muted">
                                                {{ $stock->entitas->entitas_name }}
                                            </small>
                                        </td>
                                        <td class="text-center">{{ $stock->jumlah }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                    <!--stock produk-->
                </div>
            </div>
            <p style="font-style:italic;">Dokumen ini dibuat otomatis dengan Smartwerehouse pada
                {{ now()->format('d F Y, h:i A') }}</p>
        </div>


        @if (!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
</body>

</html>
