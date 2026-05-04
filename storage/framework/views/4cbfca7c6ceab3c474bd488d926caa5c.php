

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'icon' => 'fas fa-chart-bar',
    'iconColor' => 'indigo',
    'label' => 'Statistique',
    'value' => '0',
    'badge' => null,
    'badgeVariant' => 'neutral',
    'trend' => null,
    'trendDirection' => 'up',
    'suffix' => null,
    'valueClass' => '',
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
    'icon' => 'fas fa-chart-bar',
    'iconColor' => 'indigo',
    'label' => 'Statistique',
    'value' => '0',
    'badge' => null,
    'badgeVariant' => 'neutral',
    'trend' => null,
    'trendDirection' => 'up',
    'suffix' => null,
    'valueClass' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => 'stat-card'])); ?>>
    
    <div class="stat-card__header">
        <div class="stat-card__icon stat-card__icon--<?php echo e($iconColor); ?>">
            <i class="<?php echo e($icon); ?>"></i>
        </div>
        <?php if($badge): ?>
            <span class="stat-card__badge stat-card__badge--<?php echo e($badgeVariant); ?>"><?php echo e($badge); ?></span>
        <?php endif; ?>
    </div>

    
    <div>
        <h3 class="stat-card__value <?php echo e($valueClass); ?>">
            <?php echo e($value); ?>

            <?php if($suffix): ?>
                <span style="font-size: 12px; font-weight: 700; color: var(--text-muted); opacity: 0.6;"><?php echo e($suffix); ?></span>
            <?php endif; ?>
        </h3>
        <p class="stat-card__label"><?php echo e($label); ?></p>

        
        <?php if($trend): ?>
            <span class="stat-card__trend stat-card__trend--<?php echo e($trendDirection); ?>">
                <i class="fas fa-arrow-<?php echo e($trendDirection); ?>"></i>
                <?php echo e($trend); ?>

            </span>
        <?php endif; ?>
    </div>

    
    <div class="stat-card__decor">
        <i class="<?php echo e($icon); ?>"></i>
    </div>

    
    <?php echo e($slot); ?>

</div>
<?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views\components\stats-card.blade.php ENDPATH**/ ?>