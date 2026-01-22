@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
    table.dataTable thead {
        background-color: #1E40AF; /* Blue header */
        color: #fff;
    }
    table.dataTable tbody tr:hover {
        background-color: #E0F2FE; /* Light blue hover */
    }
    .upcoming-task {
        background-color: #DCFCE7 !important; /* Light green */
    }
    .overdue-task {
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
    .wrap-text {
        white-space: normal !important;
        word-break: break-word;
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
<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-700">🗓 Task Schedule</h2>

        <div class="flex gap-2">
            @role('admin|it_staff')
            <a href="{{ route('tasks.create') }}" 
               class="inline-block px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition">
                + Add Task
            </a>
            @endrole

            <a href="{{ route('tasks.export.pdf') }}" target="_blank"
               class="inline-block px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg shadow hover:bg-green-700 transition">
                Export PDF
            </a>
        </div>
    </div>

    <div class="table-responsive bg-white shadow rounded-lg">
        <table id="tasksTable" class="display nowrap stripe hover" style="width:100%">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Requested By</th>
                    <th>Department</th>
                    <th>Location</th>
                    <th>Time Range</th>
                    <th>IT Personnel</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                @php
                    $today = \Carbon\Carbon::today();
                    $taskDate = \Carbon\Carbon::parse($task->date);
                    $isUpcoming = $taskDate->between($today, $today->copy()->addDays(7));
                    $isOverdue = $taskDate->lt($today);
                @endphp
                <tr class="{{ $isUpcoming ? 'upcoming-task' : ($isOverdue ? 'overdue-task' : '') }}">
                    <td>{{ $taskDate->format('M d, Y') }}</td>
                    <td class="wrap-text">{{ $task->description }}</td>
                    <td class="wrap-text">{{ $task->requested_by }}</td>
                    <td class="wrap-text">{{ $task->department->name ?? '—' }}</td>
                    <td>{{ $task->location }}</td>
                    <td>{{ \Carbon\Carbon::parse($task->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($task->end_time)->format('h:i A') }}</td>
                    <td>{{ $task->assigned_to ?? 'N/A' }}</td>
                    <td class="wrap-text">{{ $task->remarks ?? '—' }}</td>
                    <td>
                        <a href="{{ route('tasks.show', $task) }}" class="btn-view">View</a>
                        @role('admin|it_staff')
                        <a href="{{ route('tasks.edit', $task) }}" class="btn-edit">Edit</a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Delete this task?')" class="btn-delete">Delete</button>
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
    $('#tasksTable').DataTable({
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
                title: 'Task Schedule'
            },
            {
                extend: 'print',
                title: 'Task Schedule',
                customize: function (win) {
                    $(win.document.body).css('font-size', '10pt');
                    $(win.document.body).find('table').addClass('compact').css('font-size', '10pt');
                    $(win.document.body).find('table').css('width', '100%');
                }
            }
        ],
        order: [[0, 'asc']],
        autoWidth: false
    });
});
</script>
@endsection
