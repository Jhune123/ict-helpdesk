@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">💼 ICTO Asset & Equipment Analytics</h1>

    <!-- 🖨️ Print Button -->
    <div class="mb-6">
        <button onclick="window.print()"
                class="bg-gray-700 text-white font-semibold px-5 py-2 rounded-lg shadow hover:bg-gray-800 transition">
            🖨️ Print Asset Summary
        </button>
    </div>

    <!-- 📊 Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10 print:grid-cols-2 print:gap-4">
        <div class="bg-blue-100 p-5 rounded-2xl text-center shadow-sm">
            <h2 class="text-lg font-semibold text-blue-700">Total Assets</h2>
            <p class="text-4xl font-extrabold text-blue-900 mt-2">{{ $totalAssets }}</p>
        </div>

        <div class="bg-green-100 p-5 rounded-2xl text-center shadow-sm">
            <h2 class="text-lg font-semibold text-green-700">Available</h2>
            <p class="text-4xl font-extrabold text-green-900 mt-2">{{ $availableAssets }}</p>
        </div>

        <div class="bg-yellow-100 p-5 rounded-2xl text-center shadow-sm">
            <h2 class="text-lg font-semibold text-yellow-700">In Use</h2>
            <p class="text-4xl font-extrabold text-yellow-900 mt-2">{{ $inUseAssets }}</p>
        </div>

        <div class="bg-red-100 p-5 rounded-2xl text-center shadow-sm">
            <h2 class="text-lg font-semibold text-red-700">Damaged</h2>
            <p class="text-4xl font-extrabold text-red-900 mt-2">{{ $damagedAssets }}</p>
        </div>
    </div>

    <!-- 📈 Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        <!-- Assets by Category -->
        <div class="bg-white p-4 rounded-2xl shadow-md">
            <h2 class="text-lg font-bold text-gray-700 mb-3">Assets by Category</h2>
            <canvas id="assetsByCategoryChart" height="150"></canvas>
        </div>

        <!-- Assets by Department -->
        <div class="bg-white p-4 rounded-2xl shadow-md">
            <h2 class="text-lg font-bold text-gray-700 mb-3">Assets by Department</h2>
            <canvas id="assetsByDepartmentChart" height="150"></canvas>
        </div>
    </div>

    <!-- 📋 Assets Table -->
    <div class="bg-white rounded-2xl shadow-md p-5">
        <h2 class="text-lg font-bold text-gray-700 mb-4">📋 All Registered Assets</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 rounded-lg text-sm">
                <thead class="bg-gray-100">
                    <tr class="text-left">
                        <th class="py-2 px-3 border-b">#</th>
                        <th class="py-2 px-3 border-b">Asset Code</th>
                        <th class="py-2 px-3 border-b">Name</th>
                        <th class="py-2 px-3 border-b">Category</th>
                        <th class="py-2 px-3 border-b">Department</th>
                        <th class="py-2 px-3 border-b">Status</th>
                        <th class="py-2 px-3 border-b">Purchase Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($allAssets as $asset)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-3 border-b">{{ $loop->iteration }}</td>
                            <td class="py-2 px-3 border-b font-semibold">{{ $asset->asset_code }}</td>
                            <td class="py-2 px-3 border-b font-semibold">{{ $asset->name }}</td>
                            <td class="py-2 px-3 border-b">{{ $asset->category }}</td>
                            <td class="py-2 px-3 border-b">{{ $asset->department }}</td>
                            <td class="py-2 px-3 border-b capitalize">
                                <span class="@if($asset->status == 'Available') text-green-600 
                                            @elseif($asset->status == 'In Use') text-yellow-600 
                                            @else text-red-600 @endif font-semibold">
                                    {{ $asset->status }}
                                </span>
                            </td>
                            <td class="py-2 px-3 border-b">{{ $asset->purchase_date?->format('Y-m-d') ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">No assets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 📊 Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const categoryCtx = document.getElementById('assetsByCategoryChart');
    if (categoryCtx) {
        new Chart(categoryCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($assetsByCategory->keys()) !!},
                datasets: [{
                    data: {!! json_encode($assetsByCategory->values()) !!},
                    backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6'],
                    borderWidth: 1,
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }

    const deptCtx = document.getElementById('assetsByDepartmentChart');
    if (deptCtx) {
        new Chart(deptCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($assetsByDepartment->keys()) !!},
                datasets: [{
                    label: 'Number of Assets',
                    data: {!! json_encode($assetsByDepartment->values()) !!},
                    backgroundColor: '#3b82f6',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    }
});
</script>

<!-- 🖨️ Print Formatting -->
<style>
@media print {
    body * { visibility: hidden; }
    .p-6, .p-6 * { visibility: visible; }
    .p-6 { position: absolute; top: 0; left: 0; width: 100%; }
    button { display: none !important; }
}
</style>
@endsection
