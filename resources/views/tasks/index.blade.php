@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
    table.dataTable thead {
        background-color: #1E40AF;
        color: #fff;
    }
    table.dataTable tbody tr:hover {
        background-color: #E0F2FE;
    }
    .upcoming-task {
        background-color: #DCFCE7 !important;
    }
    .overdue-task {
        background-color: #FEE2E2 !important;
    }
    table.dataTable td .btn {
        white-space: nowrap;
        margin-right: 4px;
    }
    .table-responsive {
        overflow-x: auto;
    }
    table.dataTable {
        table-layout: fixed;
        width: 100% !important;
    }
    table.dataTable th,
    table.dataTable td {
        word-wrap: break-word;
        vertical-align: middle;
    }
    .wrap-text {
        white-space: normal !important;
        word-break: break-word;
    }
    @media print {
        .dataTables_wrapper .dt-buttons,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_length {
            display: none;
        }
        table.dataTable th,
        table.dataTable td {
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
            {{-- 🔒 Only Admin/IT Staff can Add Tasks --}}
            @role('admin|it_staff')
            <a href="{{ route('tasks.create') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition">
                + Add Task
            </a>
            @endrole

            <a href="{{ route('tasks.export.pdf') }}" target="_blank"
               class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg shadow hover:bg-green-700 transition">
                Export PDF
            </a>
        </div>
    </div>

    <div class="table-responsive bg-white shadow rounded-lg p-2">
        <table id="tasksTable" class="display nowrap stripe hover w-full">
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

                    $rowClass = $taskDate->lt($today)
                        ? 'overdue-task'
                        : ($taskDate->between($today, $today->copy()->addDays(7)) ? 'upcoming-task' : '');
                @endphp

                <tr class="{{ $rowClass }}">
                    <td data-order="{{ $taskDate->format('Y-m-d') }}">
                        {{ $taskDate->format('M d, Y') }}
                    </td>

                    <td class="wrap-text">{{ $task->description }}</td>
                    <td class="wrap-text">{{ $task->requested_by }}</td>
                    <td class="wrap-text">{{ $task->department->name ?? '—' }}</td>
                    <td>{{ $task->location }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($task->start_time)->format('h:i A') }}
                        –
                        {{ \Carbon\Carbon::parse($task->end_time)->format('h:i A') }}
                    </td>
                    <td>{{ $task->assigned_to ?? 'N/A' }}</td>
                    <td class="wrap-text">{{ $task->remarks ?? '—' }}</td>
                    <td>
                        <div class="flex flex-nowrap gap-1">
                            {{-- Visible to All --}}
                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm bg-cyan-500 text-white px-2 py-1 rounded shadow hover:bg-cyan-600 transition">View</a>

                            {{-- Ticket Buttons Logic --}}
                            @if($task->ticket_id)
                                <a href="{{ route('tickets.show', $task->ticket_id) }}" 
                                   class="btn btn-sm bg-indigo-600 text-white px-2 py-1 rounded shadow hover:bg-indigo-700 transition" 
                                   title="View Linked Ticket">
                                    🎟️ View Ticket
                                </a>
                            @else
                                <a href="{{ route('tickets.create', ['task_id' => $task->id]) }}" 
                                   class="btn btn-sm bg-green-500 text-white px-2 py-1 rounded shadow hover:bg-green-600 transition" 
                                   title="Create Ticket from Task">
                                    ➕ Create Ticket
                                </a>
                            @endif

                            {{-- 🔒 Visible Only to Admin/IT Staff --}}
                            @role('admin|it_staff')
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm bg-yellow-500 text-white px-2 py-1 rounded shadow hover:bg-yellow-600 transition">Edit</a>

                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm bg-red-500 text-white px-2 py-1 rounded shadow hover:bg-red-600 transition delete-btn">
                                    Delete
                                </button>
                            </form>
                            @endrole
                        </div>
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
    // Initialize DataTable
    var table = $('#tasksTable').DataTable({
        responsive: true,
        scrollX: true,
        pageLength: 15,
        lengthMenu: [5, 10, 15, 25, 50],
        order: [[0, 'desc']], 
        autoWidth: false,
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
                title: 'Task Schedule'
            }
        ]
    });

    /**
     * ✅ FIX: SOLVING DOUBLE CLICK VIA EVENT DELEGATION
     */
    $(document).on('click', '.btn', function(e) {
        if ($(this).is('a')) {
            return true; 
        }
    });

    // Handle Delete button specifically
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this task?')) {
            $(this).closest('form').submit();
        }
    });
});
</script>
@endsection