<!-- resources/views/notifications/partials/low-stock-details.blade.php -->
<div class="p-4 rounded" style="background: <?php echo e($config['bgColor']); ?>;">
    <h5 class="mb-3" style="color: <?php echo e($config['color']); ?>;">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Détails de l'alerte stock
    </h5>
    
    <div class="row g-3">
        <div class="col-md-6">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Produit</small>
                <span class="fw-bold text-white"><?php echo e($data['product_name'] ?? 'N/A'); ?></span>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">SKU</small>
                <span class="fw-bold text-white"><?php echo e($data['sku'] ?? 'N/A'); ?></span>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Stock actuel</small>
                <span class="fw-bold" style="color: #BC4639; font-size: 1.2rem;"><?php echo e($data['current_stock'] ?? 0); ?></span>
                <small class="text-white-50">unités</small>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Stock minimum</small>
                <span class="fw-bold text-white"><?php echo e($data['min_stock'] ?? 0); ?></span>
                <small class="text-white-50">unités</small>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Manque</small>
                <?php
                    $manque = ($data['min_stock'] ?? 0) - ($data['current_stock'] ?? 0);
                ?>
                <span class="fw-bold" style="color: <?php echo e($manque > 0 ? '#BC4639' : '#4285f4'); ?>;"><?php echo e(max(0, $manque)); ?></span>
                <small class="text-white-50">unités</small>
            </div>
        </div>
        
        <?php if(isset($data['current_stock']) && isset($data['min_stock']) && $data['min_stock'] > 0): ?>
            <?php
                $percentage = min(100, ($data['current_stock'] / $data['min_stock']) * 100);
                $progressColor = $percentage < 50 ? '#BC4639' : ($percentage < 75 ? '#D4A59A' : '#4285f4');
            ?>
            <div class="col-12">
                <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                    <small class="text-white-50 d-block mb-2">Niveau de stock</small>
                    <div class="progress" style="height: 25px; background: rgba(0,0,0,0.3); border-radius: 12px;">
                        <div class="progress-bar" 
                             role="progressbar"
                             style="width: <?php echo e($percentage); ?>%; background: <?php echo e($progressColor); ?>; border-radius: 12px;"
                             aria-valuenow="<?php echo e($percentage); ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            <?php echo e(number_format($percentage, 1)); ?>%
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views\notifications\partials\low-stock-details.blade.php ENDPATH**/ ?>