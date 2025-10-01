<?php $__env->startSection('content'); ?>
<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-700">Task Schedule</h2>
        <a href="<?php echo e(route('tasks.create')); ?>" 
           class="inline-block px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition">
            + Add Task
        </a>
    </div>

<!-- 🔍 Search Bar -->
<form method="GET" action="<?php echo e(route('tasks.index')); ?>" class="mb-4">
    <div class="flex gap-2">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
               placeholder="Search by description, requested by, or location..."
               class="flex-1 px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400">
        <button type="submit" 
                class="px-4 py-2 bg-yellow-500 text-black text-sm font-medium rounded-lg shadow hover:bg-yellow-600">
            🔍 Search
        </button>
    </div>
</form>

        </div>
    </form>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full border border-gray-200 text-sm">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="p-3 border">Date</th>
                    <th class="p-3 border">Description</th>
                    <th class="p-3 border">Requested By</th>
                    <th class="p-3 border">Location</th>
                    <th class="p-3 border">Time Range</th>
                    <th class="p-3 border">IT Personnel</th>
                    <th class="p-3 border">Remarks</th>
                    <th class="p-3 border text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="p-3 border"><?php echo e(\Carbon\Carbon::parse($task->date)->format('M d, Y')); ?></td>
                    <td class="p-3 border"><?php echo e($task->description); ?></td>
                    <td class="p-3 border"><?php echo e($task->requested_by); ?></td>
                    <td class="p-3 border"><?php echo e($task->location); ?></td>
                    <td class="p-3 border">
                        <?php echo e(\Carbon\Carbon::parse($task->start_time)->format('h:i A')); ?>

                        -
                        <?php echo e(\Carbon\Carbon::parse($task->end_time)->format('h:i A')); ?>

                    </td>
                    <td class="p-3 border"><?php echo e($task->assigned_to ?? 'N/A'); ?></td>
                    <td class="p-3 border"><?php echo e($task->remarks); ?></td>
                    <td class="p-3 border text-center space-x-2">
                        <a href="<?php echo e(route('tasks.show', $task)); ?>" 
                           class="inline-block px-3 py-1 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700">
                            👁 View
                        </a>
                        <a href="<?php echo e(route('tasks.edit', $task)); ?>" 
                           class="inline-block px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700">
                            ✏️ Edit
                        </a>
                        <form action="<?php echo e(route('tasks.destroy', $task)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button onclick="return confirm('Delete this task?')" 
                                    class="inline-block px-3 py-1 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700">
                                🗑 Delete
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="p-6 text-center text-gray-500">No tasks found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <?php echo e($tasks->appends(['search' => request('search')])->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ict-helpdesk\resources\views/tasks/index.blade.php ENDPATH**/ ?>