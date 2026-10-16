@extends('layouts.app')

@section('header')
    {{-- FullCalendar Core & Plugins CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
    <style>
        #calendar {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        .fc-event { cursor: pointer; }
        .fc-daygrid-day.meeting-scheduled .fc-daygrid-day-frame {
            background-color: #ecfdf5;
        }
        .fc-daygrid-day.meeting-scheduled .fc-daygrid-day-number {
            color: #fff;
            background-color: #16a34a;
            border-radius: 9999px;
            margin: 3px;
            padding: 2px 7px;
        }
        .meeting-event-content,
        .meeting-event-title,
        .meeting-event-remarks {
            display: block;
            min-width: 0;
            white-space: normal;
            overflow-wrap: anywhere;
            word-break: break-word;
        }
        .meeting-event-title { font-weight: 600; }
        .meeting-event-remarks { font-size: 0.85em; opacity: 0.95; }
    </style>
@endsection

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">📅 Meeting Schedule Calendar</h2>
        <a href="{{ route('meetings.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            List View
        </a>
    </div>

    <div id="calendar"></div>
</div>

{{-- FullCalendar Library JS --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        const meetingDates = @json($meetingDates);
        
        // Ensure FullCalendar is loaded before initializing
        if (typeof FullCalendar !== 'undefined') {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listMonth'
                },
                events: @json($events),
                dayCellDidMount: function(info) {
                    const year = info.date.getFullYear();
                    const month = String(info.date.getMonth() + 1).padStart(2, '0');
                    const day = String(info.date.getDate()).padStart(2, '0');
                    const dateKey = `${year}-${month}-${day}`;
                    if (meetingDates.includes(dateKey)) {
                        info.el.classList.add('meeting-scheduled');
                    }
                },
                eventContent: function(arg) {
                    const content = document.createElement('div');
                    content.className = 'meeting-event-content';

                    if (arg.timeText) {
                        const time = document.createElement('span');
                        time.textContent = arg.timeText + ' ';
                        content.appendChild(time);
                    }

                    const title = document.createElement('span');
                    title.className = 'meeting-event-title';
                    title.textContent = arg.event.title;
                    content.appendChild(title);

                    const remarks = arg.event.extendedProps.remarks;
                    if (remarks) {
                        const remarksElement = document.createElement('span');
                        remarksElement.className = 'meeting-event-remarks';
                        remarksElement.textContent = 'Remarks: ' + remarks;
                        content.appendChild(remarksElement);
                    }

                    return { domNodes: [content] };
                },
                eventClick: function(info) {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        info.jsEvent.preventDefault();
                    }
                },
                eventMouseEnter: function(info) {
                    info.el.style.opacity = '0.8';
                },
                eventMouseLeave: function(info) {
                    info.el.style.opacity = '1';
                }
            });
            calendar.render();
        } else {
            console.error("FullCalendar failed to load. Please check your internet connection or CDN links.");
        }
    });
</script>
@endsection