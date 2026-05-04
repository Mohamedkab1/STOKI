<!-- resources/views/notifications/partials/stock-movement-details.blade.php -->
<div class="p-4 rounded" style="background: <?php echo e($config['bgColor']); ?>;">
    <h5 class="mb-3" style="color: <?php echo e($config['color']); ?>;">
        <i class="fas fa-exchange-alt me-2"></i>
        Détails du mouvement
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
                <small class="text-white-50 d-block">Type</small>
                <?php if(($data['movement_type'] ?? '') == 'in'): ?>
                    <span class="badge p-2" style="background: #4285f4; font-size: 14px;">ENTRÉE</span>
                <?php else: ?>
                    <span class="badge p-2" style="background: #BC4639; font-size: 14px;">SORTIE</span>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Quantité</small>
                <span class="fw-bold text-white"><?php echo e($data['quantity'] ?? 0); ?></span>
                <small class="text-white-50">unités</small>
            </div>
        </div>
        
        <?php if(isset($data['unit_price'])): ?>
        <div class="col-md-4">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Prix unitaire</small>
                <span class="fw-bold text-white"><?php echo e(number_format($data['unit_price'], 2)); ?></span>
                <small class="text-white-50">DH</small>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if(isset($data['total_price'])): ?>
        <div class="col-md-4">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Total</small>
                <span class="fw-bold" style="color: #4285f4;"><?php echo e(number_format($data['total_price'], 2)); ?></span>
                <small class="text-white-50">DH</small>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if(isset($data['reason'])): ?>
        <div class="col-12">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Motif</small>
                <span class="text-white"><?php echo e($data['reason']); ?></span>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views\notifications\partials\stock-movement-details.blade.php ENDPATH**/ ?>