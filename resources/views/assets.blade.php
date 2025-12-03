@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">💼 ICTO Asset & Equipment Analytics</h1>

    <!-- Print Button -->
    <div class="mb-6">
        <button onclick="window.print()"
                class="bg-gray-700 text-white font-semibold px-5 py-2 rounded-lg shadow hover:bg-gray-800 transition">
            🖨️ Print Asset Summary
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-blue-100 p-5 rounded-2xl text-center shadow-sm transform hover:scale-105 transition">
            <h2 class="text-lg font-semibold text-blue-700">Total Assets</h2>
            <p class="text-4xl font-extrabold text-blue-900 mt-2">{{ $totalAssets }}</p>
        </div>

        <div class="bg-green-100 p-5 rounded-2xl text-center shadow-sm transform hover:scale-105 transition">
            <h2 class="text-lg font-semibold text-green-700">Asset Types</h2>
            <p class="text-4xl font-extrabold text-green-900 mt-2">{{ count($assetsByType) }}</p>
        </div>

        <div class="bg-yellow-100 p-5 rounded-2xl text-center shadow-sm transform hover:scale-105 transition">
            <h2 class="text-lg font-semibold text-yellow-700">Departments</h2>
            <p class="text-4xl font-extrabold text-yellow-900 mt-2">{{ count($assetsByDepartment) }}</p>
        </div>

        <div class="bg-purple-100 p-5 rounded-2xl text-center shadow-sm transform hover:scale-105 transition">
            <h2 class="text-lg font-semibold text-purple-700">Status Types</h2>
            <p class="text-4xl font-extrabold text-purple-900 mt-2">{{ count($assetsByStatus) }}</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Assets by Type -->
        <div class="bg-white p-5 rounded-2xl shadow-md">
            <h2 class="text-lg font-bold text-gray-700 mb-3">Assets by Type</h2>
            <canvas id="assetsByTypeChart" height="150"></canvas>
        </div>

        <!-- Assets by Department -->
        <div class="bg-white p-5 rounded-2xl shadow-md">
            <h2 class="text-lg font-bold text-gray-700 mb-3">Assets by Department</h2>
            <canvas id="assetsByDepartmentChart" height="150"></canvas>
        </div>

        <!-- Assets by Status -->
        <div class="bg-white p-5 rounded-2xl shadow-md lg:col-span-2">
            <h2 class="text-lg font-bold text-gray-700 mb-3">Assets by Status</h2>
            <canvas id="assetsByStatusChart" height="150"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Assets by Type Chart
    const typeCtx = document.getElementById('assetsByTypeChart');
    if (typeCtx) {
        new Chart(typeCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($assetsByType->keys()) !!},
                datasets: [{
                    data: {!! json_encode($assetsByType->values()) !!},
                    backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316','#64748b'],
                    borderWidth: 1,
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }

    // Assets by Department Chart
    const deptCtx = document.getElementById('assetsByDepartmentChart');
    if (deptCtx) {
        new Chart(deptCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($assetsByDepartment->keys()) !!},
                datasets: [{
                    label: 'Count',
                    data: {!! json_encode($assetsByDepartment->values()) !!},
                    backgroundColor: '#6366f1',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    }

    // Assets by Status Chart
    const statusCtx = document.getElementById('assetsByStatusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($assetsByStatus->keys()) !!},
                datasets: [{
                    data: {!! json_encode($assetsByStatus->values()) !!},
                    backgroundColor: ['#10b981','#f59e0b','#ef4444','#3b82f6','#8b5cf6','#ec4899'],
                    borderWidth: 1,
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }
});
</script>

<!-- Print Styling -->
<style>
@media print {
    body * { visibility: hidden; }
    .p-6, .p-6 * { visibility: visible; }
    .p-6 { position: absolute; top: 0; left: 0; width: 100%; }
    button { display: none; }
}
</style>
@endsection
