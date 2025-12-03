<?php $__env->startSection('content'); ?>
<div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-2xl p-6">
        <h2 class="text-2xl font-bold mb-6">🏢 Tickets by Department</h2>

        
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

        
        <?php if($tickets->isEmpty()): ?>
            <div class="p-4 bg-yellow-100 text-yellow-800 rounded-lg">
                No tickets found 🚫
            </div>
        <?php else: ?>
            <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department => $deptTickets): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-10">
                    <h3 class="text-xl font-semibold text-indigo-700 mb-4">
                        <?php echo e($department ?? 'Unspecified Department'); ?>

                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded-lg shadow-sm departmentTable">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2">🎫 Title</th>
                                    <th class="px-4 py-2">📝 Description</th>
                                    <th class="px-4 py-2">📊 Status</th>
                                    <th class="px-4 py-2">⭐ Priority</th>
                                    <th class="px-4 py-2">👤 Client</th>
                                    <th class="px-4 py-2">🧑‍💻 IT Personnel</th>
                                    <th class="px-4 py-2">📅 Submitted</th>
                                    <th class="px-4 py-2">✅ Finished</th>
                                    <th class="px-4 py-2">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $deptTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="px-4 py-2 font-semibold"><?php echo e($ticket->title); ?></td>
                                        <td class="px-4 py-2"><?php echo e($ticket->description ?? '-'); ?></td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 rounded text-white
                                                <?php echo e($ticket->status == 'Open' ? 'bg-red-500' : ''); ?>

                                                <?php echo e($ticket->status == 'In Progress' ? 'bg-yellow-500' : ''); ?>

                                                <?php echo e($ticket->status == 'Closed' ? 'bg-green-500' : ''); ?>">
                                                <?php echo e($ticket->status); ?>

                                            </span>
                                        </td>
                                        <td class="px-4 py-2"><?php echo e($ticket->priority ?? 'Normal'); ?></td>
                                        <td class="px-4 py-2"><?php echo e($ticket->client_name); ?></td>
                                        <td class="px-4 py-2"><?php echo e($ticket->assignee_name); ?></td>
                                        <td class="px-4 py-2"><?php echo e($ticket->date_submitted?->format('M d, Y')); ?></td>
                                        <td class="px-4 py-2"><?php echo e($ticket->date_finished?->format('M d, Y') ?? '-'); ?></td>
                                        <td class="px-4 py-2"><?php echo e($ticket->remarks ?? '-'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>


<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $('.departmentTable').DataTable({
            responsive: true,
            pageLength: 10,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'csv', text: 'CSV', className: 'bg-blue-600 text-white px-3 py-1 rounded mx-1' },
                { extend: 'excel', text: 'Excel', className: 'bg-green-600 text-white px-3 py-1 rounded mx-1' },
                { extend: 'pdf', text: 'PDF', className: 'bg-red-600 text-white px-3 py-1 rounded mx-1' },
                { extend: 'print', text: 'Print', className: 'bg-gray-700 text-white px-3 py-1 rounded mx-1' }
            ]
        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ict_helpdesk\resources\views/tickets/departments.blade.php ENDPATH**/ ?>