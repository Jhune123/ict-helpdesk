<?php $__env->startSection('content'); ?>
<div class="py-6 max-w-6xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-2xl p-6">
        <h2 class="text-2xl font-bold mb-6">🏢 Tickets by Department</h2>

        <?php if($tickets->isEmpty()): ?>
            <div class="p-4 bg-yellow-100 text-yellow-800 rounded-lg">
                No tickets found 🚫
            </div>
        <?php else: ?>
            <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department => $deptTickets): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-indigo-700 mb-4">
                        <?php echo e($department ?? 'Unspecified Department'); ?>

                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded-lg shadow-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">🎫 Title</th>
                                    <th class="px-4 py-2 text-left">📝 Description</th>
                                    <th class="px-4 py-2 text-left">📊 Status</th>
                                    <th class="px-4 py-2 text-left">⭐ Priority</th>
                                    <th class="px-4 py-2 text-left">👤 Client</th>
                                    <th class="px-4 py-2 text-left">🧑‍💻 IT Personnel</th>
                                    <th class="px-4 py-2 text-left">📅 Submitted</th>
                                    <th class="px-4 py-2 text-left">✅ Finished</th>
                                    <th class="px-4 py-2 text-left">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $deptTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-t hover:bg-gray-50">
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ict-helpdesk\resources\views/tickets/departments.blade.php ENDPATH**/ ?>