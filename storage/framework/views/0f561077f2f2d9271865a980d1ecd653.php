<?php $__env->startSection('content'); ?>
<div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-2xl p-6">
        <h2 class="text-2xl font-bold mb-4">📋 All Tickets</h2>

        <div class="flex gap-4 mb-4">
            <a href="<?php echo e(route('tickets.create')); ?>" 
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                ➕ Create Ticket
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 border">#</th>
                        <th class="px-4 py-2 border">Title</th>
                        <th class="px-4 py-2 border">Category</th>
                        <th class="px-4 py-2 border">Department</th>
                        <th class="px-4 py-2 border">Client</th>
                        <th class="px-4 py-2 border">IT Personnel</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Date Created</th>
                        <th class="px-4 py-2 border">Date Finished</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="text-gray-700 hover:bg-gray-50">
                            <td class="px-4 py-2 border"><?php echo e($ticket->id); ?></td>
                            <td class="px-4 py-2 border"><?php echo e($ticket->title); ?></td>
                            <td class="px-4 py-2 border"><?php echo e($ticket->categoryName ?? 'N/A'); ?></td>
                            <td class="px-4 py-2 border"><?php echo e($ticket->department ?? 'N/A'); ?></td>
                            <td class="px-4 py-2 border"><?php echo e($ticket->client_name ?? 'N/A'); ?></td>
                            <td class="px-4 py-2 border"><?php echo e($ticket->assigneeName ?? 'N/A'); ?></td>
                            <td class="px-4 py-2 border">
                                <span class="px-2 py-1 rounded-lg text-sm 
                                    <?php if($ticket->status === 'Open'): ?> bg-green-100 text-green-700
                                    <?php elseif($ticket->status === 'In Progress'): ?> bg-yellow-100 text-yellow-700
                                    <?php elseif($ticket->status === 'Closed'): ?> bg-red-100 text-red-700
                                    <?php endif; ?>">
                                    <?php echo e($ticket->status); ?>

                                </span>
                            </td>
                            <td class="px-4 py-2 border">
                                <?php echo e($ticket->created_at ? $ticket->created_at->timezone('Asia/Manila')->format('F d, Y h:i A') : 'N/A'); ?>

                            </td>
                            <td class="px-4 py-2 border">
                                <?php echo e($ticket->date_finished ? \Carbon\Carbon::parse($ticket->date_finished)->timezone('Asia/Manila')->format('F d, Y h:i A') : 'N/A'); ?>

                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-2 border flex gap-2 items-center">
                                <!-- View button (always visible) -->
                                <a href="<?php echo e(route('tickets.show', $ticket->id)); ?>" 
                                   class="px-3 py-1 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">
                                    👁️ View
                                </a>

                                <?php
                                    $role = strtolower(auth()->user()?->role ?? 'user');
                                ?>

                                <?php if(in_array($role, ['admin', 'it_staff'])): ?>
                                    <!-- Edit button -->
                                    <a href="<?php echo e(route('tickets.edit', $ticket->id)); ?>" 
                                       class="px-3 py-1 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                                        ✏️ Edit
                                    </a>

                                    <!-- Delete button -->
                                    <form action="<?php echo e(route('tickets.destroy', $ticket->id)); ?>" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this ticket?')" 
                                          class="inline-block">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="px-3 py-1 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="px-4 py-4 text-center text-gray-500">
                                No tickets found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <?php echo e($tickets->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ict-helpdesk\resources\views/tickets/index.blade.php ENDPATH**/ ?>