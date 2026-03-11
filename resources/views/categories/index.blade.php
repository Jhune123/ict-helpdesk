@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Categories</h2>
        @role('admin|it_staff')
            <a href="{{ route('categories.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow transition">
                + Add Category
            </a>
        @endrole
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-700 border border-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 bg-white shadow-sm rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-300 px-4 py-3 text-left w-16">#</th>
                    <th class="border border-gray-300 px-4 py-3 text-left">Name</th>
                    <th class="border border-gray-300 px-4 py-3 text-left">Description</th>
                    <th class="border border-gray-300 px-4 py-3 text-center w-48">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($categories as $category)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">{{ $category->name }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-gray-700 text-sm">
                            {{ $category->description ?? '—' }}
                        </td>

                        <td class="border border-gray-300 px-4 py-2 text-center">
                            @role('admin|it_staff')
                                <div class="flex justify-center items-center gap-3">
                                    <a href="{{ route('categories.edit', $category->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        Edit
                                    </a>

                                    <span class="text-gray-300">|</span>

                                    <form action="{{ route('categories.destroy', $category->id) }}" 
                                          method="POST" 
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 font-medium"
                                                onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            @endrole
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-gray-500 italic">
                            No categories found in the system.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection