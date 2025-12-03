@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Categories</h2>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    @role('admin|it_staff')
        <a href="{{ route('categories.create') }}" 
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
            + Add Category
        </a>
    @endrole

    <div class="overflow-x-auto mt-6">
        <table class="w-full border border-gray-300 bg-white shadow-sm rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2 text-left w-16">#</th>
                    <th class="border px-3 py-2 text-left">Name</th>
                    <th class="border px-3 py-2 text-left">Description</th>
                    <th class="border px-3 py-2 text-center w-40">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-3 py-2">{{ $loop->iteration }}</td>
                        <td class="border px-3 py-2 font-semibold">{{ $category->name }}</td>
                        <td class="border px-3 py-2 text-gray-700">
                            {{ $category->description ?? '—' }}
                        </td>

                        <td class="border px-3 py-2 text-center">
                            @role('admin|it_staff')
                                <a href="{{ route('categories.edit', $category->id) }}" 
                                   class="text-blue-600 hover:underline">
                                    Edit
                                </a>

                                <span class="mx-1">|</span>

                                <form action="{{ route('categories.destroy', $category->id) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:underline"
                                            onclick="return confirm('Delete this category?')">
                                        Delete
                                    </button>
                                </form>
                            @endrole
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">
                            No categories found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
