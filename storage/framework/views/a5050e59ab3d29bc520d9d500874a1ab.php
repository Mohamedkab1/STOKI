<!-- resources/views/products/fifo-history.blade.php -->


<?php $__env->startSection('title', 'Historique FIFO - ' . $product->name); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4 fade-in">
    <div>
        <h1 class="h2 mb-1">Historique FIFO</h1>
        <p class="text-muted"><?php echo e($product->name); ?> (<?php echo e($product->sku); ?>)</p>
    </div>
    <a href="<?php echo e(route('products.show', $product)); ?>" class="btn btn-outline-custom">
        <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="card-classic">
    <div class="card-header-classic">
        <i class="fas fa-chart-line me-2"></i> Mouvements FIFO
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-classic mb-0">
                <thead>
                    <tr>
                        <th rowspan="2" class="align-middle">Date</th>
                        <th colspan="3" class="text-center bg-success bg-opacity-10">Entrées</th>
                        <th colspan="3" class="text-center bg-danger bg-opacity-10">Sorties</th>
                        <th colspan="3" class="text-center bg-info bg-opacity-10">Stock théorique</th>
                    </tr>
                    <tr>
                        <th>Qté</th>
                        <th>PU</th>
                        <th>Valeur</th>
                        <th>Qté</th>
                        <th>PU</th>
                        <th>Valeur</th>
                        <th>Qté</th>
                        <th>PU Moyen</th>
                        <th>Valeur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $fifoHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-bold"><?php echo e($entry['date']); ?></td>
                        
                        <?php if($entry['type'] == 'in'): ?>
                            <td class="text-success"><?php echo e($entry['quantity']); ?></td>
                            <td class="text-success"><?php echo e(number_format($entry['unit_price'], 2)); ?> DH</td>
                            <td class="text-success"><?php echo e(number_format($entry['total_value'], 2)); ?> DH</td>
                        <?php else: ?>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        <?php endif; ?>
                        
                        <?php if($entry['type'] == 'out'): ?>
                            <td class="text-danger"><?php echo e($entry['quantity']); ?></td>
                            <td class="text-danger">
                                <?php if(!empty($entry['details'])): ?>
                                    <?php $__currentLoopData = $entry['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <small class="d-block"><?php echo e($detail['unit_price']); ?> DH</small>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <?php echo e(number_format($entry['unit_price'], 2)); ?> DH
                                <?php endif; ?>
                            </td>
                            <td class="text-danger"><?php echo e(number_format($entry['total_value'], 2)); ?> DH</td>
                        <?php else: ?>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        <?php endif; ?>
                        
                        <td class="fw-bold"><?php echo e($entry['stock_after']); ?></td>
                        <td>
                            <?php if($entry['stock_after'] > 0): ?>
                                <?php echo e(number_format($entry['stock_value_after'] / $entry['stock_after'], 2)); ?> DH
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold"><?php echo e(number_format($entry['stock_value_after'], 2)); ?> DH</td>
                    </tr>
                    
                    <?php if($entry['type'] == 'out' && !empty($entry['details'])): ?>
                    <tr class="table-light">
                        <td colspan="10" class="small text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Détail FIFO:
                            <?php $__currentLoopData = $entry['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-secondary me-1">Lot: <?php echo e($detail['quantity']); ?> x <?php echo e(number_format($detail['unit_price'], 2)); ?>DH = <?php echo e(number_format($detail['subtotal'], 2)); ?>DH</span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <i class="fas fa-chart-line fa-2x mb-2"></i>
                            <p>Aucun mouvement FIFO trouvé</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="9" class="text-end">Stock final :</th>
                        <th><?php echo e($fifoHistory ? end($fifoHistory)['stock_after'] : 0); ?></th>
                    </tr>
                    <tr>
                        <th colspan="9" class="text-end">Valeur du stock final :</th>
                        <th class="fw-bold"><?php echo e($fifoHistory ? number_format(end($fifoHistory)['stock_value_after'], 2) : 0); ?> DH</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Lots en stock -->
<div class="card-classic mt-4">
    <div class="card-header-classic">
        <i class="fas fa-cubes me-2"></i> Lots en stock
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-classic mb-0">
                <thead>
                    <tr>
                        <th>Lot</th>
                        <th>Date réception</th>
                        <th>Quantité restante</th>
                        <th>Prix unitaire</th>
                        <th>Valeur</th>
                        <th>Expiration</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $batches = App\Models\ProductBatch::where('product_id', $product->id)
                            ->where('remaining_quantity', '>', 0)
                            ->orderBy('received_date', 'asc')
                            ->get();
                    ?>
                    
                    <?php $__empty_1 = true; $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="badge bg-secondary"><?php echo e($batch->batch_number); ?></span></td>
                        <td><?php echo e($batch->received_date->format('d/m/Y')); ?></td>
                        <td><?php echo e($batch->remaining_quantity); ?></td>
                        <td><?php echo e(number_format($batch->purchase_price, 2)); ?> DH</td>
                        <td class="fw-bold"><?php echo e(number_format($batch->remaining_quantity * $batch->purchase_price, 2)); ?> DH</td>
                        <td>
                            <?php if($batch->expiry_date): ?>
                                <?php if($batch->expiry_date->isPast()): ?>
                                    <span class="badge bg-danger">Expiré</span>
                                <?php else: ?>
                                    <?php echo e($batch->expiry_date->format('d/m/Y')); ?>

                                <?php endif; ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-box-open fa-2x mb-2"></i>
                            <p>Aucun lot en stock</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="2">Total</th>
                        <th><?php echo e($batches->sum('remaining_quantity')); ?></th>
                        <th></th>
                        <th class="fw-bold"><?php echo e(number_format($batches->sum(function($b) { return $b->remaining_quantity * $b->purchase_price; }), 2)); ?> DH</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views\products\fifo-history.blade.php ENDPATH**/ ?>