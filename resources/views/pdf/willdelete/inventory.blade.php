<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Stock Report</title>

    <style>
        table {
            width: 95%;
            border-collapse: collapse;
            margin: 50px auto;
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
            margin: 25px 20px;
        }

        body {
            margin: 25px 30px;
            font-family: sans-serif;
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

    <h3 style="margin-left: 45px; margin-bottom: 0px;">Inventory Stock Report</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Category</th>
                <th>Last Inbound</th>
                <th>Last Outbound</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td data-column="First Name">{{ $loop->iteration }}</td>
                    <td data-column="First Name">{{ $item->name }}</td>
                    <td data-column="Last Name">{{ $item->qty }}</td>
                    <td data-column="Department">{{ $item->category->title }}</td>
                    <td data-column="Date">
                        {{ empty($item->inLatest) ? 'No Data' : date('F j, Y', strtotime($item->inLatest[0]->created_at)) }}
                    </td>
                    <td data-column="Date">
                        {{ empty($item->outLatest) ? 'No Data' : date('F j, Y', strtotime($item->outLatest[0]->created_at)) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
