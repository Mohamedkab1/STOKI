<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'info',
    'icon' => null,
]));

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

foreach (array_filter(([
    'variant' => 'info',
    'icon' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $variants = [
        'primary' => 'bg-brand-primary/10 text-brand-primary border-brand-primary/20 dark:bg-brand-primary/20 dark:text-brand-primary dark:border-brand-primary/40',
        'success' => 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-500/15 dark:text-emerald-400 dark:border-emerald-500/30',
        'danger' => 'bg-rose-50 text-rose-700 border-rose-100 dark:bg-rose-500/15 dark:text-rose-400 dark:border-rose-500/30',
        'warning' => 'bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-500/15 dark:text-amber-400 dark:border-amber-500/30',
        'info' => 'bg-sky-50 text-sky-700 border-sky-100 dark:bg-sky-500/15 dark:text-sky-400 dark:border-sky-500/30',
        'neutral' => 'bg-slate-50 text-slate-700 border-slate-100 dark:bg-white/5 dark:text-slate-300 dark:border-white/10',
    ];
    
    $classes = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tight border shadow-sm transition-all duration-300 " . $variants[$variant];
?>

<span <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <?php if($icon): ?>
        <i class="<?php echo e($icon); ?> text-[9px]"></i>
    <?php endif; ?>
    <?php echo e($slot); ?>

</span>
<?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views/components/ui/badge.blade.php ENDPATH**/ ?>