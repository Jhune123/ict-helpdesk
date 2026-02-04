@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-2xl p-6">

        <h2 class="text-2xl font-bold mb-4">🏢 Tickets by Department</h2>

        <!-- 🔍 DEPARTMENT SEARCH ONLY -->
        <div class="mb-6">
            <input
                type="text"
                id="departmentSearch"
                placeholder="Search department name..."
                class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-indigo-300"
            >
        </div>

        {{-- DataTables CSS --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

        @if($tickets->isEmpty())
            <div class="p-4 bg-yellow-100 text-yellow-800 rounded-lg">
                No tickets found 🚫
            </div>
        @else

            @foreach($tickets as $department => $deptTickets)
                <!-- 🏢 DEPARTMENT WRAPPER -->
                <div class="mb-10 department-block" data-department="{{ strtolower($department ?? 'unspecified') }}">

                    <h3 class="text-xl font-semibold text-indigo-700 mb-4">
                        {{ $department ?? 'Unspecified Department' }}
                        <span class="text-sm text-gray-500">
                            ({{ $deptTickets->count() }})
                        </span>
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded-lg shadow-sm departmentTable">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th>🎫 Title</th>
                                    <th>📝 Description</th>
                                    <th>📊 Status</th>
                                    <th>⭐ Priority</th>
                                    <th>👤 Client</th>
                                    <th>🧑‍💻 IT Personnel</th>
                                    <th>📅 Submitted</th>
                                    <th>✅ Finished</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deptTickets as $ticket)
                                    <tr>
                                        <td class="font-semibold">{{ $ticket->title }}</td>
                                        <td>{{ $ticket->description ?? '-' }}</td>
                                        <td>
                                            <span class="px-2 py-1 rounded text-white
                                                {{ $ticket->status == 'Open' ? 'bg-red-500' : '' }}
                                                {{ $ticket->status == 'In Progress' ? 'bg-yellow-500' : '' }}
                                                {{ $ticket->status == 'Closed' ? 'bg-green-500' : '' }}">
                                                {{ $ticket->status }}
                                            </span>
                                        </td>
                                        <td>{{ $ticket->priority ?? 'Normal' }}</td>
                                        <td>{{ $ticket->client_name }}</td>
                                        <td>{{ optional($ticket->assignee)->name ?? '-' }}</td>
                                        <td>{{ $ticket->date_submitted?->format('M d, Y') }}</td>
                                        <td>{{ $ticket->date_finished?->format('M d, Y') ?? '-' }}</td>
                                        <td>{{ $ticket->remarks ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            @endforeach
        @endif

    </div>
</div>

{{-- jQuery + DataTables --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

{{-- DataTables Buttons --}}
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function () {

        // Init DataTables PER department (ticket-level search stays here)
        $('.departmentTable').DataTable({
            responsive: true,
            pageLength: 10,
            dom: 'Bfrtip',
            buttons: ['csv', 'excel', 'pdf', 'print']
        });

        // 🔍 DEPARTMENT SEARCH ONLY
        $('#departmentSearch').on('keyup', function () {
            let keyword = $(this).val().toLowerCase();

            $('.department-block').each(function () {
                let dept = $(this).data('department');
                $(this).toggle(dept.includes(keyword));
            });
        });

    });
</script>
@endsection
