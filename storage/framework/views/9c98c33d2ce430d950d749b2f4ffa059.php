<?php $__env->startSection('styles'); ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
    table.dataTable thead {
        background-color: #1E40AF; /* Blue header */
        color: #fff;
    }
    table.dataTable tbody tr:hover {
        background-color: #E0F2FE; /* Light blue hover */
    }
    .upcoming-task {
        background-color: #DCFCE7 !important; /* Light green */
    }
    .overdue-task {
        background-color: #FEE2E2 !important; /* Light red */
    }
    table.dataTable td .btn {
        white-space: nowrap;
        margin-right: 2px;
    }
    .table-responsive {
        overflow-x: auto;
    }
    /* Force DataTables column sizing */
    table.dataTable {
        table-layout: fixed;
        width: 100% !important;
    }
    table.dataTable td, table.dataTable th {
        word-wrap: break-word;
        vertical-align: middle;
    }
    /* Print adjustments */
    @media print {
        table.dataTable {
            width: 100% !important;
        }
        .dataTables_wrapper .dt-buttons {
            display: none; /* hide buttons on print */
        }
        table.dataTable th, table.dataTable td {
            font-size: 10pt; /* smaller font to fit more columns */
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-700">🗓 Task Schedule</h2>

        <div class="flex gap-2">
            <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin|it_staff')): ?>
            <a href="<?php echo e(route('tasks.create')); ?>" 
               class="inline-block px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition">
                + Add Task
            </a>
            <?php endif; ?>

            <!-- Export PDF Button -->
            <a href="<?php echo e(route('tasks.export.pdf')); ?>" target="_blank"
               class="inline-block px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg shadow hover:bg-green-700 transition">
                Export PDF
            </a>
        </div>
    </div>

    <div class="table-responsive bg-white shadow rounded-lg">
        <table id="tasksTable" class="display nowrap stripe hover" style="width:100%">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Requested By</th>
                    <th>Location</th>
                    <th>Time Range</th>
                    <th>IT Personnel</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $today = \Carbon\Carbon::today();
                    $taskDate = \Carbon\Carbon::parse($task->date);
                    $isUpcoming = $taskDate->between($today, $today->copy()->addDays(7));
                    $isOverdue = $taskDate->lt($today);
                ?>
                <tr class="<?php echo e($isUpcoming ? 'upcoming-task' : ($isOverdue ? 'overdue-task' : '')); ?>">
                    <td><?php echo e($taskDate->format('M d, Y')); ?></td>
                    <td><?php echo e($task->description); ?></td>
                    <td><?php echo e($task->requested_by); ?></td>
                    <td><?php echo e($task->location); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($task->start_time)->format('h:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($task->end_time)->format('h:i A')); ?></td>
                    <td><?php echo e($task->assigned_to ?? 'N/A'); ?></td>
                    <td><?php echo e($task->remarks); ?></td>
                    <td>
                        <a href="<?php echo e(route('tasks.show', $task)); ?>" class="btn-view">View</a>
                        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin|it_staff')): ?>
                        <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="btn-edit">Edit</a>
                        <form action="<?php echo e(route('tasks.destroy', $task)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button onclick="return confirm('Delete this task?')" class="btn-delete">Delete</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    $('#tasksTable').DataTable({
        responsive: true,
        scrollX: true,
        paging: true,
        searching: true,
        ordering: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel',
            {
                extend: 'pdf',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Task Schedule'
            },
            {
                extend: 'print',
                title: 'Task Schedule',
                customize: function (win) {
                    $(win.document.body).css('font-size', '10pt');
                    $(win.document.body).find('table').addClass('compact').css('font-size', '10pt');
                    $(win.document.body).find('table').css('width', '100%');
                }
            }
        ],
        order: [[0, 'asc']],
        autoWidth: false
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ict_helpdesk\resources\views/tasks/index.blade.php ENDPATH**/ ?>