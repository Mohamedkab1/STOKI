<?php $__env->startSection('title', 'Tableau de bord'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-in">

  <!-- Header -->
  <div class="page-header">
      <div>
          <h1 class="page-title">Tableau de bord</h1>
          <p class="text-text-muted mt-1 font-medium italic opacity-80">Aperçu global de vos activités et de l'état de votre stock.</p>
      </div>
      <div class="text-sm font-medium text-text-secondary border border-border-color rounded-lg px-4 py-2 bg-bg-surface">
          <?php echo e(now()->isoFormat('LL')); ?>

      </div>
  </div>

  <!-- Stats Grid -->
  <div class="stats-grid">
      <div class="stat-card">
          <div class="stat-icon stat-icon-blue">
              <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
          </div>
          <div>
              <div class="stat-label">Total produits</div>
              <div class="stat-value"><?php echo e($totalProducts); ?></div>
          </div>
      </div>
      
      <div class="stat-card">
          <div class="stat-icon stat-icon-orange">
              <svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
          </div>
          <div>
              <div class="stat-label">Stock faible</div>
              <div class="stat-value"><?php echo e($lowStockCount); ?></div>
          </div>
      </div>
      
      <div class="stat-card">
          <div class="stat-icon stat-icon-green">
              <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
          </div>
          <div>
              <div class="stat-label">Valeur totale stock</div>
              <div class="stat-value"><?php echo e(number_format($totalStockValue, 2)); ?> <span class="text-xs text-text-secondary">MAD</span></div>
          </div>
      </div>
      
      <div class="stat-card">
          <div class="stat-icon stat-icon-purple">
              <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
          </div>
          <div>
              <div class="stat-label">Chiffre d'affaires</div>
              <div class="stat-value"><?php echo e(number_format($caTotal, 2)); ?> <span class="text-xs text-text-secondary">MAD</span></div>
          </div>
      </div>
  </div>

  <!-- Chart Card -->
  <div class="chart-card">
    
    <div class="chart-card-header">
      <div class="chart-card-title">Mouvements de stock</div>

      <div class="chart-card-right">
        
        <form action="<?php echo e(route('dashboard')); ?>" method="GET" class="chart-filter-form">
          <input type="date" name="date_from" value="<?php echo e($dateFrom->format('Y-m-d')); ?>" required>
          <span class="chart-filter-sep">→</span>
          <input type="date" name="date_to" value="<?php echo e($dateTo->format('Y-m-d')); ?>" required>
          <button type="submit" class="btn-chart-apply">Appliquer</button>
        </form>

        
        <div class="chart-legend">
          <div class="chart-legend-item">
            <div class="chart-legend-dot dot-entree"></div>
            Entrées
          </div>
          <div class="chart-legend-item">
            <div class="chart-legend-dot dot-sortie"></div>
            Sorties
          </div>
        </div>
      </div>
    </div>

    
    <div class="chart-totals">
      <div class="chart-total-item">
        <span class="chart-total-val entree"><?php echo e($totalEntrees); ?></span>
        <span class="chart-total-lbl">Total entrées</span>
      </div>
      <div class="chart-total-divider"></div>
      <div class="chart-total-item">
        <span class="chart-total-val sortie"><?php echo e($totalSorties); ?></span>
        <span class="chart-total-lbl">Total sorties</span>
      </div>
    </div>

    
    <div class="chart-body">
      <canvas id="mouvementsChart"></canvas>
    </div>
  </div>

  <!-- Main Layout 2/3 + 1/3 -->
  <div class="dashboard-grid">
      
      <!-- Left: Recent Movements -->
      <div class="space-y-4">
          <h2 class="text-lg font-medium text-text-primary">Derniers mouvements</h2>
          <div class="table-responsive">
              <table class="modern-table">
                  <thead>
                      <tr>
                          <th>Produit</th>
                          <th>Type</th>
                          <th>Quantité</th>
                          <th class="text-right">Date</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php $__empty_1 = true; $__currentLoopData = $recentMovements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mvt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                          <tr>
                              <td>
                                  <div><?php echo e($mvt->product->name); ?></div>
                                  <div class="text-[11px] text-text-secondary">#<?php echo e($mvt->product->sku); ?></div>
                              </td>
                              <td>
                                  <?php if($mvt->type === 'in'): ?>
                                      <span class="stock-badge badge-success relative top-0 right-0">Entrée</span>
                                  <?php elseif($mvt->type === 'out'): ?>
                                      <span class="stock-badge badge-danger relative top-0 right-0">Sortie</span>
                                  <?php else: ?>
                                      <span class="stock-badge badge-warning relative top-0 right-0">Ajustement</span>
                                  <?php endif; ?>
                              </td>
                              <td class="font-medium <?php echo e($mvt->type === 'in' ? 'text-green-500' : ($mvt->type === 'out' ? 'text-red-500' : '')); ?>">
                                  <?php echo e($mvt->type === 'in' ? '+' : ($mvt->type === 'out' ? '-' : '')); ?><?php echo e($mvt->quantity); ?>

                              </td>
                              <td class="text-right text-text-secondary text-[12px]">
                                  <?php echo e($mvt->created_at->format('d/m/Y H:i')); ?>

                              </td>
                          </tr>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                          <tr><td colspan="4" class="text-center text-text-secondary py-6">Aucun mouvement récent</td></tr>
                      <?php endif; ?>
                  </tbody>
              </table>
          </div>
      </div>
      
      <!-- Right: Low Stock -->
      <div class="space-y-4">
          <h2 class="text-lg font-medium text-text-primary">Stock faible</h2>
          <div class="bg-bg-card border border-border-color rounded-xl overflow-hidden">
              <div class="flex flex-col">
                  <?php $__empty_1 = true; $__currentLoopData = $lowStockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                      <div class="flex items-center justify-between p-4 border-b border-border-subtle last:border-b-0 hover:bg-bg-surface transition-colors">
                          <div class="flex items-center gap-3 min-w-0">
                              <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                                  <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                              </div>
                              <div class="min-w-0">
                                  <div class="text-sm font-medium text-text-primary truncate" title="<?php echo e($prod->name); ?>"><?php echo e($prod->name); ?></div>
                                  <div class="text-[11px] text-text-secondary">Min: <?php echo e($prod->min_stock); ?></div>
                              </div>
                          </div>
                          <span class="stock-badge badge-danger relative top-0 right-0 ml-2">Stock: <?php echo e($prod->quantity); ?></span>
                      </div>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                      <div class="p-6 text-center text-text-secondary text-sm">Aucun produit en stock faible.</div>
                  <?php endif; ?>
              </div>
          </div>
      </div>
      
  </div>

  <!-- Bottom: Recent Invoices -->
  <div class="space-y-4 mt-8">
      <h2 class="text-lg font-medium text-text-primary">Dernières factures</h2>
      <div class="table-responsive">
          <table class="modern-table">
              <thead>
                  <tr>
                      <th>Numéro</th>
                      <th>Client</th>
                      <th>Montant</th>
                      <th>Statut</th>
                      <th class="text-right">Date</th>
                  </tr>
              </thead>
              <tbody>
                  <?php $__empty_1 = true; $__currentLoopData = $dernieresFactures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                      <tr>
                          <td class="font-medium text-brand-primary">#<?php echo e($inv->invoice_number); ?></td>
                          <td><?php echo e($inv->customer_supplier ?: '—'); ?></td>
                          <td class="font-medium"><?php echo e(number_format($inv->total_amount, 2)); ?> MAD</td>
                          <td>
                              <?php if($inv->payment_status === 'paid'): ?>
                                  <span class="stock-badge badge-success relative top-0 right-0">Payée</span>
                              <?php else: ?>
                                  <span class="stock-badge badge-warning relative top-0 right-0">En attente</span>
                              <?php endif; ?>
                          </td>
                          <td class="text-right text-text-secondary text-[12px]">
                              <?php echo e($inv->invoice_date->format('d/m/Y')); ?>

                          </td>
                      </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                      <tr><td colspan="5" class="text-center text-text-secondary py-6">Aucune facture récente</td></tr>
                  <?php endif; ?>
              </tbody>
          </table>
      </div>
  </div>

</div>

<?php $__env->startPush('scripts'); ?>
<script>
  window.chartData = {
    labels:  <?php echo json_encode($chartLabels); ?>,
    entrees: <?php echo json_encode($chartEntrees); ?>,
    sorties: <?php echo json_encode($chartSorties); ?>

  };
</script>
<script src="<?php echo e(asset('js/dashboard-chart.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views/dashboard/index.blade.php ENDPATH**/ ?>