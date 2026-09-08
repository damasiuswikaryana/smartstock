<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Procurement to Warehouse</title>
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
            font-size: 13px;
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
            <td colspan="3" style="padding:10px;">
                <div class="row">
                    <div class="col-12" style="">
                        <h2 class="text-center" style="margin:5px 0px 0px 0px; padding:0px;">
                            PROCUREMENT TO WAREHOUSE
                        </h2>
                    </div>
                </div>
            </td>
            <td colspan="4" style="padding:10px;">
                <table class="table no-border">
                    <tr>
                        <td width="30%"><b>PTW NO.</b></td>
                        <td>: {{ $data->ptw_number }}</td>
                    </tr>
                    <tr>
                        <td width="30%"><b>PRF NO.</b></td>
                        <td>: @foreach ($data->child->unique('po_id') as $child)
                                @foreach (explode(',', $child->poMaster->prf_number) as $prf)
                                    {{ trim($prf) . ', ' }}
                                @endforeach
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td><b>TGL MASUK</b></td>
                        <td>: {{ tanggalIndo($data->ptw_date) }}</td>
                    </tr>
                    <tr>
                        <td><b>DPT / PROJECT</b></td>
                        <td>:
                            {{ $data->project->name }}
                        </td>
                    </tr>
                    <tr>
                        <td><b>STATUS</b></td>
                        <td>:
                            {{ $data->ptw_status == 'Gudang' ? 'Received by Warehouse' : $data->ptw_status }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        {{-- header --}}
        {{-- items --}}
        <tr>
            <th width="3%">No.</th>
            <th width="35%">Item(s) Description</th>
            <th width="8%">PRF</th>
            <th width="8%">PO</th>
            <th width="8%">IN</th>
            <th width="10%">Unit</th>
            <th width="25%">Keterangan</th>
        </tr>
        @foreach ($data->child as $child)
            <tr>
                <td class="text-center" width="3%">{{ $loop->iteration }}</td>
                <td width="35%">
                    {{ $child->varian->name_varian }}<br>
                    <small>{{ $child->poMaster->vendor->nama }}</small>
                </td>
                <td class="text-center" width="8%">{{ $child->prf_jum }}</td>
                <td class="text-center" width="8%">{{ $child->qty_po ?? 0 }}</td>
                <td class="text-center" width="8%">{{ $child->qty_po ?? 0 }}</td>
                <td class="text-center" width="10%">{{ $child->varian->satuan->satuan ?? '' }}</td>
                <td width="25%">{{ $child->note }}</td>
            </tr>
        @endforeach
        {{-- items --}}
        <tr>
            <td colspan="7" style="padding:5px;">
                <b>PO ASSOCIATE</b>
            </td>
        </tr>
        <tr>
            <th><b>No.</b></th>
            <th><b>Purchase Order</b></th>
            <th colspan="3"><b>Vendor</b></th>
            <th colspan="1"><b>Item(s)</b></th>
            <th><b>PO Date</b></th>
        </tr>
        @foreach ($data->child->unique('po_id') as $child)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $child->poMaster->po_no }}</td>
                <td colspan="3">{{ $child->poMaster->vendor->nama }}</td>
                <td class="text-center" colspan="1">{{ $child->poMaster->child->count('item_varian_id') }}</td>
                <td>{{ tglIndo4($child->poMaster->po_date) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="7" style="height:180px; padding:15px;">
                <div class="row">
                    <div class="col-4 text-center">
                        <b>Handover by</b>
                        <br><br>
                        <img src="{{ $checked }}" width="43"><br>
                        <span>Created at <br>{{ tanggalIndoWaktuLidgkap($data->created_at) }}</span>
                        <br><br>
                        {{ '(' . $data->createdBy->firstname . ' ' . $data->createdBy->lastname . ')' }}
                    </div>
                    <div class="col-4 text-center">
                        <b>Received by</b>
                        <br><br>
                        <br><br>
                        <br><br>
                        ( {{ $gudang->firstname . ' ' . $gudang->lastname }} )
                    </div>
                    <div class="col-4 text-center">
                        <b>Acknowledge by</b>
                        <br><br>
                        <br><br>
                        <br><br>
                        ( I Komang Sudarna )
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="5" style="padding:10px;">
                <b>Notes :</b>
                <br>- This document generated at {{ tanggalIndoWaktuLidgkap(date('Y-m-d H:i:s')) }}
            </td>
            <td colspan="2" style="padding:10px;">
                <img src="{{ $icon }}" width="43"><br>
                <span style="font-size:12px;">Powered by</span><br>
                <span style="font-size:12px;">Smartwarehouse App</span>
            </td>
        </tr>
    </table>
</body>

</html>
