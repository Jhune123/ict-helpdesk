<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Tickets Export Report</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm;
        }

        body {
            font-family: sans-serif;
            font-size: 8px;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }

        .header-container {
            width: 100%;
            margin-bottom: 8px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 4px;
        }

        .header-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0;
            text-transform: uppercase;
        }

        .header-meta {
            font-size: 8px;
            color: #4b5563;
            margin-top: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 4px;
        }

        th, td {
            border: 0.5px solid #d1d5db;
            padding: 3px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        th {
            background-color: #f3f4f6;
            color: #1f2937;
            font-weight: bold;
            font-size: 7.5px;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 12px;
            font-size: 7px;
            color: #6b7280;
            text-align: right;
        }

        .page-number:before {
            content: "Page " counter(page);
        }
    </style>
</head>
<body>

    <div class="header-container">
        <h1 class="header-title">Tickets Master Export</h1>
        <div class="header-meta">
            <strong>Total Records:</strong> {{ $totalCount }} | 
            <strong>Generated On:</strong> {{ \Carbon\Carbon::now('Asia/Manila')->format('M d, Y h:i A') }}
        </div>
    </div>

    @forelse($ticketChunks as $chunkIndex => $tickets)
        <table>
            @if($chunkIndex === 0)
            <thead>
                <tr>
                    <th style="width: 8%;">Ticket #</th>
                    <th style="width: 8%;">Title</th>
                    <th style="width: 10%;">Description</th>
                    <th style="width: 6%;">Eq. Type</th>
                    <th style="width: 7%;">Brand/Model</th>
                    <th style="width: 6%;">Serial No.</th>
                    <th style="width: 7%;">Category</th>
                    <th style="width: 7%;">Department</th>
                    <th style="width: 7%;">IT Staff</th>
                    <th style="width: 7%;">Client</th>
                    <th style="width: 5%;">Priority</th>
                    <th style="width: 7%;">Contact</th>
                    <th style="width: 5%;">Status</th>
                    <th style="width: 5%;">Submitted</th>
                    <th style="width: 5%;">Finished</th>
                </tr>
            </thead>
            @endif
            <tbody>
                @foreach($tickets as $ticket)
                <tr>
                    <td><strong>{{ $ticket->ticket_number }}</strong></td>
                    <td>{{ \Illuminate\Support\Str::limit($ticket->title ?? '', 30) }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($ticket->description ?? '', 40) }}</td>
                    <td>{{ $ticket->equipment_type ?? '-' }}</td>
                    <td>{{ $ticket->brand_model ?? '-' }}</td>
                    <td>{{ $ticket->serial_no ?? '-' }}</td>
                    <td>{{ $ticket->category?->name ?? '-' }}</td>
                    <td>{{ $ticket->department ?? '-' }}</td>
                    <td>{{ $ticket->assignee?->name ?? 'Unassigned' }}</td>
                    <td>{{ $ticket->client_name ?? '-' }}</td>
                    <td>{{ $ticket->priority ?? 'Normal' }}</td>
                    <td>{{ $ticket->contact_number ?? '-' }}</td>
                    <td>{{ $ticket->status }}</td>
                    <td>
                        {{ !empty($ticket->date_submitted) ? \Carbon\Carbon::parse($ticket->date_submitted)->format('M d, Y') : '-' }}
                    </td>
                    <td>
                        {{ !empty($ticket->date_finished) ? \Carbon\Carbon::parse($ticket->date_finished)->format('M d, Y') : '-' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <table>
            <tbody>
                <tr>
                    <td style="text-align: center; padding: 10px;">No records found.</td>
                </tr>
            </tbody>
        </table>
    @endforelse

    <div class="footer">
        <span class="page-number"></span>
    </div>

</body>
</html>