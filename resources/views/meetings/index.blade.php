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
    table.dataTable th, table.dataTable td { white-space: normal !important; overflow-wrap: anywhere; word-break: normal; vertical-align: top; }
    .wrap-text { white-space: normal !important; overflow-wrap: anywhere; word-break: normal; max-width: 280px; }
    .table-responsive { overflow-x: auto; }
</style>
@endsection

@section('content')
<div class="max-w-full mx-auto px-4 py-6">
    @error('mom')
        <div class="mb-4 rounded border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
            {{ $message }}
        </div>
    @enderror

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
        <table id="meetingsTable" class="display stripe hover w-full">
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
                            <details class="inline-block relative">
                                <summary class="inline-block cursor-pointer list-none bg-amber-500 text-white px-2 py-1 rounded hover:bg-amber-600">MOM</summary>
                                <div class="absolute right-0 z-20 mt-2 w-64 rounded border bg-white p-3 text-left shadow-lg">
                                    @if($meeting->mom_path)
                                        <p class="mb-2 break-all text-xs text-gray-700">{{ $meeting->mom_original_name }}</p>
                                        <div class="mb-3 flex gap-2">
                                            <a href="{{ route('meetings.mom.view', $meeting) }}" target="_blank" rel="noopener" class="rounded bg-green-600 px-2 py-1 text-xs text-white hover:bg-green-700">View</a>
                                            <a href="{{ route('meetings.mom.download', $meeting) }}" class="rounded bg-blue-600 px-2 py-1 text-xs text-white hover:bg-blue-700">Download</a>
                                        </div>
                                    @else
                                        <p class="mb-2 text-xs text-gray-600">No MOM uploaded yet.</p>
                                    @endif
                                    <form action="{{ route('meetings.mom.upload', $meeting) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <label class="mb-1 block text-xs font-semibold text-gray-700" for="mom-{{ $meeting->id }}">{{ $meeting->mom_path ? 'Replace MOM file' : 'Upload MOM file' }}</label>
                                        <input id="mom-{{ $meeting->id }}" type="file" name="mom" accept=".pdf,.doc,.docx" required class="mb-2 w-full text-xs">
                                        <p class="mb-2 text-xs text-gray-500">PDF, DOC, or DOCX; max 10 MB.</p>
                                        <button type="submit" class="rounded bg-amber-600 px-2 py-1 text-xs text-white hover:bg-amber-700">Upload</button>
                                    </form>
                                </div>
                            </details>
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
