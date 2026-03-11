@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-2xl">
        <h2 class="text-xl font-bold mb-4">Edit Category</h2>

        <form action="{{ route('categories.update', $category->id) }}" 
              method="POST" 
              class="bg-white p-6 rounded shadow-md border border-gray-200">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block font-medium mb-1">Category Name</label>
                <input type="text" name="name" id="name"
                       value="{{ old('name', $category->name) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" 
                       required>
                @error('name') 
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block font-medium mb-1">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('description', $category->description) }}</textarea>
                @error('description') 
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" 
                           {{ old('is_active', $category->is_active) ? 'checked' : '' }} 
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-2 text-gray-700 font-medium">Active (Visible to Users)</span>
                </label>
                <p class="text-xs text-gray-500 mt-1 italic">Inactive categories will be hidden from the request dropdowns.</p>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow transition-colors font-semibold">
                    Update Category
                </button>
                <a href="{{ route('categories.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded shadow transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection