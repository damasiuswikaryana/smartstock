<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quotation Request</title>
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
            <td colspan="3" style="padding:10px;">
                <div class="row">
                    <div class="col-4" style="">
                        <img src="{{ $logo }}" width="130">
                    </div>
                    <div class="col-8" style="">
                        <h2 class="text-center" style="margin:5px 0px 0px 0px; padding:0px;">
                            QUOTATION REQUEST
                        </h2>
                    </div>
                </div>
            </td>
            <td colspan="3" style="padding:10px;">
                <table class="table no-border">
                    <tr>
                        <td width="30%"><b>QR No.</b></td>
                        <td>: {{ $data->qr_no }}</td>
                    </tr>
                    <tr>
                        <td><b>QR Date</b></td>
                        <td>: {{ tanggalIndo($data->qr_date) }}</td>
                    </tr>
                    <tr>
                        <td><b>QR Status</b></td>
                        <td>: @if ($data->qr_status == 'Pending')
                                PENDING
                            @else
                                APPROVED
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        {{-- header --}}
        {{-- company sender and destination --}}
        <tr>
            <td colspan="3" style="padding:10px;">
                <b>Company Information:</b><br><br>
                <b>{{ $data->entitas->entitas_company }}</b><br>
                {{ $data->entitas->entitas_alamat }} <br>
                <table class="table no-border">
                    <tr>
                        <td width="13%">Phone</td>
                        <td width="2%">:</td>
                        <td>{{ $data->entitas->entitas_phone }}</td>
                    </tr>
                    <tr>
                        <td width="13%">Email</td>
                        <td width="2%">:</td>
                        <td>{{ $data->entitas->entitas_email }}</td>
                    </tr>
                </table>
            </td>
            <td colspan="3" style="padding:10px;">
                <b>Order to:</b><br><br>
                <b>{{ $data->vendor->nama }}</b><br>
                {{ $data->vendor->alamat }} <br>
                <table class="table no-border">
                    <tr>
                        <td width="13%">Phone</td>
                        <td width="2%">:</td>
                        <td>{{ $data->vendor->phone }}</td>
                    </tr>
                    <tr>
                        <td width="13%">Email</td>
                        <td width="2%">:</td>
                        <td>{{ $data->vendor->email }}</td>
                    </tr>
                    <tr>
                        <td width="13%">TOP</td>
                        <td width="2%">:</td>
                        <td>{{ $data->vendor->termin_pembayaran != null ? $data->vendor->termin_pembayaran . ' hari' : 'CASH' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        {{-- company sender and destination --}}
        {{-- items --}}
        <tr>
            <th width="5%">No.</th>
            <th width="45%">Item(s) Description</th>
            <th width="8%">Qty</th>
            <th width="8%">Unit</th>
            <th width="20%">Unit Price</th>
            <th width="20%">Amount</th>
        </tr>
        @foreach ($data->child as $index => $item)
            @php
                $amount = $item->unit_price * $item->qty;
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->varian->name_varian }}</td>
                <td class="text-center">
                    {{ $item->qty }}
                </td>
                <td class="text-center">
                    {{ getSatuanName($item->satuan_id) }}
                </td>
                <td class="text-sm-end">
                    <span class="float-left">Rp.</span>
                    <span class="float-right">{{ pecahTanpaRp($item->unit_price) }}</span>
                    <div class="clear-both"></div>
                </td>
                <td class="text-sm-end">
                    <span class="float-left">Rp.</span>
                    <span class="float-right">{{ pecahTanpaRp($amount) }}</span>
                    <div class="clear-both"></div>
                </td>
            </tr>
        @endforeach
        {{-- items --}}
        {{-- Baris kosong --}}
        @for ($i = count($data->child); $i < 8; $i++)
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endfor
        {{-- baris kosong --}}
        <tr>
            <td colspan="4" rowspan="5" style="padding:0px 10px; vertical-align:top;">
                <b>Term & Condition</b>
                <br>
                <table class="table no-border">
                    <tr>
                        <td width="2%">-</td>
                        <td>Seluruh proses pengiriman barang harus disertai dengan
                            faktur, nota, kwitansi dan GRN.</td>
                    </tr>
                    <tr>
                        <td width="2%">-</td>
                        <td>Proses pelunasan dilakukan selambat-lambatnya
                            (....) hari sejak barang diterima.</td>
                    </tr>
                </table>
            </td>
            <td><b>Sub Total</b></td>
            <td class="text-sm-end d-flex">
                <span class="float-left">Rp.</span>
                <span class="float-right">{{ pecahTanpaRp($subtotal) }}</span>
            </td>
        </tr>
        <tr>
            <td><b>Tax {{ $data->tax }}%</b></td>
            <td class="text-sm-end">
                <span class="float-left">Rp.</span>
                <span class="float-right">{{ pecahTanpaRp($tax_amount) }}</span>
            </td>
        </tr>
        <tr>
            <td><b>Discount</b></td>
            <td class="text-sm-end">
                <span class="float-left">Rp.</span>
                <span class="float-right">{{ pecahTanpaRp($data->disc) }}</span>
            </td>
        </tr>
        <tr>
            <td><b>DP</b></td>
            <td class="text-sm-end">
                <span class="float-left">Rp.</span>
                <span class="float-right">{{ pecahTanpaRp($data->dp) }}</span>
            </td>
        </tr>
        <tr>
            <td><b>Total</b></td>
            <td class="text-sm-end fw-bold">
                <span class="float-left">Rp.</span>
                <span class="float-right">{{ pecahTanpaRp($total_after_dp) }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="height:180px; padding:15px;">
                <div class="row">
                    <div class="col-4 text-center">
                        <b>Processed by Procurement</b>
                        <br><br>
                        <img src="{{ $checked }}" width="43"><br>
                        <span>Created at <br>{{ tanggalIndoWaktuLidgkap($data->created_at) }}</span>
                        <br><br>
                        {{ '(' . $data->createdBy->firstname . ' ' . $data->createdBy->lastname . ')' }}
                    </div>
                    <div class="col-4 text-center">
                        <b>Checked</b>
                        <br><br>
                        @if ($data->checked_date != null)
                            <img src="{{ $checked }}" width="43"><br>
                            <span>Signed at <br>{{ tanggalIndoWaktuLidgkap($data->checked_date) }}</span>
                        @else
                            <br><br><br>
                        @endif
                        <br><br>
                        @if ($data->checkedBy != null)
                            {{ '(' . $data->checkedBy->firstname . ' ' . $data->checkedBy->lastname . ')' }}
                        @else
                            (_____________________)
                        @endif
                    </div>
                    <div class="col-4 text-center">
                        <b>Director Approval</b>
                        <br><br>
                        @if ($data->director_date != null)
                            <img src="{{ $checked }}" width="43"><br>
                            <span>Signed at <br>{{ tanggalIndoWaktuLidgkap($data->director_date) }}</span>
                        @else
                            <br><br><br>
                        @endif
                        <br><br>
                        @if ($data->directorBy != null)
                            {{ '(' . $data->directorBy->firstname . ' ' . $data->directorBy->lastname . ')' }}
                        @else
                            (_____________________)
                        @endif
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="padding:10px;">
                <b>Notes :</b>
                <br>- Signature must be filled with date
                <br>- All approval based on Smartwarehouse App
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
