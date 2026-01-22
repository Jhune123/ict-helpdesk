@extends('layouts.app')

@section('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<style>
    table.dataTable thead { background-color: #16A34A; color: #fff; }
    table.dataTable tbody tr:hover { background-color: #DCFCE7; }
    .upcoming-meeting { background-color: #DCFCE7 !important; }
    .past-meeting { background-color: #FEE2E2 !important; }
    table.dataTable { table-layout: fixed; width: 100% !important; }
    table.dataTable th, table.dataTable td { white-space: normal; word-wrap: break-word; vertical-align: top; }
    .wrap-text { white-space: normal; word-break: break-word; max-width: 280px; }
    .table-responsive { overflow-x: auto; }
</style>
@endsection

@section('content')
<div class="max-w-full mx-auto px-4 py-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-wrap justify-between items-center mb-4 gap-2">
        <h2 class="text-2xl font-bold text-green-700">📅 Meetings</h2>

        <div class="flex gap-2">
            <a href="{{ route('meetings.calendar') }}" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">
                📆 Calendar View
            </a>

            @role('admin|it_staff|client')
            <a href="{{ route('meetings.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                + Create Meeting
            </a>
            @endrole
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-responsive bg-white shadow-md rounded-lg p-2">
        <table id="meetingsTable" class="display nowrap stripe hover w-full">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Location</th>
                    <th>Facilitator</th>
                    <th>Participants</th>
                    <th>Remarks</th>
                    <th>IT Personnel</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($meetings as $meeting)
                    @php
                        $today = \Carbon\Carbon::today();
                        $meetingDate = \Carbon\Carbon::parse($meeting->date);
                    @endphp
                    <tr class="{{ $meetingDate->gte($today) ? 'upcoming-meeting' : 'past-meeting' }}">
                        <td class="wrap-text font-semibold">{{ $meeting->title }}</td>
                        <td>{{ $meetingDate->format('M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }}</td>
                        <td>{{ \Carbon\Carbon::parse($meeting->end_time)->format('h:i A') }}</td>
                        <td class="wrap-text">{{ $meeting->location }}</td>
                        <td class="wrap-text">{{ $meeting->facilitator ?? 'N/A' }}</td>
                        <td class="wrap-text">{{ $meeting->participants }}</td>
                        <td class="wrap-text">{{ $meeting->remarks ?? '—' }}</td>
                        <td class="wrap-text">
                            @forelse($meeting->itPersonnel as $person)
                                • {{ $person->name }}<br>
                            @empty
                                <span class="text-gray-500">None</span>
                            @endforelse
                        </td>
                        <td class="whitespace-nowrap">
                            <a href="{{ route('meetings.show', $meeting) }}" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">View</a>
                            @role('admin|it_staff')
                            <a href="{{ route('meetings.edit', $meeting) }}" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Edit</a>
                            <form action="{{ route('meetings.destroy', $meeting) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Delete this meeting?')" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
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
$(document).ready(function () {
    $('#meetingsTable').DataTable({
        responsive: true,
        scrollX: true,
        pageLength: 10,
        lengthMenu: [5,10,25,50],
        order: [[1,'asc']],
        dom: 'Bfrtip',
        buttons: ['copy','csv','excel','pdf','print']
    });
});
</script>
@endsection
