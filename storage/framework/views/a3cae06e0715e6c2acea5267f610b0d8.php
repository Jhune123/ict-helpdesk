<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4 text-green-700">📑 Meeting Details</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
        <div>
            <p><strong>📌 Title:</strong> <?php echo e($meeting->title); ?></p>
            <p><strong>📅 Date:</strong> <?php echo e(\Carbon\Carbon::parse($meeting->date)->format('F d, Y')); ?></p>
            <p><strong>🕒 Time:</strong> <?php echo e($meeting->start_time); ?> - <?php echo e($meeting->end_time); ?></p>
            <p><strong>📍 Location:</strong> <?php echo e($meeting->location); ?></p>
            <p><strong>👨‍💼 Facilitator:</strong> <?php echo e($meeting->facilitator ?? 'N/A'); ?></p>
        </div>

        <div>
            <p><strong>👥 Participants:</strong> <?php echo e($meeting->participants); ?></p>
            <p><strong>📝 Remarks:</strong> <?php echo e($meeting->remarks ?? 'No remarks'); ?></p>
            <p><strong>💻 IT Personnel Attending:</strong></p>
            <ul class="list-disc list-inside">
                <?php $__empty_1 = true; $__currentLoopData = $meeting->itPersonnel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $personnel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li><?php echo e($personnel->name); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="text-gray-500">No IT Personnel assigned</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-2">
        <a href="<?php echo e(route('meetings.index')); ?>" 
           class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            ⬅ Back
        </a>

        
        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin|it_staff')): ?>
            <a href="<?php echo e(route('meetings.edit', $meeting->id)); ?>" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
               ✏ Edit
            </a>

            <form action="<?php echo e(route('meetings.destroy', $meeting->id)); ?>" 
                  method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this meeting?');">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" 
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    🗑 Delete
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ict_helpdesk\resources\views/meetings/show.blade.php ENDPATH**/ ?>