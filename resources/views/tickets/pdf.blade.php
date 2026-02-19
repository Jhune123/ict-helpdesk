{{-- resources/views/tickets/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tickets Export</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20px;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }
        thead {
            background-color: #4A90E2;
            color: white;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
            page-break-inside: avoid;
        }
        th {
            font-size: 12px;
        }
        td {
            font-size: 11px;
        }
        .status-open {
            background-color: #DFF0D8;
            color: #3C763D;
            font-weight: bold;
            text-align: center;
        }
        .status-inprogress {
            background-color: #FCF8E3;
            color: #8A6D3B;
            font-weight: bold;
            text-align: center;
        }
        .status-closed {
            background-color: #F5F5F5;
            color: #777;
            font-weight: bold;
            text-align: center;
        }
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>ICT Helpdesk Tickets Export</h2>

    <table>
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Category</th>
                <th>Department</th>
                <th>IT Personnel</th>
                <th>Priority</th>
                <th>Client Name</th>
                <th>Contact Number / E-mail Address</th>
                <th>Remarks</th>
                <th>Status</th>
                <th>Date Submitted</th>
                <th>Date Finished</th>
                <th>Created By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->title }}</td>
                    <td>{{ $ticket->description }}</td>
                    <td>{{ $ticket->category?->name ?? 'N/A' }}</td>
                    <td>{{ $ticket->department ?? 'N/A' }}</td>
                    <td>{{ $ticket->assignee?->name ?? 'Unassigned' }}</td>
                    <td>{{ $ticket->priority ?? 'Normal' }}</td>
                    <td>{{ $ticket->client_name }}</td>
                    <td>{{ $ticket->contact_number ?? '' }}</td>
                    <td>{{ $ticket->remarks ?? '' }}</td>
                    <td class="
                        @if($ticket->status === 'Open') status-open
                        @elseif($ticket->status === 'In Progress') status-inprogress
                        @elseif($ticket->status === 'Closed') status-closed
                        @endif
                    ">{{ $ticket->status }}</td>
                    <td>{{ $ticket->date_submitted?->format('Y-m-d H:i') }}</td>
                    <td>{{ $ticket->date_finished?->format('Y-m-d H:i') ?? '' }}</td>
                    <td>{{ $ticket->creator?->name ?? 'System' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
