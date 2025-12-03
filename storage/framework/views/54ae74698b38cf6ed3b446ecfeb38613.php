<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto bg-white shadow rounded p-6">
    <h2 class="text-xl font-bold mb-4">Task Details</h2>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-gray-600 font-medium">Date</p>
            <p class="text-lg"><?php echo e(\Carbon\Carbon::parse($task->date)->format('F d, Y')); ?></p>
        </div>

        <div>
            <p class="text-gray-600 font-medium">Time</p>
            <p class="text-lg">
                <?php echo e(\Carbon\Carbon::parse($task->start_time)->format('h:i A')); ?> 
                - 
                <?php echo e(\Carbon\Carbon::parse($task->end_time)->format('h:i A')); ?>

            </p>
        </div>

        <div class="col-span-2">
            <p class="text-gray-600 font-medium">Description</p>
            <p class="text-lg"><?php echo e($task->description); ?></p>
        </div>

        <div>
            <p class="text-gray-600 font-medium">Requested By</p>
            <p class="text-lg"><?php echo e($task->requested_by); ?></p>
        </div>

        <div>
            <p class="text-gray-600 font-medium">Location</p>
            <p class="text-lg"><?php echo e($task->location); ?></p>
        </div>

        <div class="col-span-2">
            <p class="text-gray-600 font-medium">Remarks</p>
            <p class="text-lg"><?php echo e($task->remarks ?? '—'); ?></p>
        </div>
    </div>

    <div class="flex justify-end mt-6 space-x-2">
        <a href="<?php echo e(route('tasks.index')); ?>" class="px-4 py-2 bg-gray-300 rounded">Back</a>

        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin|it_staff')): ?>
            <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Edit</a>
            <form action="<?php echo e(route('tasks.destroy', $task)); ?>" method="POST" class="inline">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button onclick="return confirm('Are you sure you want to delete this task?')" 
                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                    Delete
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ict_helpdesk\resources\views/tasks/show.blade.php ENDPATH**/ ?>