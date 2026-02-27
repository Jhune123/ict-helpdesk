<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ICTO Ticket Report</title>
    <style>
        body { 
            font-family: 'Helvetica', sans-serif; 
            font-size: 11px; 
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1a365d;
            padding-bottom: 10px;
        }
        h1 { color: #1a365d; margin: 0; font-size: 20px; text-transform: uppercase; }
        h2 { color: #4a5568; margin: 5px 0 0 0; font-size: 14px; font-weight: normal; }
        
        .meta-info {
            text-align: right;
            margin-bottom: 10px;
            font-size: 10px;
            color: #718096;
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        th { 
            background-color: #1a365d; 
            color: white; 
            padding: 8px; 
            text-transform: uppercase;
            font-size: 9px;
            border: 1px solid #1a365d;
        }
        td { 
            padding: 7px; 
            text-align: center; 
            border: 1px solid #cbd5e0; 
        }
        
        /* Mirroring Ticket Management Statuses */
        .status-open { color: #2f855a; font-weight: bold; }
        .status-progress { color: #c05621; font-weight: bold; }
        .status-closed { color: #4a5568; }

        .footer {
            margin-top: 40px;
            font-size: 9px;
            color: #a0aec0;
        }
        .signature-section {
            margin-top: 50px;
            width: 100%;
        }
        .sig-box {
            width: 200px;
            border-top: 1px solid #333;
            text-align: center;
            padding-top: 5px;
            float: right;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Kalinga State University</h1>
        <h2 style="font-weight: bold;">ICTO–TICKET QUEUING SYSTEM</h2>
        <p style="margin: 0; font-size: 12px;">Detailed Ticket Activity Report</p>
    </div>

    <div class="meta-info">
        <strong>Date Generated:</strong> {{ now('Asia/Manila')->format('F d, Y | h:i A') }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Ticket #</th>
                <th width="30%">Client / Subject</th>
                <th width="15%">Status</th>
                <th width="20%">Assigned Personnel</th>
                <th width="10%">Filed</th>
                <th width="10%">Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach($queues as $ticket)
            <tr>
                <td style="font-weight: bold;">{{ $ticket->ticket_number ?? $ticket->id }}</td>
                <td style="text-align: left;">
                    <div style="font-weight: bold;">{{ $ticket->subject }}</div>
                    <div style="font-size: 9px; color: #4a5568;">{{ $ticket->user->name ?? 'System Client' }}</div>
                </td>
                <td>
                    @if($ticket->status === 'Open')
                        <span class="status-open">WAITING</span>
                    @elseif($ticket->status === 'In Progress')
                        <span class="status-progress">SERVING</span>
                    @else
                        <span class="status-closed">CLOSED</span>
                    @endif
                </td>
                <td style="font-weight: bold;">
                    {{ $ticket->assigned_to ?? '---' }}
                </td>
                <td>{{ $ticket->created_at->format('h:i A') }}</td>
                <td>
                    {{ $ticket->status === 'Closed' ? $ticket->updated_at->format('h:i A') : '--:--' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($queues->isEmpty())
        <p style="text-align: center; margin-top: 30px; color: #a0aec0; font-style: italic;">No active tickets found for this report period.</p>
    @endif

    <div class="signature-section">
        <div class="sig-box">
            <strong>Verified By:</strong><br><br><br>
            ICTO Supervisor / Admin
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="footer">
        <hr style="border: 0; border-top: 1px solid #edf2f7; margin-bottom: 5px;">
        This document is an official system-generated report from the ICTO-MIS Mirror Portal.
    </div>

</body>
</html>