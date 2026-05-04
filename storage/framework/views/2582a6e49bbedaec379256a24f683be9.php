

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'searchPlaceholder' => 'Rechercher...',
    'searchId' => 'tableSearch',
    'emptyIcon' => 'fas fa-inbox',
    'emptyTitle' => 'Aucune donnée',
    'emptyDescription' => 'Aucun élément à afficher pour le moment.',
    'showSearch' => true,
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
    'searchPlaceholder' => 'Rechercher...',
    'searchId' => 'tableSearch',
    'emptyIcon' => 'fas fa-inbox',
    'emptyTitle' => 'Aucune donnée',
    'emptyDescription' => 'Aucun élément à afficher pour le moment.',
    'showSearch' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => 'data-table-wrapper'])); ?>>
    
    <?php if($showSearch || isset($actions)): ?>
        <div class="data-table__search-bar">
            <?php if($showSearch): ?>
                <div class="data-table__search-input">
                    <i class="fas fa-search"></i>
                    <input 
                        type="text" 
                        id="<?php echo e($searchId); ?>" 
                        placeholder="<?php echo e($searchPlaceholder); ?>"
                        autocomplete="off"
                        oninput="filterTable(this)"
                    >
                </div>
            <?php endif; ?>

            <?php if(isset($actions)): ?>
                <div class="data-table__actions">
                    <?php echo e($actions); ?>

                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    
    <div style="overflow-x: auto;">
        <table class="data-table" id="<?php echo e($searchId); ?>Table">
            <?php if(isset($head)): ?>
                <thead>
                    <tr><?php echo e($head); ?></tr>
                </thead>
            <?php endif; ?>

            <tbody>
                <?php echo e($slot); ?>

            </tbody>
        </table>
    </div>

    
    <?php if(isset($empty)): ?>
        <?php echo e($empty); ?>

    <?php endif; ?>

    
    <?php if(isset($pagination)): ?>
        <div class="data-table__pagination">
            <?php echo e($pagination); ?>

        </div>
    <?php endif; ?>
</div>


<?php if (! $__env->hasRenderedOnce('07c8053d-8e37-48e8-b17a-bf99cc085e32')): $__env->markAsRenderedOnce('07c8053d-8e37-48e8-b17a-bf99cc085e32'); ?>
<?php $__env->startPush('scripts'); ?>
<script>
/**
 * Filtrer les lignes de la table en fonction de la recherche
 * @param {HTMLInputElement} input - Champ de recherche
 */
function filterTable(input) {
    var filter = input.value.toLowerCase();
    var tableId = input.id + 'Table';
    var table = document.getElementById(tableId);
    if (!table) return;

    var rows = table.querySelectorAll('tbody tr');
    var visibleCount = 0;

    rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        if (text.indexOf(filter) > -1) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Afficher/masquer l'état vide
    var emptyState = table.closest('.data-table-wrapper')?.querySelector('.data-table__empty');
    if (emptyState) {
        emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
    }
}

/**
 * Tri des colonnes cliquables
 */
document.addEventListener('click', function(e) {
    var th = e.target.closest('th.sortable');
    if (!th) return;

    var table = th.closest('table');
    var colIndex = Array.from(th.parentNode.children).indexOf(th);
    var tbody = table.querySelector('tbody');
    var rows = Array.from(tbody.querySelectorAll('tr'));
    var isAsc = th.classList.contains('sort-asc');

    // Réinitialiser les autres colonnes
    th.parentNode.querySelectorAll('th').forEach(function(header) {
        header.classList.remove('sort-asc', 'sort-desc');
        var icon = header.querySelector('.sort-icon');
        if (icon) icon.className = 'fas fa-sort sort-icon';
    });

    // Appliquer le tri
    var direction = isAsc ? 'desc' : 'asc';
    th.classList.add('sort-' + direction);
    var icon = th.querySelector('.sort-icon');
    if (icon) icon.className = 'fas fa-sort-' + direction + ' sort-icon';

    // Trier les lignes
    rows.sort(function(a, b) {
        var aText = a.children[colIndex]?.textContent.trim() || '';
        var bText = b.children[colIndex]?.textContent.trim() || '';

        // Essayer de comparer comme nombres
        var aNum = parseFloat(aText.replace(/[^\d.-]/g, ''));
        var bNum = parseFloat(bText.replace(/[^\d.-]/g, ''));

        if (!isNaN(aNum) && !isNaN(bNum)) {
            return direction === 'asc' ? aNum - bNum : bNum - aNum;
        }
        return direction === 'asc' ? aText.localeCompare(bText, 'fr') : bText.localeCompare(aText, 'fr');
    });

    // Réinsérer les lignes triées
    rows.forEach(function(row) { tbody.appendChild(row); });
});
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views\components\data-table.blade.php ENDPATH**/ ?>