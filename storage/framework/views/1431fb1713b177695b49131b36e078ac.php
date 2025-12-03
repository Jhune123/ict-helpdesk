<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto py-8">

    <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-200">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">⭐ Feedback Details</h2>

        <div class="space-y-4 text-gray-800">

            <p><strong>Ticket #:</strong> <?php echo e($feedback->ticket_id); ?></p>

            <p><strong>Client Name:</strong> <?php echo e($feedback->client_name); ?></p>

            <p><strong>Rating:</strong> <?php echo e($feedback->rating); ?> ⭐</p>

            <p><strong>Comments:</strong></p>
            <p class="bg-gray-50 p-3 rounded-lg border"><?php echo e($feedback->comments ?? 'No comments'); ?></p>

            <p><strong>Submitted On:</strong> 
                <?php echo e($feedback->created_at->timezone('Asia/Manila')->format('F d, Y h:i A')); ?>

            </p>
        </div>

        <div class="mt-6">
            <a href="<?php echo e(route('feedbacks.index')); ?>"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                ⬅ Back to Feedback List
            </a>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ict_helpdesk\resources\views/feedbacks/show.blade.php ENDPATH**/ ?>