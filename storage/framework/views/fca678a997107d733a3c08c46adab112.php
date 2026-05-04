<!-- resources/views/notifications/show.blade.php -->


<?php $__env->startSection('title', 'Détail notification'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4 fade-in">
    <div>
        <h1 class="h2 mb-1">
            <i class="fas fa-bell me-2 text-primary"></i>Détail de la notification
        </h1>
    </div>
    <div>
        <a href="<?php echo e(route('notifications.index')); ?>" class="btn btn-outline-custom me-2">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
        <form action="<?php echo e(route('notifications.destroy', $notification->id)); ?>" method="POST" class="d-inline">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-danger-custom" onclick="return confirm('Supprimer cette notification ?')">
                <i class="fas fa-trash me-1"></i> Supprimer
            </button>
        </form>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <?php
            $data = $notification->data;
            $notificationType = $data['type'] ?? 'general';
            $isUnread = $notification->unread();
            
            $typeConfig = [
                'low_stock' => [
                    'name' => 'Alerte Stock Faible',
                    'icon' => 'exclamation-triangle',
                    'color' => 'warning',
                    'bgSoft' => 'rgba(245, 158, 11, 0.1)'
                ],
                'stock_movement' => [
                    'name' => 'Mouvement de Stock',
                    'icon' => 'exchange-alt',
                    'color' => 'info',
                    'bgSoft' => 'rgba(59, 130, 246, 0.1)'
                ],
                'invoice_generated' => [
                    'name' => 'Facture Générée',
                    'icon' => 'file-invoice',
                    'color' => 'success',
                    'bgSoft' => 'rgba(16, 185, 129, 0.1)'
                ],
                'default' => [
                    'name' => 'Notification',
                    'icon' => 'bell',
                    'color' => 'secondary',
                    'bgSoft' => 'rgba(100, 116, 139, 0.1)'
                ]
            ];
            
            $config = $typeConfig[$notificationType] ?? $typeConfig['default'];
        ?>
        
        <div class="card-classic">
            <div class="card-header-classic d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <span class="badge bg-<?php echo e($config['color']); ?> me-2">
                        <i class="fas fa-<?php echo e($config['icon']); ?> me-1"></i> <?php echo e($config['name']); ?>

                    </span>
                    <?php if($isUnread): ?>
                        <span class="badge bg-primary">Non lue</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Lue</span>
                    <?php endif; ?>
                </div>
                <small class="text-muted">
                    <i class="fas fa-clock me-1"></i> <?php echo e($notification->created_at->format('d/m/Y H:i:s')); ?>

                </small>
            </div>
            
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: <?php echo e($config['bgSoft']); ?>;">
                        <i class="fas fa-<?php echo e($config['icon']); ?> fa-3x text-<?php echo e($config['color']); ?>"></i>
                    </div>
                    <h3 class="text-<?php echo e($config['color']); ?>"><?php echo e($data['message'] ?? 'Notification'); ?></h3>
                </div>
                
                <?php if($notificationType == 'low_stock'): ?>
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i> Détails de l'alerte stock</h5>
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Produit :</th>
                                <td><?php echo e($data['product_name'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <th>SKU :</th>
                                <td><?php echo e($data['sku'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <th>Stock actuel :</th>
                                <td><span class="badge bg-danger"><?php echo e($data['current_stock'] ?? 0); ?> unités</span></td>
                            </tr>
                            <tr>
                                <th>Stock minimum :</th>
                                <td><?php echo e($data['min_stock'] ?? 0); ?> unités</span></td>
                            </tr>
                            <tr>
                                <th>Quantité manquante :</th>
                                <td class="text-danger fw-bold"><?php echo e(($data['min_stock'] ?? 0) - ($data['current_stock'] ?? 0)); ?> unités</span></td>
                            </tr>
                        </table>
                        
                        <?php if(isset($data['current_stock']) && isset($data['min_stock']) && $data['min_stock'] > 0): ?>
                            <?php
                                $percentage = min(100, ($data['current_stock'] / $data['min_stock']) * 100);
                            ?>
                            <div class="mt-3">
                                <small class="text-muted">Niveau de stock</small>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-<?php echo e($percentage < 50 ? 'danger' : ($percentage < 75 ? 'warning' : 'success')); ?>" 
                                         style="width: <?php echo e($percentage); ?>%;"></div>
                                </div>
                                <small class="text-muted"><?php echo e(number_format($percentage, 1)); ?>%</small>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                <?php elseif($notificationType == 'stock_movement'): ?>
                    <div class="alert alert-info">
                        <h5><i class="fas fa-exchange-alt me-2"></i> Détails du mouvement</h5>
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Produit :</th>
                                <td><?php echo e($data['product_name'] ?? 'N/A'); ?></span></td>
                            </tr>
                            <tr>
                                <th>Type :</th>
                                <td>
                                    <?php if(($data['movement_type'] ?? '') == 'in'): ?>
                                        <span class="badge bg-success">Entrée</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Sortie</span>
                                    <?php endif; ?>
                                 </span>
                            </td>
                            <tr>
                                <th>Quantité :</th>
                                <td><?php echo e($data['quantity'] ?? 0); ?> unités</span></td>
                            </tr>
                            <?php if(isset($data['unit_price'])): ?>
                            <tr>
                                <th>Prix unitaire :</th>
                                <td><?php echo e(number_format($data['unit_price'], 2)); ?> DH</span></td>
                            </tr>
                            <?php endif; ?>
                            <?php if(isset($data['total_price'])): ?>
                            <tr>
                                <th>Total :</th>
                                <td class="fw-bold text-<?php echo e($config['color']); ?>"><?php echo e(number_format($data['total_price'], 2)); ?> DH</span></td>
                            </tr>
                            <?php endif; ?>
                            <?php if(isset($data['reason'])): ?>
                            <tr>
                                <th>Motif :</th>
                                <td><?php echo e($data['reason']); ?></span></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    
                <?php elseif($notificationType == 'invoice_generated'): ?>
                    <div class="alert alert-success">
                        <h5><i class="fas fa-file-invoice me-2"></i> Détails de la facture</h5>
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Numéro facture :</th>
                                <td><strong class="text-<?php echo e($config['color']); ?>"><?php echo e($data['invoice_number'] ?? 'N/A'); ?></strong></td>
                            </tr>
                            <tr>
                                <th>Produit :</th>
                                <td><?php echo e($data['product_name'] ?? 'N/A'); ?></span></td>
                            </tr>
                            <tr>
                                <th>Quantité :</th>
                                <td><?php echo e($data['quantity'] ?? 0); ?> unités</span></td>
                            </tr>
                            <?php if(isset($data['unit_price'])): ?>
                            <tr>
                                <th>Prix unitaire :</th>
                                <td><?php echo e(number_format($data['unit_price'], 2)); ?> DH</span></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th>Total :</th>
                                <td class="fw-bold text-<?php echo e($config['color']); ?>"><?php echo e(number_format($data['total_amount'] ?? 0, 2)); ?> DH</span></td>
                            </tr>
                            <?php if(isset($data['customer_supplier'])): ?>
                            <tr>
                                <th>Client/Fournisseur :</th>
                                <td><?php echo e($data['customer_supplier']); ?></span></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($data['action_url'])): ?>
                    <div class="text-center mt-4">
                        <a href="<?php echo e(url($data['action_url'])); ?>" class="btn btn-primary-custom">
                            <i class="fas fa-eye me-1"></i> Voir les détails complets
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="card-footer text-muted">
                <div class="d-flex justify-content-between">
                    <small>
                        <i class="fas fa-clock me-1"></i>
                        Reçue le <?php echo e($notification->created_at->format('d/m/Y à H:i:s')); ?>

                    </small>
                    <?php if($notification->read_at): ?>
                        <small>
                            <i class="fas fa-check-circle me-1 text-success"></i>
                            Lue le <?php echo e($notification->read_at->format('d/m/Y à H:i:s')); ?>

                        </small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Actions supplémentaires -->
        <div class="text-center mt-4">
            <?php if($isUnread): ?>
                <form action="<?php echo e(route('notifications.mark-as-read', $notification->id)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline-custom">
                        <i class="fas fa-check me-1"></i> Marquer comme lu
                    </button>
                </form>
            <?php endif; ?>
            <form action="<?php echo e(route('notifications.destroy', $notification->id)); ?>" method="POST" class="d-inline ms-2">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-outline-custom text-danger" onclick="return confirm('Supprimer cette notification ?')">
                    <i class="fas fa-trash me-1"></i> Supprimer définitivement
                </button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views\notifications\show.blade.php ENDPATH**/ ?>