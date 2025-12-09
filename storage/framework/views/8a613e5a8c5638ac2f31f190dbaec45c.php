<?php $__env->startSection('content'); ?>
<div class="py-6 max-w-6xl mx-auto sm:px-6 lg:px-8">

    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-200">
        <h2 class="text-2xl font-bold mb-6 text-gray-900">
            ⭐ Client Feedbacks
        </h2>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Ticket #</th>
                        <th class="border px-4 py-2">Client</th>
                        <th class="border px-4 py-2">Rating</th>
                        <th class="border px-4 py-2">Comments</th>
                        <th class="border px-4 py-2">Date</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $feedbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2"><?php echo e($fb->id); ?></td>
                            <td class="border px-4 py-2">#<?php echo e($fb->ticket_id); ?></td>
                            <td class="border px-4 py-2"><?php echo e($fb->client_name); ?></td>
                            <td class="border px-4 py-2"><?php echo e($fb->rating); ?> ⭐</td>
                            <td class="border px-4 py-2"><?php echo e($fb->comments ?? '—'); ?></td>
                            <td class="border px-4 py-2">
                                <?php echo e($fb->created_at->timezone('Asia/Manila')->format('F d, Y h:i A')); ?>

                            </td>

                            <td class="border px-4 py-2 flex gap-2">
                                <a href="<?php echo e(route('feedbacks.show', $fb->id)); ?>"
                                   class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    👁 View
                                </a>

                                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin')): ?>
                                    <form action="<?php echo e(route('feedbacks.destroy', $fb->id)); ?>" method="POST"
                                          onsubmit="return confirm('Delete this feedback?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                            🗑 Delete
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">No feedbacks yet.</td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <?php echo e($feedbacks->links()); ?>

        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ict_helpdesk\resources\views/feedbacks/index.blade.php ENDPATH**/ ?>