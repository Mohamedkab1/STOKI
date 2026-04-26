<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['active' => false, 'icon' => null]));

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

foreach (array_filter((['active' => false, 'icon' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a <?php echo e($attributes); ?> class="<?php echo \Illuminate\Support\Arr::toCssClasses([
    'flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 group relative no-underline hover:no-underline',
    'text-brand-primary bg-sidebar-active' => $active,
    'text-text-muted hover:text-text-main hover:bg-sidebar-active' => !$active,
]); ?>">
    <?php if($active): ?>
        <div class="absolute inset-y-2 left-0 w-1 bg-brand-primary rounded-r-full shadow-lg shadow-brand-primary/40"></div>
    <?php endif; ?>
    
    <?php if($icon): ?>
        <i class="<?php echo e($icon); ?> <?php echo e($active ? 'text-brand-primary scale-110' : 'text-text-muted opacity-60 group-hover:opacity-100 group-hover:scale-110'); ?> transition-all duration-300"></i>
    <?php endif; ?>
    
    <span class="tracking-tight"><?php echo e($slot); ?></span>

    <?php if(!$active): ?>
        <div class="absolute inset-0 bg-brand-primary/0 group-active:bg-brand-primary/5 rounded-xl transition-colors"></div>
    <?php endif; ?>
</a>
<?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views/components/layout/nav-link.blade.php ENDPATH**/ ?>