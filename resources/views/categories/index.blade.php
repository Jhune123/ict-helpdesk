@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Categories</h2>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('categories.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">+ Add Category</a>

    <div class="overflow-x-auto mt-6">
        <table class="w-full border border-gray-300 bg-white shadow-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2 text-left">#</th>
                    <th class="border px-3 py-2 text-left">Name</th>
                    <th class="border px-3 py-2 text-left">Description</th>
                    <th class="border px-3 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-3 py-2">{{ $loop->iteration }}</td>
                        <td class="border px-3 py-2">{{ $category->name }}</td>
                        <td class="border px-3 py-2">{{ $category->description }}</td>
                        <td class="border px-3 py-2 text-center">
                            <a href="{{ route('categories.edit', $category->id) }}" class="text-blue-600 hover:underline">Edit</a>
                            <span class="mx-1">|</span>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this category?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">No categories yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
