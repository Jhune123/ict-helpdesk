<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Tickets Export Report</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5px;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }

        /* Header Layout */
        .header-container {
            width: 100%;
            margin-bottom: 12px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 6px;
        }

        .header-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0;
            text-transform: uppercase;
        }

        .header-meta {
            font-size: 8px;
            color: #4b5563;
            margin-top: 3px;
        }

        /* Table Formatting */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            border: 0.5px solid #d1d5db;
            padding: 4px 3px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        th {
            background-color: #f3f4f6;
            color: #1f2937;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tr {
            page-break-inside: avoid;
        }

        /* Priority & Status Badges */
        .badge {
            font-weight: bold;
            display: inline-block;
        }

        .status-open { color: #d97706; }
        .status-progress { color: #2563eb; }
        .status-closed { color: #16a34a; }
        .status-condemned { color: #dc2626; }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 15px;
            font-size: 7.5px;
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
        <h1 class="header-title">Tickets Report Summary</h1>
        <div class="header-meta">
            <strong>Total Records:</strong> {{ count($tickets) }} | 
            <strong>Generated On:</strong> {{ \Carbon\Carbon::now('Asia/Manila')->format('M d, Y h:i A') }}
        </div>
    </div>

    <table>
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
        <tbody>
            @forelse($tickets as $ticket)
            <tr>
                <td><strong>{{ $ticket->ticket_number }}</strong></td>
                <td>{{ $ticket->title }}</td>
                <td>{{ Str::limit($ticket->description, 60) }}</td>
                <td>{{ $ticket->equipment_type ?? '-' }}</td>
                <td>{{ $ticket->brand_model ?? '-' }}</td>
                <td>{{ $ticket->serial_no ?? '-' }}</td>
                <td>{{ $ticket->category?->name ?? '-' }}</td>
                <td>{{ $ticket->department ?? '-' }}</td>
                <td>{{ $ticket->assignee?->name ?? 'Unassigned' }}</td>
                <td>{{ $ticket->client_name }}</td>
                <td>{{ $ticket->priority ?? 'Normal' }}</td>
                <td>{{ $ticket->contact_number ?? '-' }}</td>
                <td>
                    @php
                        $statusLower = strtolower($ticket->status);
                        $class = match($statusLower) {
                            'open' => 'status-open',
                            'in progress' => 'status-progress',
                            'closed', 'finished' => 'status-closed',
                            'condemned' => 'status-condemned',
                            default => ''
                        };
                    @endphp
                    <span class="badge {{ $class }}">{{ $ticket->status }}</span>
                </td>
                <td>
                    {{ $ticket->date_submitted ? \Carbon\Carbon::parse($ticket->date_submitted)->format('M d, Y') : '-' }}
                </td>
                <td>
                    {{ $ticket->date_finished ? \Carbon\Carbon::parse($ticket->date_finished)->format('M d, Y') : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="15" style="text-align: center; padding: 10px;">No tickets found for the selected period.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <span class="page-number"></span>
    </div>

</body>
</html>