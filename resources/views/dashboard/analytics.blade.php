@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

    <!-- TITLE -->
    <div class="mb-6 text-center">
        <h1 class="text-3xl font-bold text-gray-800">📊 Analytics Dashboard</h1>
        <p class="text-gray-500">Real-time ICTO Helpdesk System Overview</p>
    </div>

    <!-- KPI CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <!-- Total Tickets -->
        <div class="p-6 bg-white shadow-md rounded-xl border-l-4 border-blue-500">
            <h3 class="text-gray-500 uppercase text-sm">Total Tickets</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $totalTickets }}</p>
        </div>

        <!-- Open Tickets -->
        <div class="p-6 bg-white shadow-md rounded-xl border-l-4 border-yellow-500">
            <h3 class="text-gray-500 uppercase text-sm">Open Tickets</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $openTickets }}</p>
        </div>

        <!-- Closed Tickets -->
        <div class="p-6 bg-white shadow-md rounded-xl border-l-4 border-green-500">
            <h3 class="text-gray-500 uppercase text-sm">Closed Tickets</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $closedTickets }}</p>
        </div>

        <!-- Total Assets -->
        <div class="p-6 bg-white shadow-md rounded-xl border-l-4 border-purple-600">
            <h3 class="text-gray-500 uppercase text-sm">Total Assets</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $totalAssets }}</p>
        </div>

    </div>

    <!-- MAIN CHARTS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">

        <!-- Monthly Tickets -->
        <div class="p-6 bg-white shadow-md rounded-xl">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">📅 Monthly Ticket Trend</h3>
            <canvas id="monthlyChart"></canvas>
        </div>

        <!-- Tickets by Department -->
        <div class="p-6 bg-white shadow-md rounded-xl">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">🏫 Tickets per Department</h3>
            <canvas id="departmentChart"></canvas>
        </div>
    </div>

    <!-- CATEGORY + IT PERSONNEL -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">

        <!-- Tickets per Category -->
        <div class="p-6 bg-white shadow-md rounded-xl">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">🗂️ Ticket Categories</h3>
            <canvas id="categoryChart"></canvas>
        </div>

        <!-- Assigned Tickets per IT Staff -->
        <div class="p-6 bg-white shadow-md rounded-xl">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">👨‍💻 IT Personnel Performance</h3>
            <canvas id="personnelChart"></canvas>
        </div>

    </div>

    <!-- Recent Activity Timeline -->
    <div class="mt-10 bg-white p-6 rounded-xl shadow-md">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">🕒 Recent Activities</h3>

        @forelse ($recentActivities as $activity)
            <div class="border-l-4 border-blue-500 pl-4 mb-4">
                <p class="font-bold text-gray-800">{{ $activity->title }}</p>
                <p class="text-gray-600 text-sm">{{ $activity->created_at->diffForHumans() }}</p>
            </div>
        @empty
            <p class="text-gray-500">No recent activity.</p>
        @endforelse
    </div>

</div>

<!-- CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    /* MONTHLY TREND */
    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyLabels) !!},
            datasets: [{
                label: 'Tickets per Month',
                data: {!! json_encode($monthlyData) !!},
                borderWidth: 2
            }]
        }
    });

    /* DEPARTMENT CHART */
    new Chart(document.getElementById('departmentChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($ticketsPerDepartment)) !!},
            datasets: [{
                label: 'Tickets',
                data: {!! json_encode(array_values($ticketsPerDepartment)) !!},
                borderWidth: 1
            }]
        }
    });

    /* CATEGORY CHART */
    new Chart(document.getElementById('categoryChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode(array_keys($ticketsPerCategory)) !!},
            datasets: [{
                data: {!! json_encode(array_values($ticketsPerCategory)) !!}
            }]
        }
    });

    /* PERSONNEL PERFORMANCE */
    new Chart(document.getElementById('personnelChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($ticketsPerPersonnel)) !!},
            datasets: [{
                label: 'Assigned Tickets',
                data: {!! json_encode(array_values($ticketsPerPersonnel)) !!},
                borderWidth: 1
            }]
        }
    });
</script>
@endsection
