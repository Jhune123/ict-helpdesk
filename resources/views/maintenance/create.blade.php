@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Create New Maintenance Schedule</h2>

        <form action="{{ route('maintenance.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Maintenance Title --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Maintenance Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g., Monthly Server Cleanup" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                {{-- Office / College --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Office / College</label>
                    <input type="text" name="office_college" value="{{ old('office_college') }}" placeholder="e.g., College of Engineering" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                {{-- Device / Model --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Device / Model</label>
                    <input type="text" name="device_model" value="{{ old('device_model') }}" placeholder="e.g., Dell PowerEdge R740" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                {{-- Property Number --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Property Number</label>
                    <input type="text" name="property_number" value="{{ old('property_number') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                {{-- Serial Number --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Serial Number</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                {{-- Frequency --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Frequency</label>
                    <select name="frequency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach(['daily', 'weekly', 'monthly', 'quarterly', 'semi-annual', 'yearly'] as $freq)
                            <option value="{{ $freq }}" {{ old('frequency') == $freq ? 'selected' : '' }}>
                                {{ $freq === 'semi-annual' ? 'Semi-Annual (6 Months)' : ucfirst($freq) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Last Run (Date Performed) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Run (Date Performed)</label>
                    <input type="date" name="last_run_date" value="{{ old('last_run_date', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <p class="text-xs text-gray-500 mt-1">The system will use this to set the first Next Run date.</p>
                </div>

                {{-- Priority --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Priority</label>
                    <select name="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach(['Low', 'Normal', 'High', 'Critical'] as $priority)
                            <option value="{{ $priority }}" {{ old('priority', 'Normal') == $priority ? 'selected' : '' }}>{{ $priority }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Multi-Staff Assignment --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Assigned Staff (Select one or more)</label>
                    <select name="assigned_to[]" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 min-h-[120px]">
                        @foreach($staff as $user)
                            <option value="{{ $user->id }}" {{ (is_array(old('assigned_to')) && in_array($user->id, old('assigned_to'))) ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1 italic">
                        Tip: Hold <strong>Ctrl</strong> (Windows) or <strong>Command</strong> (Mac) to select multiple staff members.
                    </p>
                </div>

            </div>

            {{-- Description / Checklist --}}
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700">Description / Maintenance Checklist</label>
                <textarea name="description" rows="4" placeholder="List the specific tasks to be performed..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('description') }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('maintenance.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 shadow-sm transition">
                    Create Schedule
                </button>
            </div>
        </form>
    </div>
</div>
@endsection