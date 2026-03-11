@extends('layouts.app')

@section('content')
<div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">

    <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-200">
        <h2 class="text-2xl font-bold mb-6 text-gray-900">🎫 Ticket Details</h2>

        <div class="space-y-3 text-gray-800">
            <div><span class="font-semibold">Title:</span> <span class="ml-2">{{ $ticket->title }}</span></div>
            <div>
                <span class="font-semibold">Description:</span>
                <p class="ml-2 text-gray-600">{{ $ticket->description ?? 'N/A' }}</p>
            </div>
            <div><span class="font-semibold">Status:</span> <span class="ml-2">{{ $ticket->status }}</span></div>
            <div><span class="font-semibold">Category:</span> <span class="ml-2">{{ $ticket->categoryName }}</span></div>
            <div><span class="font-semibold">Department:</span> <span class="ml-2">{{ $ticket->department ?? 'N/A' }}</span></div>
            <div><span class="font-semibold">IT Personnel:</span> <span class="ml-2">{{ $ticket->assigneeName ?? 'Unassigned' }}</span></div>
            <div>
                <span class="font-semibold">Remarks:</span>
                <p class="ml-2 text-gray-600">{{ $ticket->remarks ?? 'N/A' }}</p>
            </div>
            <div>
                <span class="font-semibold">Date Created:</span>
                <span class="ml-2">{{ $ticket->created_at->timezone('Asia/Manila')->format('F d, Y h:i A') }}</span>
            </div>
            @if(in_array($ticket->status, ['Closed', 'Condemned']) && $ticket->date_finished)
            <div>
                <span class="font-semibold">Date Finished:</span>
                <span class="ml-2">{{ \Carbon\Carbon::parse($ticket->date_finished)->timezone('Asia/Manila')->format('F d, Y h:i A') }}</span>
            </div>
            @endif
        </div>

        {{-- ✅ NETWORK REQUEST SECTION --}}
        @if($ticket->networkRequest)
        <div class="mt-8 bg-white shadow-sm overflow-hidden sm:rounded-lg border border-green-200 border-l-4 border-l-green-600 mb-2">
            <div class="px-4 py-5 bg-green-50 sm:px-6 flex justify-between items-center border-b border-green-200">
                <div>
                    <h3 class="text-lg leading-6 font-extrabold text-green-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        Network Request Details
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-green-700 font-semibold tracking-wide uppercase">
                        KSU-ICTO-QF-04 Official Form Data
                    </p>
                </div>
            </div>
            
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Type of Request</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-bold text-lg">
                            {{ $ticket->networkRequest->request_type === 'Others' ? $ticket->networkRequest->request_type_others : $ticket->networkRequest->request_type }}
                        </dd>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                        <dt class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Office / Location</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="font-bold">{{ $ticket->networkRequest->office }}</span>
                            @if($ticket->networkRequest->location)
                                <span class="text-gray-400 mx-2">|</span> 
                                <span class="text-gray-700">{{ $ticket->networkRequest->location }}</span>
                            @endif
                        </dd>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Device & MAC Address</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="font-medium text-gray-800">
                                🖥️ {{ $ticket->networkRequest->device === 'Others' ? $ticket->networkRequest->device_others : $ticket->networkRequest->device }}
                            </span>
                            <br>
                            <div class="mt-2 inline-flex items-center px-2.5 py-1.5 rounded-md text-xs font-mono font-bold bg-gray-800 text-green-400 shadow-inner">
                                MAC: {{ $ticket->networkRequest->mac_address ?: 'Not Provided' }}
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        @endif

        {{-- ✅ FEEDBACK SECTION (RE-ADDED) --}}
        @if($ticket->feedback)
        <div class="mt-6 bg-pink-50 border border-pink-200 rounded-xl p-5">
            <h3 class="font-bold text-pink-900 flex items-center gap-2 mb-3">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                Customer Feedback
            </h3>
            <div class="flex items-center mb-2">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-6 h-6 {{ $i <= $ticket->feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                @endfor
                <span class="ml-2 text-sm font-bold text-pink-800">({{ $ticket->feedback->rating }}/5)</span>
            </div>
            <p class="text-gray-700 italic border-l-2 border-pink-300 pl-3">"{{ $ticket->feedback->comments ?? 'No written comments provided.' }}"</p>
            <p class="mt-2 text-xs text-gray-500">Submitted on: {{ $ticket->feedback->created_at->format('M d, Y h:i A') }}</p>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <h3 class="font-semibold text-gray-900 mb-4 border-b pb-2">🖥 Equipment Information</h3>
                <div class="space-y-2 text-gray-800 text-sm">
                    <div class="flex justify-between"><span class="font-semibold text-gray-600">Type:</span> <span>{{ $ticket->equipment_type ?? 'N/A' }}</span></div>
                    <div class="flex justify-between"><span class="font-semibold text-gray-600">Brand/Model:</span> <span>{{ $ticket->brand_model ?? 'N/A' }}</span></div>
                    <div class="flex justify-between"><span class="font-semibold text-gray-600">Serial No:</span> <span>{{ $ticket->serial_no ?? 'N/A' }}</span></div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <h3 class="font-semibold text-gray-900 mb-4 border-b pb-2">👤 Client Information</h3>
                <div class="space-y-2 text-gray-800 text-sm">
                    <div class="flex justify-between"><span class="font-semibold text-gray-600">Client Name:</span> <span>{{ $ticket->client_name }}</span></div>
                    <div class="flex justify-between"><span class="font-semibold text-gray-600">Contact:</span> <span>{{ $ticket->contact_number ?? 'N/A' }}</span></div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-between items-center border-t pt-6">
            <a href="{{ route('tickets.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition-colors rounded-lg border border-gray-300">⬅ Back to List</a>
            <div class="flex gap-3">
                @role('admin|it_staff')
                <a href="{{ route('tickets.edit', $ticket->id) }}" class="px-5 py-2.5 bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors rounded-lg shadow-sm">✏ Edit Ticket</a>
                @endrole
                @if($ticket->status === 'Closed' && !$ticket->feedback)
                    <a href="{{ route('feedbacks.create', $ticket->id) }}" class="px-5 py-2.5 bg-pink-500 text-white font-semibold hover:bg-pink-600 transition-colors rounded-lg shadow-sm">📝 Submit Feedback</a>
                @elseif($ticket->feedback)
                    <span class="text-green-600 font-bold self-center flex items-center gap-1 bg-green-50 px-4 py-2 rounded-lg border border-green-200">Feedback Submitted ✅</span>
                @endif
            </div>
        </div>
    </div>

    {{-- COMMENTS SECTION --}}
    <div class="bg-white shadow-xl rounded-2xl p-6 mt-8 border border-gray-200">
        <h3 class="text-xl font-bold mb-6 text-gray-800 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            Discussion / Updates
        </h3>
        
        <div class="space-y-5 mb-8">
            @forelse($ticket->comments as $comment)
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 shadow-sm relative group">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <strong class="text-gray-900">{{ $comment->user->name ?? 'Unknown User' }}</strong>
                            <span class="text-xs text-gray-500 ml-2 bg-white px-2 py-0.5 rounded border border-gray-200">{{ $comment->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</span>
                        </div>
                        
                        @if(auth()->id() === $comment->user_id || auth()->user()->hasRole('admin'))
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 p-1 rounded transition-colors opacity-0 group-hover:opacity-100 focus:opacity-100" title="Delete Comment">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        @endif
                    </div>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $comment->message }}</p>
                </div>
            @empty
                <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    <p class="text-gray-500 font-medium">No comments or updates yet.</p>
                    <p class="text-gray-400 text-sm mt-1">Be the first to leave a message.</p>
                </div>
            @endforelse
        </div>

        <form action="{{ route('tickets.comments.store', $ticket->id) }}" method="POST" class="mt-6 border-t border-gray-200 pt-6">
            @csrf
            <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">Leave an update or reply</label>
            <textarea id="message" name="message" rows="3" required class="w-full border border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-xl p-4 shadow-sm resize-y" placeholder="Type your message here..."></textarea>
            <div class="text-right mt-4">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white hover:bg-blue-700 font-bold rounded-xl shadow-md transition-all active:scale-95 flex items-center gap-2 inline-flex">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Post Message
                </button>
            </div>
        </form>
    </div>
</div>
@endsection