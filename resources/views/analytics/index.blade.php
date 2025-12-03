@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto">

    <h1 class="text-3xl font-bold mb-6">📊 Analytics Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- STATUS CHART --}}
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-2">Tickets by Status</h2>
            <canvas id="statusChart"></canvas>
        </div>

        {{-- DEPARTMENT CHART --}}
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-2">Tickets by Department</h2>
            <canvas id="departmentChart"></canvas>
        </div>

        {{-- CATEGORY CHART --}}
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-2">Tickets by Category</h2>
            <canvas id="categoryChart"></canvas>
        </div>

        {{-- MONTHLY CHART --}}
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-2">Monthly Ticket Trends</h2>
            <canvas id="monthlyChart"></canvas>
        </div>

        {{-- IT STAFF WORKLOAD --}}
        <div class="bg-white p-6 rounded-xl shadow col-span-1 md:col-span-2">
            <h2 class="text-lg font-semibold mb-2">IT Personnel Workload</h2>
            <canvas id="workloadChart"></canvas>
        </div>
    </div>
</div>

{{-- CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // STATUS
    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: @json(array_keys($statusCounts->toArray())),
            datasets: [{
                data: @json(array_values($statusCounts->toArray())),
            }]
        }
    });

    // DEPARTMENT
    new Chart(document.getElementById('departmentChart'), {
        type: 'bar',
        data: {
            labels: @json(array_keys($departmentCounts->toArray())),
            datasets: [{
                data: @json(array_values($departmentCounts->toArray())),
            }]
        }
    });

    // CATEGORY
    new Chart(document.getElementById('categoryChart'), {
        type: 'bar',
        data: {
            labels: @json(array_keys($categoryCounts->toArray())),
            datasets: [{
                data: @json(array_values($categoryCounts->toArray())),
            }]
        }
    });

    // MONTHLY
    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: @json(array_keys($monthlyCounts->toArray())),
            datasets: [{
                data: @json(array_values($monthlyCounts->toArray())),
                fill: false,
            }]
        }
    });

    // WORKLOAD
    new Chart(document.getElementById('workloadChart'), {
        type: 'bar',
        data: {
            labels: @json(array_keys($itWorkload->toArray())),
            datasets: [{
                data: @json(array_values($itWorkload->toArray())),
            }]
        }
    });
</script>
@endsection
