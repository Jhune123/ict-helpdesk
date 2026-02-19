<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tickets Export</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Tickets Export</h2>
    <table>
        <thead>
            <tr>
                <th>Ticket #</th>
                <th>Title</th>
                <th>Description</th>
                <th>Equipment Type</th>
                <th>Brand / Model</th>
                <th>Serial No.</th>
                <th>Category</th>
                <th>Department</th>
                <th>IT Personnel</th>
                <th>Client</th>
                <th>Priority</th>
                <th>Contact Number / E-mail Address</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Finished</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->ticket_number }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ $ticket->description }}</td>
                <td>{{ $ticket->equipment_type ?? '-' }}</td>
                <td>{{ $ticket->brand_model ?? '-' }}</td>
                <td>{{ $ticket->serial_no ?? '-' }}</td>
                <td>{{ $ticket->category?->name ?? '-' }}</td>
                <td>{{ $ticket->department ?? '-' }}</td>
                <td>{{ $ticket->assignee?->name ?? '-' }}</td>
                <td>{{ $ticket->client_name }}</td>
                <td>{{ $ticket->priority ?? 'Normal' }}</td>
                <td>{{ $ticket->contact_number ?? '-' }}</td>
                <td>{{ $ticket->status }}</td>
                <td>{{ $ticket->date_submitted?->format('M d, Y h:i A') ?? '-' }}</td>
                <td>{{ $ticket->date_finished?->format('M d, Y h:i A') ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
