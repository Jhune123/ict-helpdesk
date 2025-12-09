<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4 text-green-700">➕ Create Meeting</h2>

    
    <?php if($errors->any()): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <strong>⚠️ Please fix the following errors:</strong>
            <ul class="list-disc list-inside">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('meetings.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Title</label>
                <input type="text" name="title" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                       value="<?php echo e(old('title')); ?>" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Date</label>
                <input type="date" name="date" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                       value="<?php echo e(old('date')); ?>" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Start Time</label>
                <input type="time" name="start_time" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                       value="<?php echo e(old('start_time')); ?>" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium">End Time</label>
                <input type="time" name="end_time" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                       value="<?php echo e(old('end_time')); ?>" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Location</label>
            <input type="text" name="location" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                   value="<?php echo e(old('location')); ?>" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Facilitator</label>
            <input type="text" name="facilitator" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                   value="<?php echo e(old('facilitator')); ?>">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Participants</label>
            <textarea name="participants" rows="3" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"><?php echo e(old('participants')); ?></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Remarks</label>
            <textarea name="remarks" rows="3" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"><?php echo e(old('remarks')); ?></textarea>
        </div>

        
        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin|it_staff')): ?>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">IT Personnel Attendees</label>
                <select name="it_personnels[]" id="it_personnels"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                        multiple>
                    <?php $__currentLoopData = $itPersonnels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $personnel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($personnel->id); ?>"
                            <?php if(isset($meeting) && $meeting->itPersonnel->contains($personnel->id)): ?> selected <?php endif; ?>>
                            <?php echo e($personnel->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <p class="text-sm text-gray-500 mt-1">
                    Hold <strong>Ctrl</strong> (Windows) or <strong>Cmd</strong> (Mac) to select multiple.
                </p>
            </div>
        <?php endif; ?>

        <div class="flex justify-end space-x-2 mt-6">
            <a href="<?php echo e(route('meetings.index')); ?>"
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700">⬅ Cancel</a>
            <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                ✅ Save
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ict_helpdesk\resources\views/meetings/create.blade.php ENDPATH**/ ?>