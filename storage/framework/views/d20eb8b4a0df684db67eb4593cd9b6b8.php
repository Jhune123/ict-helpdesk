<?php $__env->startSection('styles'); ?>
<style>
    .info-label {
        font-weight: 600;
        color: #1E40AF;
    }
    .info-value {
        background-color: #F3F4F6;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
    }
    .card {
        background-color: #fff;
        padding: 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4" style="max-width: 800px;">
    <div class="card">
        <h2 class="text-2xl font-bold mb-6">📄 Asset Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <span class="info-label">Entity Name:</span>
                <div class="info-value"><?php echo e($asset->entity_name); ?></div>
            </div>
            <div>
                <span class="info-label">Fund Cluster:</span>
                <div class="info-value"><?php echo e($asset->fund_cluster); ?></div>
            </div>
            <div>
                <span class="info-label">PAR No.:</span>
                <div class="info-value"><?php echo e($asset->par_no); ?></div>
            </div>
            <div>
                <span class="info-label">Quantity:</span>
                <div class="info-value"><?php echo e($asset->quantity); ?></div>
            </div>
            <div>
                <span class="info-label">Unit:</span>
                <div class="info-value"><?php echo e($asset->unit); ?></div>
            </div>
            <div>
                <span class="info-label">Date Acquired:</span>
                <div class="info-value"><?php echo e($asset->date_acquired); ?></div>
            </div>
            <div>
                <span class="info-label">Amount:</span>
                <div class="info-value"><?php echo e(number_format($asset->amount, 2)); ?></div>
            </div>
            <div>
                <span class="info-label">Property No.:</span>
                <div class="info-value"><?php echo e($asset->property_no); ?></div>
            </div>
            <div class="col-span-2">
                <span class="info-label">Description:</span>
                <div class="info-value"><?php echo e($asset->description); ?></div>
            </div>
            <div class="col-span-2">
                <span class="info-label">Purpose:</span>
                <div class="info-value"><?php echo e($asset->purpose); ?></div>
            </div>
            <div>
                <span class="info-label">Approved for Issuance:</span>
                <div class="info-value"><?php echo e($asset->approved_for_issuance); ?></div>
            </div>
            <div>
                <span class="info-label">Received From:</span>
                <div class="info-value"><?php echo e($asset->received_from); ?></div>
            </div>
            <div>
                <span class="info-label">Received By:</span>
                <div class="info-value"><?php echo e($asset->received_by); ?></div>
            </div>
            <div>
                <span class="info-label">Date Counted:</span>
                <div class="info-value"><?php echo e($asset->date_counted); ?></div>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <a href="<?php echo e(route('assets.index')); ?>" class="btn bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded shadow">
                Back
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ict_helpdesk\resources\views/assets/show.blade.php ENDPATH**/ ?>