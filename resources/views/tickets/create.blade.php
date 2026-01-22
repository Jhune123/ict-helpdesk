@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">➕ Create New Ticket</h2>

    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf

        <!-- Title -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Title</label>
            <input type="text" name="title" value="{{ old('title') }}"
                   class="w-full border rounded-lg p-2" required>
            @error('title')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Description</label>
            <textarea name="description" rows="4"
                      class="w-full border rounded-lg p-2"
                      required>{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Priority -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Priority</label>
            <select name="priority" class="w-full border rounded-lg p-2">
                @php $priorities = ['Low', 'Normal', 'High', 'Urgent']; @endphp
                @foreach($priorities as $priority)
                    <option value="{{ $priority }}" {{ old('priority') == $priority ? 'selected' : '' }}>
                        {{ $priority }}
                    </option>
                @endforeach
            </select>
            @error('priority')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Category -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Category</label>
            <select name="category_id" class="w-full border rounded-lg p-2">
                <option value="">-- Select Category --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            <input type="text" name="category_manual" placeholder="Or enter new category"
                   value="{{ old('category_manual') }}" class="w-full border rounded-lg p-2 mt-2">
            @error('category_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            @error('category_manual') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Department -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Department</label>
            <div class="flex items-center gap-2">
                <select id="department-select" name="department" class="w-full border rounded-lg p-2">
                    <option value="">-- Select Department --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->name }}" {{ old('department') == $dept->name ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
                <button type="button" id="add-department-btn"
                        class="bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700">
                    + Add
                </button>
            </div>
        </div>

        <!-- Add Department Modal -->
        <div id="add-department-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                <h3 class="text-lg font-bold mb-4">Add New Department</h3>
                <input type="text" id="new-department-name" placeholder="Department Name"
                       class="w-full border rounded-lg p-2 mb-4">
                <div class="flex justify-end gap-2">
                    <button type="button" id="cancel-department-btn"
                            class="px-4 py-2 rounded-lg border hover:bg-gray-100">Cancel</button>
                    <button type="button" id="save-department-btn"
                            class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">Save</button>
                </div>
            </div>
        </div>

        <!-- Assign To -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Assign To (IT Personnel)</label>
            <select name="assigned_to" class="w-full border rounded-lg p-2">
                <option value="">-- Select IT Personnel --</option>
                @foreach($it_personnel as $person)
                    <option value="{{ $person->id }}" {{ old('assigned_to') == $person->id ? 'selected' : '' }}>
                        {{ $person->name }}
                    </option>
                @endforeach
            </select>
            @error('assigned_to') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Client Name -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Client Name</label>
            <input type="text" name="client_name" value="{{ old('client_name') }}"
                   class="w-full border rounded-lg p-2" required>
            @error('client_name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Contact Number -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Contact Number</label>
            <input type="text" name="contact_number" value="{{ old('contact_number') }}"
                   class="w-full border rounded-lg p-2">
            @error('contact_number') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Remarks -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Remarks</label>
            <textarea name="remarks" rows="3" class="w-full border rounded-lg p-2">{{ old('remarks') }}</textarea>
            @error('remarks') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Create Ticket
        </button>
    </form>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('add-department-modal');
    const addBtn = document.getElementById('add-department-btn');
    const cancelBtn = document.getElementById('cancel-department-btn');
    const saveBtn = document.getElementById('save-department-btn');
    const newDepartmentInput = document.getElementById('new-department-name');
    const departmentSelect = document.getElementById('department-select');

    // Open modal
    addBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        newDepartmentInput.focus();
    });

    // Close modal
    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        newDepartmentInput.value = '';
    });

    // Save department via AJAX
    saveBtn.addEventListener('click', () => {
        const name = newDepartmentInput.value.trim();
        if (!name) {
            alert('Please enter department name.');
            return;
        }

        fetch("{{ route('departments.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: name })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // Add new option to dropdown
                const option = document.createElement('option');
                option.value = data.department.name;
                option.textContent = data.department.name;
                option.selected = true;
                departmentSelect.appendChild(option);

                modal.classList.add('hidden');
                modal.classList.remove('flex');
                newDepartmentInput.value = '';
            } else {
                alert(data.message || 'Error adding department');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error adding department');
        });
    });
});
</script>
@endsection
