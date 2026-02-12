@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">
        
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Maintenance Schedule Details
            </h3>
            <div class="flex space-x-2">
                @hasanyrole('admin|it_staff')
                <a href="{{ route('maintenance.edit', $maintenance->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-bold">Edit</a>
                @endhasanyrole

                <a href="{{ route('maintenance.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-bold">Back to List</a>
            </div>
        </div>

        <div class="px-6 py-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Title</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-bold">{{ $maintenance->title }}</dd>
                </div>

                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Office / College</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $maintenance->office_college }}</dd>
                </div>

                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Device Model</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $maintenance->device_model ?? 'N/A' }}</dd>
                </div>

                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Property Number</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $maintenance->property_number ?? 'N/A' }}</dd>
                </div>

                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Serial Number</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $maintenance->serial_number ?? 'N/A' }}</dd>
                </div>

                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Frequency</dt>
                    <dd class="mt-1 text-sm text-gray-900 capitalize">
                        {{ $maintenance->frequency === 'semi-annual' ? 'Semi-Annual (6 Months)' : $maintenance->frequency }}
                    </dd>
                </div>

                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Next Run Date</dt>
                    <dd class="mt-1 text-sm text-blue-600 font-bold">
                        {{ $maintenance->next_run_date ? $maintenance->next_run_date->format('M d, Y') : 'N/A' }}
                    </dd>
                </div>

                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Priority</dt>
                    <dd class="mt-1">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $maintenance->priority == 'Critical' ? 'bg-red-100 text-red-800' : 
                               ($maintenance->priority == 'High' ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800') }}">
                            {{ $maintenance->priority }}
                        </span>
                    </dd>
                </div>

                {{-- Updated Assigned Staff Section --}}
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 mb-2">Assigned IT Personnel</dt>
                    <dd class="mt-1 flex flex-wrap gap-2">
                        @forelse($maintenance->assignees as $staff)
                            <div class="inline-flex items-center px-3 py-1.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm font-semibold">{{ $staff->name }}</span>
                            </div>
                        @empty
                            <span class="text-sm text-gray-400 italic">No staff assigned to this schedule.</span>
                        @endforelse
                    </dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Description / Checklist</dt>
                    <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-4 rounded border border-gray-200 leading-relaxed">
                        {!! nl2br(e($maintenance->description)) !!}
                    </dd>
                </div>

            </dl>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-right text-xs text-gray-400">
            Last Updated: {{ $maintenance->updated_at->format('M d, Y h:i A') }}
        </div>
    </div>
</div>
@endsection