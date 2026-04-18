{{-- 
    Composant Table de Données (x-data-table)
    Table stylisée avec recherche, tri, pagination et actions
    
    Props:
    - searchPlaceholder: placeholder du champ de recherche
    - searchId: id unique pour le champ de recherche
    - emptyIcon: icône quand la table est vide
    - emptyTitle: titre quand la table est vide
    - emptyDescription: description quand la table est vide
    
    Slots:
    - actions: boutons d'action en haut à droite
    - head: en-têtes de colonnes (th)
    - body: contenu du tableau (tr/td)
    - pagination: liens de pagination
    - empty: contenu personnalisé quand vide (optionnel)
--}}

@props([
    'searchPlaceholder' => 'Rechercher...',
    'searchId' => 'tableSearch',
    'emptyIcon' => 'fas fa-inbox',
    'emptyTitle' => 'Aucune donnée',
    'emptyDescription' => 'Aucun élément à afficher pour le moment.',
    'showSearch' => true,
])

<div {{ $attributes->merge(['class' => 'data-table-wrapper']) }}>
    {{-- Barre de recherche et actions --}}
    @if($showSearch || isset($actions))
        <div class="data-table__search-bar">
            @if($showSearch)
                <div class="data-table__search-input">
                    <i class="fas fa-search"></i>
                    <input 
                        type="text" 
                        id="{{ $searchId }}" 
                        placeholder="{{ $searchPlaceholder }}"
                        autocomplete="off"
                        oninput="filterTable(this)"
                    >
                </div>
            @endif

            @if(isset($actions))
                <div class="data-table__actions">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    {{-- Table --}}
    <div style="overflow-x: auto;">
        <table class="data-table" id="{{ $searchId }}Table">
            @if(isset($head))
                <thead>
                    <tr>{{ $head }}</tr>
                </thead>
            @endif

            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>

    {{-- État vide (affiché quand le slot est vide ou via JS) --}}
    @if(isset($empty))
        {{ $empty }}
    @endif

    {{-- Pagination --}}
    @if(isset($pagination))
        <div class="data-table__pagination">
            {{ $pagination }}
        </div>
    @endif
</div>

{{-- Script de filtrage intégré --}}
@once
@push('scripts')
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
@endpush
@endonce
