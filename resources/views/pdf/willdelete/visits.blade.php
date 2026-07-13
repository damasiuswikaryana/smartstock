<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visit Activity Report</title>

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

    <h3 style="margin-left: 45px; margin-bottom: 0px;">Visit Activity Report</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Visitor</th>
                <th>Client Name</th>
                <th>Client Address</th>
                <th>Location</th>
                <th>Visit Duration</th>
                <th>Visit Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td data-column="#">{{ $loop->iteration }}</td>
                    <td data-column="Client Name">{{ $item->user->firstname . ' ' . $item->user->lastname }}</td>
                    <td data-column="Client Type">{{ $item->client_name }}</td>
                    <td data-column="Client Address">{{ $item->client_address }}</td>
                    <td data-column="Location">
                        <a target="_blanks" style="text-decoration: none"
                            href="http://maps.google.com/maps?z=16&t=m&q=loc:{{ $item->latitude }}+{{ $item->longitude }}">
                            Lihat di Google Maps
                        </a>
                    </td>
                    <td data-column="Visit Duration">
                        {{ $item->total_minutes ? $item->total_minutes . 'm' : 'No Check Out Yet' }}</td>
                    <td data-column="Visit Date">
                        {{ date('F j, Y', strtotime($item->visited_at)) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
