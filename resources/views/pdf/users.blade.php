<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>

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

    <h3 style="margin-left: 45px; margin-bottom: 0px;">All Registered Employees</h3>
    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Department</th>
                <th>Job Title</th>
                <th>Job Status</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Date Of Joining</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td data-column="First Name">{{ $user->emp_id }}</td>
                    <td data-column="First Name">{{ $user->firstname }}</td>
                    <td data-column="Last Name">{{ $user->lastname }}</td>
                    <td data-column="Department">{{ $user->divisi->nama }}</td>
                    <td data-column="Job Title">{{ $user->dept->nama }}</td>
                    <td data-column="Status">{{ $user->status }}</td>
                    <td data-column="Email" style="color: dodgerblue;">
                        {{ $user->email }}
                    </td>
                    <td data-column="Phone Number">{{ $user->phone }}</td>
                    <td data-column="Date">
                        {{ date('F j, Y', strtotime($user->created_at)) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
