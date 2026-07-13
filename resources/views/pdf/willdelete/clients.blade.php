<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients Data</title>

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

    <h3 style="margin-left: 45px; margin-bottom: 0px;">Data Registered Clients</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Client Name</th>
                <th>Client Type</th>
                <th>Client Address</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Onboarding Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td data-column="#">{{ $loop->iteration }}</td>
                    <td data-column="Client Name">{{ $item->nama }}</td>
                    <td data-column="Client Type">{{ $item->tipe }}</td>
                    <td data-column="Client Address">{{ $item->alamat }}</td>
                    <td data-column="Phone Number">{{ $item->phone }}</td>
                    <td data-column="Email" style="color: dodgerblue;">
                        {{ $item->email }}
                    </td>
                    <td data-column="Onboarding Date">
                        {{ date('F j, Y', strtotime($item->onboarding_at)) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
