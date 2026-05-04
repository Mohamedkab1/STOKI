<?php $__env->startSection('title', 'Factures'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-in">

  <!-- Header -->
  <div class="page-header">
      <div>
          <h1 class="page-title">Factures</h1>
          <p class="text-text-muted mt-1 font-medium italic opacity-80">Historique complet de vos transactions.</p>
      </div>
      <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['href' => ''.e(route('products.index')).'','tag' => 'a','size' => 'sm','icon' => 'fas fa-plus shadow-sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('products.index')).'','tag' => 'a','size' => 'sm','icon' => 'fas fa-plus shadow-sm']); ?>
          Nouvelle facture
       <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
  </div>

  <!-- Stats mini row -->
  <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr);">
      <div class="stat-card">
          <div class="stat-icon stat-icon-blue">
              <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
          </div>
          <div>
              <div class="stat-label">Total factures</div>
              <div class="stat-value"><?php echo e($invoices->total()); ?></div>
          </div>
      </div>
      <div class="stat-card">
          <div class="stat-icon stat-icon-green">
              <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
          </div>
          <div>
              <div class="stat-label">Montant total (Payé)</div>
              <div class="stat-value"><?php echo e(number_format(\App\Models\Invoice::where('payment_status', 'paid')->sum('total_amount'), 2)); ?> <span class="text-xs text-text-secondary">MAD</span></div>
          </div>
      </div>
      <div class="stat-card">
          <div class="stat-icon stat-icon-orange">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
          </div>
          <div>
              <div class="stat-label">En attente</div>
              <div class="stat-value"><?php echo e(\App\Models\Invoice::where('payment_status', 'pending')->count()); ?></div>
          </div>
      </div>
  </div>

  <!-- Filters -->
  <form action="<?php echo e(route('invoices.index')); ?>" method="GET" class="filters-bar">
      <div class="filter-search" style="min-width: 250px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          <input type="text" name="search" placeholder="N° facture ou client..." value="<?php echo e(request('search')); ?>">
      </div>
      
      <select name="payment_status" class="filter-select">
          <option value="">Tous les statuts</option>
          <option value="paid" <?php echo e(request('payment_status') == 'paid' ? 'selected' : ''); ?>>Payée</option>
          <option value="pending" <?php echo e(request('payment_status') == 'pending' ? 'selected' : ''); ?>>En attente</option>
      </select>
      
      <div style="display:flex; align-items:center; gap:6px;">
          <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="filter-select" style="min-width: 130px;">
          <span class="text-text-secondary">-</span>
          <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="filter-select" style="min-width: 130px;">
      </div>

      <button type="submit" class="btn-filter">Filtrer</button>
      <a href="<?php echo e(route('invoices.index')); ?>" class="btn-reset" title="Réinitialiser">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path></svg>
      </a>
  </form>

  <!-- Table -->
  <div class="table-responsive">
      <table class="modern-table">
          <thead>
              <tr>
                  <th>Numéro</th>
                  <th>Nature</th>
                  <th>Client / Fournisseur</th>
                  <th>Montant</th>
                  <th>Statut</th>
                  <th>Date</th>
                  <th width="100">Actions</th>
              </tr>
          </thead>
          <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <tr>
                      <td class="font-medium text-text-primary">#<?php echo e($inv->invoice_number); ?></td>
                      <td>
                          <?php if($inv->type === 'sale'): ?>
                            Sortie (Vente)
                          <?php else: ?>
                            Entrée (Achat)
                          <?php endif; ?>
                      </td>
                      <td><?php echo e($inv->customer_supplier ?: '—'); ?></td>
                      <td class="font-medium"><?php echo e(number_format($inv->total_amount, 2)); ?> MAD</td>
                      <td>
                          <?php if($inv->payment_status === 'paid'): ?>
                              <span class="stock-badge badge-success relative top-0 right-0">Payée</span>
                          <?php else: ?>
                              <span class="stock-badge badge-warning relative top-0 right-0">En attente</span>
                          <?php endif; ?>
                      </td>
                      <td class="text-text-secondary"><?php echo e($inv->invoice_date->format('d/m/Y')); ?></td>
                      <td>
                          <div class="flex gap-1.5">
                              <a href="<?php echo e(route('invoices.show', $inv)); ?>" class="act-btn act-view" title="Voir">
                                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                              </a>
                              <a href="<?php echo e(route('invoices.pdf', $inv)); ?>" class="act-btn" title="PDF">
                                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="text-text-secondary" stroke="currentColor" stroke-width="2"><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10.4 12.6a2 2 0 1 1 3 3L8 21l-4 1 1-4Z"></path><path d="M18 18h.01"></path><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h4"></path></svg>
                              </a>
                          </div>
                      </td>
                  </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <tr>
                      <td colspan="7" class="text-center text-text-secondary py-10">
                          Aucune facture trouvée.
                      </td>
                  </tr>
              <?php endif; ?>
          </tbody>
      </table>
  </div>

  <?php if($invoices->hasPages()): ?>
      <div class="pt-6">
          <?php echo e($invoices->links()); ?>

      </div>
  <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views\invoices\index.blade.php ENDPATH**/ ?>