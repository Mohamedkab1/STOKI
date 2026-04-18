<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['active' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['active' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<aside id="sidebar" class="app-sidebar custom-scrollbar">
    <!-- Brand -->
    <div class="h-20 flex items-center justify-between px-6 border-b border-border-subtle">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-brand-primary rounded-xl flex items-center justify-center shadow-lg shadow-brand-primary/10">
                <i class="fas fa-cubes text-xl text-white"></i>
            </div>
            <span class="text-xl font-black tracking-tight text-text-main">STOKI<span class="text-brand-primary">ERP</span></span>
        </div>
        <!-- Bouton fermé optionnel s'il est désiré sur mobile, géré par le JS -->
        <button id="sidebar-close" class="hamburger-btn" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100%-160px)]">
        <div class="px-4 py-2 text-[10px] font-bold text-text-muted uppercase tracking-widest opacity-60">Général</div>
        
        <?php if (isset($component)) { $__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.nav-link','data' => ['href' => ''.e(route('dashboard')).'','active' => request()->routeIs('dashboard'),'icon' => 'fas fa-chart-line']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('dashboard')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('dashboard')),'icon' => 'fas fa-chart-line']); ?>
            Tableau de Bord
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8)): ?>
<?php $attributes = $__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8; ?>
<?php unset($__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8)): ?>
<?php $component = $__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8; ?>
<?php unset($__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8); ?>
<?php endif; ?>

        <div class="px-4 py-2 mt-4 text-[10px] font-bold text-text-muted uppercase tracking-widest opacity-60">Inventaire</div>
        
        <?php if (isset($component)) { $__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.nav-link','data' => ['href' => ''.e(route('products.index')).'','active' => request()->routeIs('products.*'),'icon' => 'fas fa-box']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('products.index')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('products.*')),'icon' => 'fas fa-box']); ?>
            Produits
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8)): ?>
<?php $attributes = $__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8; ?>
<?php unset($__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8)): ?>
<?php $component = $__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8; ?>
<?php unset($__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.nav-link','data' => ['href' => ''.e(route('categories.index')).'','active' => request()->routeIs('categories.*'),'icon' => 'fas fa-tags']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('categories.index')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('categories.*')),'icon' => 'fas fa-tags']); ?>
            Catégories
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8)): ?>
<?php $attributes = $__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8; ?>
<?php unset($__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8)): ?>
<?php $component = $__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8; ?>
<?php unset($__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8); ?>
<?php endif; ?>

        <div class="px-4 py-2 mt-4 text-[10px] font-bold text-text-muted uppercase tracking-widest opacity-60">Opérations</div>

        <?php if (isset($component)) { $__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.nav-link','data' => ['href' => ''.e(route('invoices.index')).'','icon' => 'fas fa-file-invoice-dollar','active' => request()->routeIs('invoices.*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('invoices.index')).'','icon' => 'fas fa-file-invoice-dollar','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('invoices.*'))]); ?>
            Factures
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8)): ?>
<?php $attributes = $__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8; ?>
<?php unset($__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8)): ?>
<?php $component = $__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8; ?>
<?php unset($__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8); ?>
<?php endif; ?>

        <div class="px-4 py-2 mt-4 text-[10px] font-bold text-text-muted uppercase tracking-widest opacity-60">Système</div>

        <?php if (isset($component)) { $__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.nav-link','data' => ['href' => ''.e(route('notifications.all')).'','icon' => 'fas fa-bell','active' => request()->routeIs('notifications.*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('notifications.all')).'','icon' => 'fas fa-bell','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('notifications.*'))]); ?>
            Notifications
            <?php $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); ?>
            <?php if($unreadCount > 0): ?>
                <span class="ml-auto bg-brand-primary text-white text-[9px] font-black px-2 py-0.5 rounded-full shadow-sm"><?php echo e($unreadCount); ?></span>
            <?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8)): ?>
<?php $attributes = $__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8; ?>
<?php unset($__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8)): ?>
<?php $component = $__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8; ?>
<?php unset($__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.nav-link','data' => ['href' => ''.e(route('profile')).'','icon' => 'fas fa-user-gear','active' => request()->routeIs('profile*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('profile')).'','icon' => 'fas fa-user-gear','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('profile*'))]); ?>
            Paramètres
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8)): ?>
<?php $attributes = $__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8; ?>
<?php unset($__attributesOriginalb65f2aa001741ba3dcf3034163b2c9e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8)): ?>
<?php $component = $__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8; ?>
<?php unset($__componentOriginalb65f2aa001741ba3dcf3034163b2c9e8); ?>
<?php endif; ?>
    </nav>

    <!-- App Info / Logout -->
    <div class="absolute bottom-0 left-0 w-full p-6 border-t border-border-subtle bg-card/50 backdrop-blur-sm">
        <form action="<?php echo e(route('logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="flex items-center gap-3 text-text-muted hover:text-rose-500 transition-colors w-full text-sm font-bold">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </button>
        </form>
    </div>
</aside>
<?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views/components/layout/sidebar.blade.php ENDPATH**/ ?>