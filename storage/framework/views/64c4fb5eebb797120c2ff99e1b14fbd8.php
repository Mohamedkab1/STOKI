<?php $__env->startSection('title', 'Catalogue Produits'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-in">

  <!-- Header Section -->
  <div class="page-header">
      <div>
          <h1 class="page-title">Catalogue Produits</h1>
          <p class="text-text-muted mt-1 font-medium italic opacity-80">Gérez votre inventaire et vos ventes en temps réel.</p>
      </div>
      <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['href' => ''.e(route('products.create')).'','tag' => 'a','size' => 'sm','icon' => 'fas fa-plus shadow-sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('products.create')).'','tag' => 'a','size' => 'sm','icon' => 'fas fa-plus shadow-sm']); ?>
          Nouveau Produit
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

  
  <form action="<?php echo e(route('products.index')); ?>" method="GET" class="filters-bar">
    <div class="filter-search">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"></circle>
        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
      </svg>
      <input type="text" name="search" placeholder="Recherche par nom, SKU..." value="<?php echo e(request('search')); ?>">
    </div>

    <select name="category" class="filter-select">
      <option value="">Toutes les catégories</option>
      <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category') == $cat->id ? 'selected' : ''); ?>>
          <?php echo e($cat->name); ?>

        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <button type="submit" class="btn-filter">Filtrer</button>

    <a href="<?php echo e(route('products.index')); ?>" class="btn-reset" title="Réinitialiser">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
        <path d="M3 3v5h5"></path>
      </svg>
    </a>
  </form>

  
  <div class="products-grid">
    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <div class="product-card">

        
        <div class="product-card-img">
          <?php if($product->image): ?>
            <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>">
          <?php else: ?>
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" class="text-slate-300" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
              <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
            </svg>
          <?php endif; ?>

          
          <span class="stock-badge <?php echo e($product->quantity > 0 ? 'badge-success' : 'badge-danger'); ?>">
            Stock: <?php echo e($product->quantity); ?>

          </span>
        </div>

        
        <div class="product-card-body">
          <div class="product-card-top">
            <span class="product-cat"><?php echo e($product->category->name ?? '—'); ?></span>
            <span class="product-sku">#<?php echo e($product->sku); ?></span>
          </div>

          <h3 class="product-name" title="<?php echo e($product->name); ?>"><?php echo e($product->name); ?></h3>
          <p class="product-desc" title="<?php echo e($product->description ?? 'Aucune description'); ?>">
            <?php echo e($product->description ?? ''); ?>

          </p>

          <div class="product-card-footer">
            <div class="product-price">
              <?php echo e(number_format($product->price, 2)); ?>

              <span>MAD</span>
            </div>
            
            <div class="product-actions">
              <a href="<?php echo e(route('products.show', $product)); ?>" class="act-btn act-view" title="Voir">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
              </a>
              <a href="<?php echo e(route('products.edit', $product)); ?>" class="act-btn act-edit" title="Modifier">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
              </a>
              <form action="<?php echo e(route('products.destroy', $product)); ?>" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="act-btn act-delete" title="Supprimer">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                  </svg>
                </button>
              </form>
            </div>
          </div>
        </div>

      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="products-empty">
        Aucun produit trouvé.
      </div>
    <?php endif; ?>
  </div>

  <!-- Pagination -->
  <?php if($products->hasPages()): ?>
    <div class="pt-8">
        <?php echo e($products->links()); ?>

    </div>
  <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views/products/index.blade.php ENDPATH**/ ?>