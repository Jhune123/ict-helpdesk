<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $subjectText ?? 'Ticket Notification' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 30px;">

    <div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 8px; padding: 25px; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
        
        <!-- Header -->
        <h2 style="color: #2563eb; margin-top: 0; text-align: center;">
            🎫 {{ $subjectText ?? 'Ticket Update' }}
        </h2>

        <!-- Greeting -->
        <p style="font-size: 16px; color: #374151; margin-top: 20px;">
            Hello {{ $ticket->user->name ?? 'User' }},
        </p>

        <!-- Message Body -->
        <p style="font-size: 15px; color: #4b5563; line-height: 1.6; white-space: pre-line;">
            {{ $messageBody ?? 'There has been an update regarding your ticket.' }}
        </p>

        <!-- Divider -->
        <hr style="margin: 25px 0; border: none; border-top: 1px solid #e5e7eb;">

        <!-- Ticket Info -->
        <div style="font-size: 14px; color: #6b7280; line-height: 1.6;">
            <p><strong>Ticket Title:</strong> {{ $ticket->title ?? 'N/A' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($ticket->status ?? 'N/A') }}</p>
            <p><strong>Ticket ID:</strong> #{{ $ticket->id ?? 'N/A' }}</p>
        </div>

        <!-- View Button -->
        @if(isset($ticket->id))
            <p style="margin-top: 25px; text-align: center;">
                <a href="{{ route('tickets.show', $ticket->id) }}" 
                   style="display:inline-block; padding:10px 20px; background-color:#2563eb; color:#ffffff; text-decoration:none; border-radius:5px; font-size:14px;">
                    🔍 View Ticket
                </a>
            </p>
        @endif

        <!-- Footer -->
        <p style="font-size: 12px; color: #9ca3af; margin-top: 30px; text-align: center;">
            This is an automated message from the <strong>KSU ICT Helpdesk System</strong>. Please do not reply directly.
        </p>
    </div>

</body>
</html>
