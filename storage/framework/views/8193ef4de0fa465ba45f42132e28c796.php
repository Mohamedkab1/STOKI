<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => null,
    'subtitle' => null,
    'padding' => true,
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
    'title' => null,
    'subtitle' => null,
    'padding' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => 'premium-card overflow-hidden transition-colors duration-300'])); ?>>
    <?php if($title || isset($header)): ?>
        <div class="px-6 py-4 border-b border-border-subtle flex items-center justify-between">
            <div>
                <?php if($title): ?>
                    <h3 class="text-sm font-black text-text-main uppercase tracking-tight"><?php echo e($title); ?></h3>
                <?php endif; ?>
                <?php if($subtitle): ?>
                    <p class="text-[10px] font-bold text-text-muted mt-1 uppercase tracking-wider opacity-60"><?php echo e($subtitle); ?></p>
                <?php endif; ?>
            </div>
            <?php if(isset($header)): ?>
                <div><?php echo e($header); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
        'p-6' => $padding,
        'p-0' => !$padding,
    ]); ?>">
        <?php echo e($slot); ?>

    </div>

    <?php if(isset($footer)): ?>
        <div class="px-6 py-4 bg-sidebar-active/50 border-t border-border-subtle">
            <?php echo e($footer); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views/components/ui/card.blade.php ENDPATH**/ ?>