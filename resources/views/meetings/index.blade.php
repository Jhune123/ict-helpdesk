@extends('layouts.app')

@section('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
    table.dataTable thead {
        background-color: #16A34A; /* Green header */
        color: #fff;
    }
    table.dataTable tbody tr:hover {
        background-color: #DCFCE7; /* Light green hover */
    }
    .upcoming-meeting {
        background-color: #DCFCE7 !important; /* Light green */
    }
    .past-meeting {
        background-color: #FEE2E2 !important; /* Light red */
    }
    table.dataTable td .btn {
        white-space: nowrap;
        margin-right: 2px;
    }
    .table-responsive {
        overflow-x: auto;
    }
    table.dataTable {
        table-layout: fixed;
        width: 100% !important;
    }
    table.dataTable td, table.dataTable th {
        word-wrap: break-word;
        vertical-align: middle;
    }
    @media print {
        table.dataTable {
            width: 100% !important;
        }
        .dataTables_wrapper .dt-buttons {
            display: none;
        }
        table.dataTable th, table.dataTable td {
            font-size: 10pt;
        }
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-green-700">📅 Meetings</h2>

        <div class="flex gap-2">
            @role('admin|it_staff|client')
                <a href="{{ route('meetings.create') }}" 
                   class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                   + Create Meeting
                </a>
            @endrole
        </div>
    </div>

    <div class="table-responsive bg-white shadow-md rounded-lg">
        <table id="meetingsTable" class="display nowrap stripe hover w-full">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Location</th>
                    <th>Facilitator</th>
                    <th>Participants</th>
                    <th>IT Personnel Attendees</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($meetings as $meeting)
                @php
                    $today = \Carbon\Carbon::today();
                    $meetingDate = \Carbon\Carbon::parse($meeting->date);
                    $isUpcoming = $meetingDate->gte($today);
                    $isPast = $meetingDate->lt($today);
                @endphp
                <tr class="{{ $isUpcoming ? 'upcoming-meeting' : ($isPast ? 'past-meeting' : '') }}">
                    <td>{{ $meeting->title }}</td>
                    <td>{{ $meetingDate->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }}</td>
                    <td>{{ \Carbon\Carbon::parse($meeting->end_time)->format('h:i A') }}</td>
                    <td>{{ $meeting->location }}</td>
                    <td>{{ $meeting->facilitator ?? 'N/A' }}</td>
                    <td>{{ $meeting->participants }}</td>
                    <td>
                        @if($meeting->itPersonnel->isNotEmpty())
                            <ul class="list-disc list-inside">
                                @foreach($meeting->itPersonnel as $person)
                                    <li>{{ $person->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-gray-500">None</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('meetings.show', $meeting->id) }}" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">View</a>
                        @role('admin|it_staff')
                        <a href="{{ route('meetings.edit', $meeting->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Edit</a>
                        <form action="{{ route('meetings.destroy', $meeting->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this meeting?')" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button>
                        </form>
                        @endrole
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    $('#meetingsTable').DataTable({
        responsive: true,
        scrollX: true,
        paging: true,
        searching: true,
        ordering: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel',
            {
                extend: 'pdf',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Meeting Schedules'
            },
            {
                extend: 'print',
                title: 'Meeting Schedules',
                customize: function (win) {
                    $(win.document.body).css('font-size', '10pt');
                    $(win.document.body).find('table').addClass('compact').css('font-size', '10pt');
                    $(win.document.body).find('table').css('width', '100%');
                }
            }
        ],
        order: [[1, 'asc']], // order by Date
        autoWidth: false
    });
});
</script>
@endsection
