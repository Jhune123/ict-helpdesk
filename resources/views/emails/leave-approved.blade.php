<p>Hello,</p>

<p>This is to inform you that the following ICT staff member will be on leave on the approved dates below:</p>

<ul>
    <li><strong>Staff member:</strong> {{ $staffName }}</li>
    <li><strong>Leave type:</strong> {{ $leave->leave_type }}@if($leave->other_leave_type) ({{ $leave->other_leave_type }})@endif</li>
    <li><strong>Approved leave dates:</strong> {{ $leave->start_date->format('F j, Y') }} to {{ $leave->end_date->format('F j, Y') }}</li>
    <li><strong>Working days:</strong> {{ $leave->working_days }}</li>
</ul>

<p>Please take this planned absence into account when coordinating ICT Department coverage.</p>
