@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Maintenance Schedule</h2>

        {{-- Form action uses $maintenance->id to match the controller update($id) --}}
        <form action="{{ route('maintenance.update', $maintenance->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Maintenance Title</label>
                    <input type="text" name="title" value="{{ old('title', $maintenance->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Office / College</label>
                    <input type="text" name="office_college" value="{{ old('office_college', $maintenance->office_college) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Device / Model</label>
                    <input type="text" name="device_model" value="{{ old('device_model', $maintenance->device_model) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Property Number</label>
                    <input type="text" name="property_number" value="{{ old('property_number', $maintenance->property_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Serial Number</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number', $maintenance->serial_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Frequency</label>
                    <select name="frequency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach(['daily', 'weekly', 'monthly', 'quarterly', 'semi-annual', 'yearly'] as $freq)
                            <option value="{{ $freq }}" {{ $maintenance->frequency == $freq ? 'selected' : '' }}>
                                {{ $freq === 'semi-annual' ? 'Semi-Annual (6 Months)' : ucfirst($freq) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Run (Date Performed)</label>
                    <input type="date" name="last_run_date" value="{{ old('last_run_date', $maintenance->last_run_date ? $maintenance->last_run_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <p class="text-xs text-gray-500 mt-1">Changing this will automatically update the Next Run date.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 text-gray-400">Next Run (Calculated)</label>
                    <input type="date" value="{{ $maintenance->next_run_date ? $maintenance->next_run_date->format('Y-m-d') : '' }}" class="mt-1 block w-full rounded-md border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed shadow-sm" disabled>
                    <p class="text-xs text-blue-500 mt-1 italic">Calculated automatically by the system.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Priority</label>
                    <select name="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach(['Low', 'Normal', 'High', 'Critical'] as $priority)
                            <option value="{{ $priority }}" {{ $maintenance->priority == $priority ? 'selected' : '' }}>{{ $priority }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Assigned Staff (Select multiple if needed)</label>
                    <select name="assigned_to[]" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 min-h-[100px]">
                        @foreach($staff as $user)
                            <option value="{{ $user->id }}" 
                                @if(in_array($user->id, old('assigned_to', $selectedStaff ?? []))) selected @endif>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1 italic">Hold Ctrl (Win) or Cmd (Mac) to select multiple staff.</p>
                </div>

            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700">Description / Checklist</label>
                <textarea name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('description', $maintenance->description) }}</textarea>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('maintenance.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 shadow-sm transition">
                    Update Schedule
                </button>
            </div>
        </form>
    </div>
</div>
@endsection