```php


<?php $__env->startSection('content'); ?>
<div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
    <!-- 🎫 Ticket Details -->
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">🎫 Ticket Details</h2>

        <div class="space-y-3 text-gray-700">
            <div><span class="font-semibold">Title:</span> <span class="ml-2"><?php echo e($ticket->title); ?></span></div>
            <div><span class="font-semibold">Description:</span> <p class="ml-2 text-gray-600"><?php echo e($ticket->description ?? 'N/A'); ?></p></div>
            <div><span class="font-semibold">Status:</span> <span class="ml-2"><?php echo e($ticket->status); ?></span></div>
            <div><span class="font-semibold">Category:</span> <span class="ml-2"><?php echo e($ticket->categoryName); ?></span></div>
            <div><span class="font-semibold">Department:</span> <span class="ml-2"><?php echo e($ticket->department ?? 'N/A'); ?></span></div>
            <div><span class="font-semibold">Client Name:</span> <span class="ml-2"><?php echo e($ticket->client_name); ?></span></div>
            <div><span class="font-semibold">IT Personnel:</span> <span class="ml-2"><?php echo e($ticket->assigneeName); ?></span></div>
            <div><span class="font-semibold">Remarks:</span> <p class="ml-2 text-gray-600"><?php echo e($ticket->remarks ?? 'N/A'); ?></p></div>
            <div><span class="font-semibold">Date Created:</span>
                <span class="ml-2"><?php echo e($ticket->created_at->timezone('Asia/Manila')->format('F d, Y h:i A')); ?></span>
            </div>

            <?php if($ticket->status === 'Closed' && $ticket->date_finished): ?>
            <div><span class="font-semibold">Date Finished:</span>
                <span class="ml-2"><?php echo e(\Carbon\Carbon::parse($ticket->date_finished)->timezone('Asia/Manila')->format('F d, Y h:i A')); ?></span>
            </div>
            <?php endif; ?>
        </div>

        <!-- Buttons -->
        <div class="mt-6 flex justify-between">
            <a href="<?php echo e(route('tickets.index')); ?>" class="px-4 py-2 bg-gray-500 text-white rounded-lg shadow hover:bg-gray-600 transition">⬅️ Back</a>

            <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin|it_staff')): ?>
            <a href="<?php echo e(route('tickets.edit', $ticket->id)); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">✏️ Edit</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- 💬 Ticket Comments Section -->
    <div class="bg-white shadow-lg rounded-2xl p-6 mt-8 border border-gray-100">
        <h3 class="text-xl font-bold mb-4 text-gray-800">💬 Ticket Comments</h3>

        <!-- ✅ Flash Messages -->
        <?php if(session('success')): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg border border-green-300">
                <?php echo e(session('success')); ?>

            </div>
        <?php elseif(session('error')): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg border border-red-300">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <!-- Comments List -->
        <div class="space-y-4 mb-6">
            <?php $__empty_1 = true; $__currentLoopData = $ticket->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 hover:bg-gray-100 transition">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-800"><?php echo e($comment->user->name ?? 'Unknown'); ?></span>
                        <span class="text-xs text-gray-500">
                            <?php echo e($comment->created_at->timezone('Asia/Manila')->format('M d, Y h:i A')); ?>

                        </span>
                    </div>
                    <p class="mt-2 text-gray-700"><?php echo e($comment->message); ?></p>

                    <?php if(Auth::id() === $comment->user_id || Auth::user()->hasRole('admin')): ?>
                        <form action="<?php echo e(route('comments.destroy', $comment->id)); ?>" method="POST" onsubmit="return confirm('Delete this comment?')" class="mt-2">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">🗑 Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500 italic">No comments yet.</p>
            <?php endif; ?>
        </div>

        <!-- ✏️ Add Comment Form -->
        <form action="<?php echo e(route('tickets.comments.store', $ticket->id)); ?>" method="POST" class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <?php echo csrf_field(); ?>
            <label for="message" class="block text-gray-700 font-semibold mb-2">Add a Comment:</label>
            <textarea 
                name="message" 
                id="message" 
                rows="3"
                class="w-full border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                placeholder="💬 Write your comment here..." 
                required></textarea>

            <div class="flex justify-end mt-3">
                <button 
                    type="submit" 
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 focus:ring-2 focus:ring-blue-400 transition">
                    ➕ Post Comment
                </button>
            </div>
        </form>
    </div>

    <!-- 📎 Ticket Attachments Section -->
    <div class="bg-white shadow-lg rounded-2xl p-6 mt-8 border border-gray-100">
        <h3 class="text-xl font-bold mb-4 text-gray-800">📎 Ticket Attachments</h3>

        <!-- List of attachments -->
        <div class="space-y-2 mb-4">
            <?php $__empty_1 = true; $__currentLoopData = $ticket->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <div>
                        <span class="font-medium text-gray-800"><?php echo e($file->filename); ?></span>
                        <span class="text-sm text-gray-500 ml-2">(<?php echo e($file->filesize); ?>)</span>
                    </div>
                    <div class="flex space-x-3">
                        <a href="<?php echo e(route('attachments.download', $file->id)); ?>" class="text-blue-600 hover:underline">⬇️ Download</a>
                        <?php if(Auth::id() === $file->user_id || Auth::user()->hasRole('admin')): ?>
                            <form action="<?php echo e(route('attachments.destroy', $file->id)); ?>" method="POST" onsubmit="return confirm('Delete this file?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:underline">🗑 Delete</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500 italic">No attachments yet.</p>
            <?php endif; ?>
        </div>

        <!-- Upload Form -->
        <form action="<?php echo e(route('attachments.store', $ticket->id)); ?>" method="POST" enctype="multipart/form-data" class="border-t border-gray-200 pt-4">
            <?php echo csrf_field(); ?>
            <label for="file" class="block text-gray-700 font-semibold mb-2">Upload a file:</label>
            <input type="file" name="file" id="file" required class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">

            <div class="flex justify-end mt-3">
                <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                    📤 Upload
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
```

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ict-helpdesk\resources\views/tickets/show.blade.php ENDPATH**/ ?>