@extends('layouts.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
<style>
    #calendar {
        max-width: 1000px;
        margin: 0 auto;
    }
    .fc-event {
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="max-w-full mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold text-green-700 mb-4">📆 Meeting Calendar</h2>

    <div id="calendar"></div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Debug: Check events from controller
    console.log(@json($events));

    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        events: @json($events),
        eventClick: function(info) {
            info.jsEvent.preventDefault(); // prevent default
            if (info.event.url) {
                window.location.href = info.event.url; // open meeting details
            }
        },
        height: 'auto',
        editable: false,
        selectable: false,
        dayMaxEvents: true, // show "+n more" if too many events
    });

    calendar.render();
});
</script>
@endsection
