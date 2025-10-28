<?php $__env->startSection('content'); ?>
<div class="p-6">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-700 mb-6">Task Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600 font-semibold">📅 Date:</p>
                <p class="text-gray-800"><?php echo e(\Carbon\Carbon::parse($task->date)->format('F d, Y')); ?></p>
            </div>

            <div>
                <p class="text-gray-600 font-semibold">👷‍♂️ IT Personnel:</p>
                <p class="text-gray-800">
                    <?php echo e($task->assigned_to ? $task->assigned_to : 'Not Assigned'); ?>

                </p>
            </div>

            <div>
                <p class="text-gray-600 font-semibold">🕒 Start Time:</p>
                <p class="text-gray-800">
                    <?php echo e($task->start_time ? \Carbon\Carbon::parse($task->start_time)->format('h:i A') : '—'); ?>

                </p>
            </div>

            <div>
                <p class="text-gray-600 font-semibold">⏰ End Time:</p>
                <p class="text-gray-800">
                    <?php echo e($task->end_time ? \Carbon\Carbon::parse($task->end_time)->format('h:i A') : '—'); ?>

                </p>
            </div>

            <div class="md:col-span-2">
                <p class="text-gray-600 font-semibold">📝 Description:</p>
                <p class="text-gray-800"><?php echo e($task->description); ?></p>
            </div>

            <div>
                <p class="text-gray-600 font-semibold">📍 Location:</p>
                <p class="text-gray-800"><?php echo e($task->location); ?></p>
            </div>

            <div>
                <p class="text-gray-600 font-semibold">🙋 Requested By:</p>
                <p class="text-gray-800"><?php echo e($task->requested_by); ?></p>
            </div>

            <div class="md:col-span-2">
                <p class="text-gray-600 font-semibold">💬 Remarks:</p>
                <p class="text-gray-800">
                    <?php echo e($task->remarks ? $task->remarks : 'No remarks provided'); ?>

                </p>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <a href="<?php echo e(route('tasks.index')); ?>" 
               class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg">
                ← Back
            </a>

            <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin|it_staff')): ?>
            <a href="<?php echo e(route('tasks.edit', $task->id)); ?>" 
               class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg">
                ✏️ Edit
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ict_helpdesk\resources\views/tasks/show.blade.php ENDPATH**/ ?>