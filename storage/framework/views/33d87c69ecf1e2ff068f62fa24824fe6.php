<?php $__env->startSection('content'); ?>
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Categories</h2>

    <?php if(session('success')): ?>
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <a href="<?php echo e(route('categories.create')); ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">+ Add Category</a>

    <div class="overflow-x-auto mt-6">
        <table class="w-full border border-gray-300 bg-white shadow-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2 text-left">#</th>
                    <th class="border px-3 py-2 text-left">Name</th>
                    <th class="border px-3 py-2 text-left">Description</th>
                    <th class="border px-3 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-3 py-2"><?php echo e($loop->iteration); ?></td>
                        <td class="border px-3 py-2"><?php echo e($category->name); ?></td>
                        <td class="border px-3 py-2"><?php echo e($category->description); ?></td>
                        <td class="border px-3 py-2 text-center">
                            <a href="<?php echo e(route('categories.edit', $category->id)); ?>" class="text-blue-600 hover:underline">Edit</a>
                            <span class="mx-1">|</span>
                            <form action="<?php echo e(route('categories.destroy', $category->id)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this category?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">No categories yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ict-helpdesk\resources\views/categories/index.blade.php ENDPATH**/ ?>