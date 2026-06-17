@extends('layouts.app')

@section('content')
<div class="py-6 max-w-6xl mx-auto sm:px-6 lg:px-8">

    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-200">
        
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-gray-100 pb-5">
            
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    ⭐ Client Satisfaction Survey (CSS)
                </h2>
                <p class="text-sm text-gray-500 mt-1">Manage client feedbacks and export monthly/yearly summary reports.</p>
            </div>

            <form action="{{ route('reports.css') }}" method="GET" target="_blank" class="flex flex-wrap items-center gap-3 bg-gray-50 p-3 rounded-lg border border-gray-100">
                
                <div class="flex items-center gap-2">
                    <label for="year" class="text-xs font-bold text-gray-600 uppercase">Year:</label>
                    <select name="year" id="year" class="form-select text-sm rounded-md border-gray-300 py-1.5 focus:ring-indigo-500 focus:border-indigo-500 text-gray-800">
                        @for ($y = date('Y'); $y >= 2024; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label for="month" class="text-xs font-bold text-gray-600 uppercase">Month:</label>
                    <select name="month" id="month" class="form-select text-sm rounded-md border-gray-300 py-1.5 focus:ring-indigo-500 focus:border-indigo-500 text-gray-800">
                        <option value="">-- Entire Year --</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="bg-indigo-600 text-white px-4 py-1.5 rounded-md text-sm font-bold hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
                    🖨️ Generate Report
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left text-sm font-semibold text-gray-700">ID</th>
                        <th class="border px-4 py-2 text-left text-sm font-semibold text-gray-700">Ticket #</th>
                        <th class="border px-4 py-2 text-left text-sm font-semibold text-gray-700">Client</th>
                        <th class="border px-4 py-2 text-left text-sm font-semibold text-gray-700">Office Visited</th>
                        <th class="border px-4 py-2 text-left text-sm font-semibold text-gray-700">Rating</th>
                        <th class="border px-4 py-2 text-left text-sm font-semibold text-gray-700">Comments/Suggestions</th>
                        <th class="border px-4 py-2 text-left text-sm font-semibold text-gray-700">Date</th>
                        <th class="border px-4 py-2 text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse($feedbacks as $fb)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border px-4 py-2 text-sm text-gray-800">{{ $fb->id }}</td>
                            <td class="border px-4 py-2 text-sm font-medium text-blue-600">#{{ $fb->ticket_id }}</td>
                            <td class="border px-4 py-2 text-sm text-gray-800">{{ $fb->client_name ?? 'Anonymous' }}</td>
                            <td class="border px-4 py-2 text-sm text-gray-800">{{ $fb->office_visited ?? '—' }}</td>
                            <td class="border px-4 py-2 text-sm font-bold text-yellow-500">{{ $fb->sqd0 ?? $fb->rating ?? '0' }} ⭐</td>
                            <td class="border px-4 py-2 text-sm text-gray-600">{{ $fb->suggestions ?? $fb->comments ?? '—' }}</td>
                            <td class="border px-4 py-2 text-sm text-gray-500">
                                {{ $fb->created_at->timezone('Asia/Manila')->format('F d, Y h:i A') }}
                            </td>

                            <td class="border px-4 py-2">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('feedbacks.show', $fb->id) }}"
                                       class="px-3 py-1 bg-green-600 text-white rounded-md text-xs font-semibold hover:bg-green-700 shadow-sm">
                                       👁 View
                                    </a>

                                    @hasanyrole('admin|it_staff')
                                        <a href="{{ route('feedbacks.edit', $fb->id) }}"
                                           class="px-3 py-1 bg-blue-600 text-white rounded-md text-xs font-semibold hover:bg-blue-700 shadow-sm">
                                           ✏️ Edit
                                        </a>

                                        <form action="{{ route('feedbacks.destroy', $fb->id) }}" method="POST"
                                              onsubmit="return confirm('Delete this feedback?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-1 bg-red-600 text-white rounded-md text-xs font-semibold hover:bg-red-700 shadow-sm">
                                                🗑 Delete
                                            </button>
                                        </form>
                                    @endhasanyrole
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-6 text-gray-500 italic">No feedbacks yet.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $feedbacks->links() }}
        </div>

    </div>

</div>
@endsection