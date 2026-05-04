<!-- resources/views/notifications/partials/default-details.blade.php -->
<div class="p-4 rounded" style="background: <?php echo e($config['bgColor']); ?>;">
    <h5 class="mb-3" style="color: <?php echo e($config['color']); ?>;">
        <i class="fas fa-info-circle me-2"></i>
        Informations
    </h5>
    
    <div class="row g-3">
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(!in_array($key, ['icon', 'color', 'message', 'action_url', 'type'])): ?>
                <div class="col-md-6">
                    <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                        <small class="text-white-50 d-block"><?php echo e(ucfirst(str_replace('_', ' ', $key))); ?></small>
                        <span class="fw-bold text-white">
                            <?php if(is_array($value)): ?>
                                <?php echo e(json_encode($value)); ?>

                            <?php elseif(is_numeric($value) && (strpos($key, 'price') !== false || strpos($key, 'amount') !== false || strpos($key, 'total') !== false)): ?>
                                <?php echo e(number_format($value, 2)); ?> DH
                            <?php else: ?>
                                <?php echo e($value); ?>

                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div><?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views\notifications\partials\default-details.blade.php ENDPATH**/ ?>