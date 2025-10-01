<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold mb-6">All Notifications</h2>

    <?php if($notifications->count() > 0): ?>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul>
                <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="border-b">
                        <div class="px-4 py-4 flex justify-between items-center <?php echo e(is_null($notification->read_at) ? 'bg-gray-100' : ''); ?>">
                            <div>
                                <p class="text-sm"><?php echo e($notification->data['message'] ?? 'New Notification'); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($notification->created_at->diffForHumans()); ?></p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <?php if(is_null($notification->read_at)): ?>
                                    <form action="<?php echo e(route('notifications.markAsRead', $notification->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-blue-600 hover:underline text-sm">Mark as Read</button>
                                    </form>
                                <?php else: ?>
                                    <form action="<?php echo e(route('notifications.markAsUnread', $notification->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-yellow-600 hover:underline text-sm">Mark as Unread</button>
                                    </form>
                                <?php endif; ?>
                                <a href="<?php echo e(route('tickets.show', $notification->data['ticket_id'])); ?>" class="text-gray-700 hover:underline text-sm">View</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>

        <div class="mt-4">
            <?php echo e($notifications->links()); ?>

        </div>
    <?php else: ?>
        <p class="text-gray-500">No notifications found.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ict-helpdesk\resources\views/notifications/index.blade.php ENDPATH**/ ?>