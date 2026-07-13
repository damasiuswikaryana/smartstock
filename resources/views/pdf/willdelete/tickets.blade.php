<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets Activity Report</title>

    <style>
        table {
            width: 95%;
            border-collapse: collapse;
            margin: 25px auto;
        }

        /* Zebra striping */
        tr:nth-of-type(odd) {
            background: #eee;
        }

        th {
            background: #415a77;
            color: white;
            font-weight: bold;
        }

        td,
        th {
            padding: 10px;
            border: 1px solid #415a77;
            text-align: left;
            font-size: 18px;

        }

        @page {
            margin: 28px 22px;
        }

        body {
            margin: 25px 30px;
            font-family: sans-serif;
        }

        .text-center {
            text-align: center;
        }

        .fs-big {
            font-size: 48px !important;
            margin: 0 !important;
        }
    </style>

</head>

<body>

    <div style="width: 95%; margin: 0 auto;">
        <div style="width: 15%; float:left; margin-right: 20px;">
            <img src="{{ public_path('assets/images/smartvisit.svg') }}" width="100%" alt="">
        </div>
        </br>
    </div>

    <h3 style="margin-left: 45px; margin-bottom: 0px;">Tickets Activity Report</h3>
    <table style="margin-bottom: 5px;">
        <tbody>
            <tr>
                <td style="padding-left: 25px;">
                    <p style="margin-bottom: 0px;">Total Open Ticket</p>
                    <h4 class="fs-big">{{ $open }}</h4>
                </td>
                <td style="padding-left: 25px;">
                    <p style="margin-bottom: 0px;">Total Ticket On-progress</p>
                    <h4 class="fs-big">{{ $prog }}</h4>
                </td>
                <td style="padding-left: 25px;">
                    <p style="margin-bottom: 0px;">Total Closed Tickets</p>
                    <h4 class="fs-big">{{ $close }}</h4>
                </td>
            </tr>
        </tbody>
    </table>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Created At</th>
                <th>Ticket Code</th>
                <th>Client Name</th>
                <th>Status</th>
                <th>Progress</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td data-column="#" style="text-align: center">{{ $loop->iteration }}</td>
                    <td data-column="Visit Date">
                        {{ date('F j, Y', strtotime($item->created_at)) }}
                    </td>
                    <td data-column="Client Name">{{ $item->code }}</td>
                    <td data-column="Client Type">{{ $item->client->nama }}</td>
                    <td data-column="Client Type">{{ ucwords($item->status) }}</td>
                    <td data-column="Client Type">{{ ucwords($item->progress) }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
