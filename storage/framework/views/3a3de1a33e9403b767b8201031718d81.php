<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">➕ Create Meeting</h2>

    <form action="<?php echo e(route('meetings.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <div class="mb-4">
            <label class="block text-gray-700">Title</label>
            <input type="text" name="title" class="w-full border rounded-lg px-3 py-2"
                   value="<?php echo e(old('title')); ?>" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Date</label>
            <input type="date" name="date" class="w-full border rounded-lg px-3 py-2"
                   value="<?php echo e(old('date')); ?>" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Start Time</label>
            <input type="time" name="start_time" class="w-full border rounded-lg px-3 py-2"
                   value="<?php echo e(old('start_time')); ?>" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">End Time</label>
            <input type="time" name="end_time" class="w-full border rounded-lg px-3 py-2"
                   value="<?php echo e(old('end_time')); ?>" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Location</label>
            <input type="text" name="location" class="w-full border rounded-lg px-3 py-2"
                   value="<?php echo e(old('location')); ?>" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Facilitator</label>
            <input type="text" name="facilitator" class="w-full border rounded-lg px-3 py-2"
                   value="<?php echo e(old('facilitator')); ?>">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Participants</label>
            <textarea name="participants" class="w-full border rounded-lg px-3 py-2"><?php echo e(old('participants')); ?></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Remarks</label>
            <textarea name="remarks" class="w-full border rounded-lg px-3 py-2"><?php echo e(old('remarks')); ?></textarea>
        </div>

        <!-- IT Personnel Attendees -->
        <div class="mb-4">
            <label class="block text-gray-700">IT Personnel Attendees</label>
           <select name="it_personnels[]" id="it_personnels" class="form-select" multiple>
    <?php $__currentLoopData = $itPersonnels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $personnel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($personnel->id); ?>"
            <?php if(isset($meeting) && $meeting->itPersonnels->contains($personnel->id)): ?> selected <?php endif; ?>>
            <?php echo e($personnel->name); ?>

        </option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>

            <p class="text-sm text-gray-500 mt-1">
                Hold <strong>Ctrl</strong> (Windows) or <strong>Cmd</strong> (Mac) to select multiple.
            </p>
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            ✅ Save
        </button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ict-helpdesk\resources\views/meetings/create.blade.php ENDPATH**/ ?>