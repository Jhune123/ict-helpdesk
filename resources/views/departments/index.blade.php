@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow rounded-lg p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">🏢 Departments</h2>

        <form action="{{ route('departments.store') }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text"
                   name="name"
                   placeholder="New Department"
                   class="border rounded px-3 py-1"
                   required>
            <button class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">
                Add
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2 text-left">Department Name</th>
                <th class="border px-4 py-2 text-center w-32">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($departments as $department)
            <tr class="hover:bg-gray-50">
                <td class="border px-4 py-2">
                    {{ $department->name }}
                </td>
                <td class="border px-4 py-2 text-center">
                    <form action="{{ route('departments.destroy', $department) }}"
                          method="POST"
                          onsubmit="return confirm('Delete this department?');">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:underline">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="text-center py-4 text-gray-500">
                    No departments found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
