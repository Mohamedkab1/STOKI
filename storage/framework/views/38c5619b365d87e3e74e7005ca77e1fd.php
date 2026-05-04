<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'disabled' => false,
    'error' => false,
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
    'disabled' => false,
    'error' => false,
    'icon' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="relative group">
    <?php if($icon): ?>
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-text-muted opacity-50 group-focus-within:text-brand-primary group-focus-within:opacity-100 transition-all duration-300">
            <i class="<?php echo e($icon); ?> text-xs"></i>
        </div>
    <?php endif; ?>

    <input <?php echo e($disabled ? 'disabled' : ''); ?> <?php echo $attributes->merge(['class' => '
        w-full rounded-xl border text-sm font-bold transition-all duration-300
        ' . ($icon ? 'pl-11' : 'pl-5') . '
        pr-5 py-3 bg-card
        focus:ring-2 focus:ring-offset-0 focus:outline-none
        ' . ($error 
            ? 'border-rose-300 text-rose-500 placeholder-rose-200 focus:ring-rose-500/10 focus:border-rose-500' 
            : 'border-border-subtle text-text-main placeholder-text-muted/40 focus:ring-brand-primary/10 focus:border-brand-primary h-12'
        ) . '
        disabled:bg-sidebar-active/50 disabled:text-text-muted/50 disabled:cursor-not-allowed
    ']); ?>>
</div>
<?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views\components\forms\input.blade.php ENDPATH**/ ?>