<!-- resources/views/invoices/create.blade.php -->


<?php $__env->startSection('title', 'Nouvelle Facture'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4 fade-in">
    <div>
        <h1 class="h2 mb-1">Nouvelle Facture</h1>
        <p class="text-muted">Créez une nouvelle facture</p>
    </div>
    <a href="<?php echo e(route('invoices.index')); ?>" class="btn btn-outline-custom">
        <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card-classic">
            <div class="card-header-classic">
                <i class="fas fa-file-invoice me-2"></i> Informations de la facture
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('invoices.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Type de facture <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">Sélectionner</option>
                                <option value="purchase" <?php echo e(old('type') == 'purchase' ? 'selected' : ''); ?>>Achat (Entrée stock)</option>
                                <option value="sale" <?php echo e(old('type') == 'sale' ? 'selected' : ''); ?>>Vente (Sortie stock)</option>
                            </select>
                            <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Type de mouvement <span class="text-danger">*</span></label>
                            <select name="movement_type" id="movement_type" class="form-select <?php $__errorArgs = ['movement_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">Sélectionner</option>
                                <option value="in" <?php echo e(old('movement_type') == 'in' ? 'selected' : ''); ?>>Entrée</option>
                                <option value="out" <?php echo e(old('movement_type') == 'out' ? 'selected' : ''); ?>>Sortie</option>
                            </select>
                            <?php $__errorArgs = ['movement_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Produit <span class="text-danger">*</span></label>
                            <select name="product_id" id="product_id" class="form-select <?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">Sélectionner un produit</option>
                                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($product->id); ?>" 
                                            data-price="<?php echo e($product->price); ?>"
                                            data-stock="<?php echo e($product->quantity); ?>"
                                            <?php echo e(old('product_id') == $product->id ? 'selected' : ''); ?>>
                                        <?php echo e($product->name); ?> (Stock: <?php echo e($product->quantity); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Quantité <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('quantity', 1)); ?>" min="1" required>
                            <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small id="stockInfo" class="text-muted"></small>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Prix unitaire (DH) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="unit_price" id="unit_price" 
                                   class="form-control <?php $__errorArgs = ['unit_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('unit_price')); ?>" required>
                            <?php $__errorArgs = ['unit_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Client / Fournisseur <span class="text-danger">*</span></label>
                            <input type="text" name="customer_supplier" class="form-control <?php $__errorArgs = ['customer_supplier'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('customer_supplier')); ?>" required>
                            <?php $__errorArgs = ['customer_supplier'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Mode de paiement</label>
                            <select name="payment_method" class="form-select">
                                <option value="">Sélectionner</option>
                                <option value="Espèces" <?php echo e(old('payment_method') == 'Espèces' ? 'selected' : ''); ?>>💵 Espèces</option>
                                <option value="Carte bancaire" <?php echo e(old('payment_method') == 'Carte bancaire' ? 'selected' : ''); ?>>💳 Carte bancaire</option>
                                <option value="Virement" <?php echo e(old('payment_method') == 'Virement' ? 'selected' : ''); ?>>🏦 Virement</option>
                                <option value="Chèque" <?php echo e(old('payment_method') == 'Chèque' ? 'selected' : ''); ?>>📝 Chèque</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Date de facture</label>
                            <input type="datetime-local" name="invoice_date" class="form-control" 
                                   value="<?php echo e(old('invoice_date', now()->format('Y-m-d\TH:i'))); ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Statut de paiement</label>
                            <select name="payment_status" class="form-select">
                                <option value="pending" <?php echo e(old('payment_status') == 'pending' ? 'selected' : ''); ?>>En attente</option>
                                <option value="paid" <?php echo e(old('payment_status') == 'paid' ? 'selected' : ''); ?>>Payé</option>
                                <option value="cancelled" <?php echo e(old('payment_status') == 'cancelled' ? 'selected' : ''); ?>>Annulé</option>
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Motif / Description</label>
                            <textarea name="reason" class="form-control" rows="2"><?php echo e(old('reason')); ?></textarea>
                        </div>
                        
                        <div class="col-12">
                            <div class="bg-light p-3 rounded text-center">
                                <strong class="d-block mb-2">Total à payer :</strong>
                                <span id="totalAmount" class="h2 text-primary">0.00</span> <span class="h5">DH</span>
                            </div>
                        </div>
                        
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="fas fa-save me-1"></i> Créer la facture
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('unit_price');
    const totalSpan = document.getElementById('totalAmount');
    const stockInfo = document.getElementById('stockInfo');
    const typeSelect = document.getElementById('type');
    const movementTypeSelect = document.getElementById('movement_type');
    
    function updateTotal() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        totalSpan.textContent = (quantity * price).toFixed(2);
    }
    
    function updateProductInfo() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        if (selectedOption.value) {
            const price = selectedOption.dataset.price;
            const stock = selectedOption.dataset.stock;
            priceInput.value = price;
            stockInfo.textContent = `Stock disponible: ${stock}`;
            updateTotal();
        }
    }
    
    function validateStock() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const movementType = movementTypeSelect.value;
        const quantity = parseInt(quantityInput.value) || 0;
        
        if (movementType === 'out' && selectedOption.value) {
            const stock = parseInt(selectedOption.dataset.stock);
            if (quantity > stock) {
                stockInfo.innerHTML = '<span class="text-danger">⚠️ Stock insuffisant!</span>';
                document.querySelector('button[type="submit"]').disabled = true;
            } else {
                stockInfo.textContent = `Stock disponible: ${stock}`;
                document.querySelector('button[type="submit"]').disabled = false;
            }
        } else {
            document.querySelector('button[type="submit"]').disabled = false;
        }
    }
    
    productSelect.addEventListener('change', function() {
        updateProductInfo();
        validateStock();
    });
    
    quantityInput.addEventListener('input', function() {
        updateTotal();
        validateStock();
    });
    
    priceInput.addEventListener('input', updateTotal);
    
    movementTypeSelect.addEventListener('change', validateStock);
    
    typeSelect.addEventListener('change', function() {
        if (this.value === 'purchase') {
            movementTypeSelect.value = 'in';
        } else if (this.value === 'sale') {
            movementTypeSelect.value = 'out';
        }
        validateStock();
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views\invoices\create.blade.php ENDPATH**/ ?>