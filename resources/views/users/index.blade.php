@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<style>
    table.dataTable thead {
        background-color: #1E40AF;
        color: #fff;
    }
    table.dataTable tbody tr:hover {
        background-color: #E0F2FE;
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
        padding: 12px;
    }
</style>
@endsection

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-700">👥 User Management</h2>

        {{-- 🔒 Only Admin & IT Staff can see the Add User button --}}
        @hasanyrole('admin|it_staff')
        <a href="{{ route('users.create') }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition">
            + Add User
        </a>
        @endhasanyrole
    </div>

    <div class="table-responsive bg-white shadow rounded-lg p-2">
        <table id="usersTable" class="display nowrap stripe hover w-full">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Date Registered</th>
                    {{-- 🔒 Actions Column Header --}}
                    @hasanyrole('admin|it_staff')
                    <th data-orderable="false">Actions</th>
                    @endhasanyrole
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="font-medium text-gray-800">{{ $user->name }}</td>
                    <td class="text-gray-600">{{ $user->email }}</td>
                    <td>
                        @if(method_exists($user, 'roles') && $user->roles->count() > 0)
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">
                                {{ $user->roles->pluck('name')->map('ucfirst')->implode(', ') }}
                            </span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                User
                            </span>
                        @endif
                    </td>
                    <td data-order="{{ $user->created_at->format('Y-m-d H:i:s') }}">
                        {{ $user->created_at->format('M d, Y - h:i A') }}
                    </td>
                    
                    {{-- 🔒 Only Admin & IT Staff can see the Edit/Delete buttons --}}
                    @hasanyrole('admin|it_staff')
                    <td>
                        <div class="flex flex-nowrap gap-1">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm bg-yellow-500 text-white px-2 py-1 rounded shadow hover:bg-yellow-600 transition">
                                Edit
                            </a>

                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm bg-red-500 text-white px-2 py-1 rounded shadow hover:bg-red-600 transition delete-btn">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                    @endhasanyrole
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

<script>
$(document).ready(function () {
    $('#usersTable').DataTable({
        responsive: true,
        pageLength: 15,
        lengthMenu: [10, 15, 25, 50],
        order: [[3, 'desc']], // Sorts by Date Registered by default
        autoWidth: false
    });

    // Handle Delete button alert
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this user? This cannot be undone!')) {
            $(this).closest('form').submit();
        }
    });
});
</script>
@endsection