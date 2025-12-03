<?php $__env->startSection('content'); ?>
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
            <p class="text-3xl font-bold text-gray-800"><?php echo e($totalTickets); ?></p>
        </div>

        <!-- Open Tickets -->
        <div class="p-6 bg-white shadow-md rounded-xl border-l-4 border-yellow-500">
            <h3 class="text-gray-500 uppercase text-sm">Open Tickets</h3>
            <p class="text-3xl font-bold text-gray-800"><?php echo e($openTickets); ?></p>
        </div>

        <!-- Closed Tickets -->
        <div class="p-6 bg-white shadow-md rounded-xl border-l-4 border-green-500">
            <h3 class="text-gray-500 uppercase text-sm">Closed Tickets</h3>
            <p class="text-3xl font-bold text-gray-800"><?php echo e($closedTickets); ?></p>
        </div>

        <!-- Total Assets -->
        <div class="p-6 bg-white shadow-md rounded-xl border-l-4 border-purple-600">
            <h3 class="text-gray-500 uppercase text-sm">Total Assets</h3>
            <p class="text-3xl font-bold text-gray-800"><?php echo e($totalAssets); ?></p>
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

        <?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="border-l-4 border-blue-500 pl-4 mb-4">
                <p class="font-bold text-gray-800"><?php echo e($activity->title); ?></p>
                <p class="text-gray-600 text-sm"><?php echo e($activity->created_at->diffForHumans()); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-gray-500">No recent activity.</p>
        <?php endif; ?>
    </div>

</div>

<!-- CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    /* MONTHLY TREND */
    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($monthlyLabels); ?>,
            datasets: [{
                label: 'Tickets per Month',
                data: <?php echo json_encode($monthlyData); ?>,
                borderWidth: 2
            }]
        }
    });

    /* DEPARTMENT CHART */
    new Chart(document.getElementById('departmentChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_keys($ticketsPerDepartment)); ?>,
            datasets: [{
                label: 'Tickets',
                data: <?php echo json_encode(array_values($ticketsPerDepartment)); ?>,
                borderWidth: 1
            }]
        }
    });

    /* CATEGORY CHART */
    new Chart(document.getElementById('categoryChart'), {
        type: 'pie',
        data: {
            labels: <?php echo json_encode(array_keys($ticketsPerCategory)); ?>,
            datasets: [{
                data: <?php echo json_encode(array_values($ticketsPerCategory)); ?>

            }]
        }
    });

    /* PERSONNEL PERFORMANCE */
    new Chart(document.getElementById('personnelChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_keys($ticketsPerPersonnel)); ?>,
            datasets: [{
                label: 'Assigned Tickets',
                data: <?php echo json_encode(array_values($ticketsPerPersonnel)); ?>,
                borderWidth: 1
            }]
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ict_helpdesk\resources\views/dashboard/analytics.blade.php ENDPATH**/ ?>