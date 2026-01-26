<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tickets Export</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2>KSU ICTO – Ticket List</h2>

    <p style="text-align:center; font-size:10px;">
        Generated on {{ now('Asia/Manila')->format('F d, Y | h:i A') }} (PH Time)
    </p>

    <table>
        <thead>
            <tr>
                <th>Ticket #</th>
                <th>Title</th>
                <th>Description</th>
                <th>Category</th>
                <th>Department</th>
                <th>IT Personnel</th>
                <th>Client Name</th>
                <th>Priority</th>
                <th>Contact Number</th>
                <th>Remarks</th>
                <th>Status</th>
                <th>Date Submitted</th>
                <th>Date Finished</th>
            </tr>
        </thead>

        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->ticket_number }}</td>

                <td>{{ $ticket->title }}</td>

                <td>{{ $ticket->description }}</td>

                <td>{{ $ticket->category?->name ?? '-' }}</td>

                <td>{{ $ticket->department ?? '-' }}</td>

                <td>{{ $ticket->assignee?->name ?? '-' }}</td>

                <td>{{ $ticket->client_name }}</td>

                <td>{{ $ticket->priority }}</td>

                <td>{{ $ticket->contact_number }}</td>

                <td>{{ $ticket->remarks }}</td>

                <td>{{ ucfirst($ticket->status) }}</td>

                {{-- DATE SUBMITTED --}}
                <td>
                    {{ $ticket->created_at
                        ? $ticket->created_at->timezone('Asia/Manila')->format('M d, Y h:i A')
                        : '-' }}
                </td>

                {{-- DATE FINISHED --}}
                <td>
                    {{ $ticket->date_finished
                        ? \Carbon\Carbon::parse($ticket->date_finished)
                            ->timezone('Asia/Manila')
                            ->format('M d, Y h:i A')
                        : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
