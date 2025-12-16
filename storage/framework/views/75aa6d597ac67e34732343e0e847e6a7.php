<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-6 py-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            📜 System Activity Logs
        </h2>

        <span class="text-sm text-gray-500">
            Visible to Admin & IT Staff only
        </span>
    </div>

    <!-- Activity Logs Table -->
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Action</th>
                    <th class="px-4 py-3">Subject</th>
                    <th class="px-4 py-3">Subject ID</th>
                    <th class="px-4 py-3">Description</th>
                    <th class="px-4 py-3">Date & Time</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-800">
                            <?php echo e($log->id); ?>

                        </td>

                        <td class="px-4 py-3">
                            <?php echo e($log->user->name ?? 'System'); ?>

                        </td>

                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                <?php echo e($log->action); ?>

                            </span>
                        </td>

                        <td class="px-4 py-3">
                            <?php
                                $subjectTitle = '—';
                                $subjectLink = '#';

                                if($log->subject_type === \App\Models\Ticket::class) {
                                    $ticket = \App\Models\Ticket::find($log->subject_id);
                                    if($ticket) {
                                        $subjectTitle = $ticket->title;
                                        $subjectLink = route('tickets.show', $ticket->id);
                                    }
                                }
                            ?>

                            <?php if($subjectTitle !== '—'): ?>
                                <a href="<?php echo e($subjectLink); ?>" class="text-blue-600 hover:underline">
                                    <?php echo e($subjectTitle); ?>

                                </a>
                            <?php else: ?>
                                <?php echo e($subjectTitle); ?>

                            <?php endif; ?>
                        </td>

                        <td class="px-4 py-3">
                            <?php echo e($log->subject_id ? '#' . $log->subject_id : '—'); ?>

                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            <?php echo e($log->description ?? '—'); ?>

                        </td>

                        <td class="px-4 py-3 text-gray-500">
                            <?php echo e($log->created_at->format('M d, Y h:i A')); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                            No activity logs found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        <?php echo e($logs->links()); ?>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ict_helpdesk\resources\views/activity_logs/index.blade.php ENDPATH**/ ?>