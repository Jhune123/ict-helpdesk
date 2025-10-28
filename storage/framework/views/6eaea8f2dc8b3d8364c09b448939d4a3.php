<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-6 mt-6">
    <h2 class="text-2xl font-bold mb-6 text-green-700 flex items-center gap-2">
        ✏️ Edit Meeting
    </h2>

    
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

    <form action="<?php echo e(route('meetings.update', $meeting->id)); ?>" method="POST" class="space-y-5">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Title -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Title</label>
            <input type="text" name="title"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                value="<?php echo e(old('title', $meeting->title)); ?>" required>
        </div>

        <!-- Date -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Date</label>
            <input type="date" name="date"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                value="<?php echo e(old('date', $meeting->date)); ?>" required>
        </div>

        <!-- Start Time -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Start Time</label>
            <input type="time" name="start_time"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                value="<?php echo e(old('start_time', $meeting->start_time)); ?>" required>
        </div>

        <!-- End Time -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">End Time</label>
            <input type="time" name="end_time"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                value="<?php echo e(old('end_time', $meeting->end_time)); ?>" required>
        </div>

        <!-- Location -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Location</label>
            <input type="text" name="location"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                value="<?php echo e(old('location', $meeting->location)); ?>" required>
        </div>

        <!-- Facilitator -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Facilitator</label>
            <input type="text" name="facilitator"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                value="<?php echo e(old('facilitator', $meeting->facilitator)); ?>">
        </div>

        <!-- Participants -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Participants</label>
            <textarea name="participants"
                class="w-full border rounded-lg px-3 py-2 h-24 resize-y focus:ring-2 focus:ring-green-500"
                placeholder="List of participants..."><?php echo e(old('participants', $meeting->participants)); ?></textarea>
        </div>

        <!-- Remarks -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Remarks</label>
            <textarea name="remarks"
                class="w-full border rounded-lg px-3 py-2 h-20 resize-y focus:ring-2 focus:ring-green-500"
                placeholder="Additional notes or updates..."><?php echo e(old('remarks', $meeting->remarks)); ?></textarea>
        </div>

        
        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin|it_staff')): ?>
        <div>
            <label class="block text-gray-700 font-medium mb-1">IT Personnel Attendees</label>
            <select name="it_personnels[]" id="it_personnels"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                multiple>
                <?php $__currentLoopData = $itPersonnels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $personnel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($personnel->id); ?>"
                        <?php if($meeting->itPersonnels->contains($personnel->id)): ?> selected <?php endif; ?>>
                        <?php echo e($personnel->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <p class="text-sm text-gray-500 mt-1">
                Hold <strong>Ctrl</strong> (Windows) or <strong>Cmd</strong> (Mac) to select multiple attendees.
            </p>
        </div>
        <?php endif; ?>

        <!-- Buttons -->
        <div class="flex justify-between items-center mt-6">
            <a href="<?php echo e(route('meetings.index')); ?>"
               class="bg-gray-500 hover:bg-gray-700 text-white font-medium px-4 py-2 rounded-lg transition">
                ⬅ Cancel
            </a>
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-medium px-5 py-2.5 rounded-lg shadow-md transition">
                💾 Update Meeting
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ict_helpdesk\resources\views/meetings/edit.blade.php ENDPATH**/ ?>