<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-green-700">🗓 Task Schedule</h2>

        
        <?php if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'it_staff')): ?>
            <a href="<?php echo e(route('tasks.create')); ?>" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition">
               + Add Task
            </a>
        <?php endif; ?>
    </div>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full border border-gray-200 text-sm">
            <thead class="bg-gray-100 text-gray-700 uppercase">
                <tr>
                    <th class="px-4 py-2 border">Date</th>
                    <th class="px-4 py-2 border">Description</th>
                    <th class="px-4 py-2 border">Requested By</th>
                    <th class="px-4 py-2 border">Location</th>
                    <th class="px-4 py-2 border">Time Range</th>
                    <th class="px-4 py-2 border">IT Personnel</th>
                    <th class="px-4 py-2 border">Remarks</th>
                    <th class="px-4 py-2 border text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border"><?php echo e(\Carbon\Carbon::parse($task->date)->format('M d, Y')); ?></td>
                        <td class="px-4 py-2 border"><?php echo e($task->description); ?></td>
                        <td class="px-4 py-2 border"><?php echo e($task->requested_by); ?></td>
                        <td class="px-4 py-2 border"><?php echo e($task->location); ?></td>
                        <td class="px-4 py-2 border">
                            <?php echo e(\Carbon\Carbon::parse($task->start_time)->format('h:i A')); ?> -
                            <?php echo e(\Carbon\Carbon::parse($task->end_time)->format('h:i A')); ?>

                        </td>
                        <td class="px-4 py-2 border"><?php echo e($task->assigned_to ?? 'N/A'); ?></td>
                        <td class="px-4 py-2 border"><?php echo e($task->remarks); ?></td>

                        <td class="px-4 py-2 border text-center space-x-2">
                            
                            <a href="<?php echo e(route('tasks.show', $task->id)); ?>" 
                               class="inline-block bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                👁 View
                            </a>

                            
                            <?php if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'it_staff')): ?>
                                <a href="<?php echo e(route('tasks.edit', $task->id)); ?>" 
                                   class="inline-block bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                   ✏️ Edit
                                </a>

                                <form action="<?php echo e(route('tasks.destroy', $task->id)); ?>" 
                                      method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                                        onclick="return confirm('Are you sure you want to delete this task?')">
                                        🗑 Delete
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">No tasks found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ict_helpdesk\resources\views/tasks/index.blade.php ENDPATH**/ ?>