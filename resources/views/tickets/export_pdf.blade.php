<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tickets Export</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">KSU ICTO - Ticket List</h2>
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
                    <td>{{ $ticket->status }}</td>
                    <td>{{ optional($ticket->date_submitted)->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
                    <td>{{ optional($ticket->date_finished)->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
