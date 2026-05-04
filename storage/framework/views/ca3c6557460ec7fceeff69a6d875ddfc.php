<!-- resources/views/notifications/partials/invoice-details.blade.php -->
<div class="p-4 rounded" style="background: <?php echo e($config['bgColor']); ?>;">
    <h5 class="mb-3" style="color: <?php echo e($config['color']); ?>;">
        <i class="fas fa-file-invoice me-2"></i>
        Détails de la facture
    </h5>
    
    <div class="row g-3">
        <div class="col-md-6">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Numéro facture</small>
                <span class="fw-bold" style="color: #4285f4;"><?php echo e($data['invoice_number'] ?? 'N/A'); ?></span>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Produit</small>
                <span class="fw-bold text-white"><?php echo e($data['product_name'] ?? 'N/A'); ?></span>
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
        
        <div class="col-md-4">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Total</small>
                <span class="fw-bold" style="color: #4285f4;"><?php echo e(number_format($data['total_amount'] ?? 0, 2)); ?></span>
                <small class="text-white-50">DH</small>
            </div>
        </div>
        
        <?php if(isset($data['customer_supplier'])): ?>
        <div class="col-12">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Client/Fournisseur</small>
                <span class="fw-bold text-white"><?php echo e($data['customer_supplier']); ?></span>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views\notifications\partials\invoice-details.blade.php ENDPATH**/ ?>