@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold mb-6">All Notifications</h2>

    @if($notifications->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul>
                @foreach($notifications as $notification)
                    <li class="border-b">
                        <div class="px-4 py-4 flex justify-between items-center {{ is_null($notification->read_at) ? 'bg-gray-100' : '' }}">
                            <div>
                                <p class="text-sm">{{ $notification->data['message'] ?? 'New Notification' }}</p>
                                <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if(is_null($notification->read_at))
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:underline text-sm">Mark as Read</button>
                                    </form>
                                @else
                                    <form action="{{ route('notifications.markAsUnread', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-yellow-600 hover:underline text-sm">Mark as Unread</button>
                                    </form>
                                @endif
                                <a href="{{ route('tickets.show', $notification->data['ticket_id']) }}" class="text-gray-700 hover:underline text-sm">View</a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @else
        <p class="text-gray-500">No notifications found.</p>
    @endif
</div>
@endsection
