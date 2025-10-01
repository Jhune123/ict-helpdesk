@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">Add New Category</h2>

    <form action="{{ route('categories.store') }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf

        <div class="mb-4">
            <label for="name" class="block font-medium mb-1">Category Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded px-3 py-2" required>
            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block font-medium mb-1">Description</label>
            <textarea name="description" id="description" rows="3" class="w-full border-gray-300 rounded px-3 py-2">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">Save</button>
            <a href="{{ route('categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow">Cancel</a>
        </div>
    </form>
</div>
@endsection
