<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">➕ Create New Ticket</h2>

    <form action="<?php echo e(route('tickets.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <!-- Title -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Title</label>
            <input type="text" name="title"
                   value="<?php echo e(old('title')); ?>"
                   class="w-full border rounded-lg p-2" required>
            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Description</label>
            <textarea name="description"
                      class="w-full border rounded-lg p-2"
                      required><?php echo e(old('description')); ?></textarea>
            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Category -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Category</label>
            <select name="category_id" class="w-full border rounded-lg p-2">
                <option value="">-- Select Category --</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>"
                        <?php echo e(old('category_id') == $cat->id ? 'selected' : ''); ?>>
                        <?php echo e($cat->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <input type="text" name="category_manual"
                   placeholder="Or type new category"
                   value="<?php echo e(old('category_manual')); ?>"
                   class="w-full border rounded-lg p-2 mt-2">
        </div>

        <!-- Department -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Department</label>
            <select name="department" class="w-full border rounded-lg p-2">
                <option value="">-- Select Department --</option>
                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($dept->name); ?>"
                        <?php echo e(old('department') == $dept->name ? 'selected' : ''); ?>>
                        <?php echo e($dept->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <input type="text" name="department_manual"
                   placeholder="Or type new department"
                   value="<?php echo e(old('department_manual')); ?>"
                   class="w-full border rounded-lg p-2 mt-2">
        </div>

        <!-- ✅ Assign To (IT Personnel) — ADMIN / IT STAFF ONLY -->
        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin|it_staff')): ?>
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">
                Assign To (IT Personnel)
            </label>
            <select name="assigned_to" class="w-full border rounded-lg p-2">
                <option value="">-- Select IT Personnel --</option>
                <?php $__empty_1 = true; $__currentLoopData = $it_personnel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <option value="<?php echo e($user->id); ?>"
                        <?php echo e(old('assigned_to') == $user->id ? 'selected' : ''); ?>>
                        <?php echo e($user->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <option value="">No IT Personnel available</option>
                <?php endif; ?>
            </select>
        </div>
        <?php endif; ?>
        <!-- ❌ Clients will NEVER see this -->

        <!-- Priority -->
        <div class="mb-4">
            <label class="block font-semibold text-gray-700">Priority</label>
            <select name="priority" class="w-full border-gray-300 rounded-lg shadow-sm">
                <option value="Low" <?php echo e(old('priority') == 'Low' ? 'selected' : ''); ?>>Low</option>
                <option value="Normal" <?php echo e(old('priority') == 'Normal' ? 'selected' : ''); ?>>Normal</option>
                <option value="High" <?php echo e(old('priority') == 'High' ? 'selected' : ''); ?>>High</option>
            </select>
        </div>

        <!-- Client Info -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Client Name</label>
            <input type="text" name="client_name"
                   value="<?php echo e(old('client_name')); ?>"
                   class="w-full border rounded-lg p-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Contact Number</label>
            <input type="text" name="contact_number"
                   value="<?php echo e(old('contact_number')); ?>"
                   class="w-full border rounded-lg p-2">
        </div>

        <!-- Remarks -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Remarks</label>
            <textarea name="remarks"
                      class="w-full border rounded-lg p-2"><?php echo e(old('remarks')); ?></textarea>
        </div>

        <!-- Submit -->
        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Create Ticket
        </button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ict_helpdesk\resources\views/tickets/create.blade.php ENDPATH**/ ?>