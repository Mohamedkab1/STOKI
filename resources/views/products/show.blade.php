@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="space-y-8 animate-in" x-data="{ activeTab: 'movements' }">
    <!-- Breadcrumbs & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <nav class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-text-muted">
            <a href="{{ route('products.index') }}" class="hover:text-brand-primary transition-colors">Catalogue</a>
            <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
            <span class="text-text-main line-clamp-1">{{ $product->name }}</span>
        </nav>
        <div class="flex items-center gap-2">
            <x-ui.button href="{{ route('products.edit', $product) }}" tag="a" variant="outline" icon="fas fa-edit">
                Modifier
            </x-ui.button>
            <x-ui.button @click="activeTab = 'adjust'; document.getElementById('adjust-section').scrollIntoView({behavior:'smooth'})" icon="fas fa-exchange-alt">
                Mouvement
            </x-ui.button>
            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                @csrf
                @method('DELETE')
                <x-ui.button type="submit" variant="danger" icon="fas fa-trash-alt">
                    Supprimer
                </x-ui.button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Left Column: Media & Core Info -->
        <div class="lg:col-span-5 space-y-8">
            <x-ui.card :padding="false" class="overflow-hidden">
                <div class="aspect-square bg-slate-100 relative group overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                            <i class="fas fa-box text-6xl mb-4"></i>
                            <span class="text-xs font-bold uppercase tracking-widest">Aucune image</span>
                        </div>
                    @endif
                    
                    <div class="absolute top-4 right-4">
                        <x-ui.badge :variant="$product->quantity > $product->min_stock ? 'success' : 'danger'" class="shadow-lg backdrop-blur-md">
                            Stock: {{ $product->quantity }}
                        </x-ui.badge>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <x-ui.badge variant="info">{{ $product->category->name }}</x-ui.badge>
                        <span class="text-[10px] font-mono text-text-muted">#{{ $product->sku }}</span>
                    </div>

                    <h1 class="text-2xl font-black text-text-main mb-2">{{ $product->name }}</h1>
                    <p class="text-sm text-text-muted leading-relaxed mb-6 italic">
                        {{ $product->description ?? 'Aucune description disponible pour ce produit.' }}
                    </p>

                    <div class="bg-sidebar-active/50 rounded-2xl p-5 border border-border-subtle group transition-colors duration-300">
                         <p class="text-[10px] font-bold text-text-muted uppercase tracking-widest mb-1 group-hover:text-brand-primary transition-colors">Prix de vente</p>
                         <div class="flex items-baseline gap-2">
                             <span class="text-3xl font-black text-text-main">{{ number_format($product->price, 2) }}</span>
                             <span class="text-sm font-bold text-text-muted">MAD</span>
                         </div>
                    </div>

                    @if($product->quantity == 0 && $product->stockMovements()->exists())
                        <div class="mt-6 p-4 rounded-xl bg-amber-50 border border-amber-100 text-amber-800">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-exclamation-triangle text-amber-600"></i>
                                <span class="text-xs font-bold uppercase">Stock incohérent</span>
                            </div>
                            <p class="text-xs opacity-80 mb-3">Mouvements détectés mais stock nul. Vous devriez recalculer.</p>
                            <x-ui.button @click="recalculateStock({{ $product->id }})" variant="outline" size="sm" class="w-full bg-white border-amber-200 text-amber-700 hover:bg-amber-100 transition-all">
                                <i class="fas fa-sync-alt mr-2 text-[10px]"></i> Forcer le Recalcul
                            </x-ui.button>
                        </div>
                    @endif
                </div>
            </x-ui.card>

            <x-ui.card title="Détails Techniques">
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl border border-border-subtle bg-sidebar-active/40 transition-colors duration-300">
                        <p class="text-[10px] font-bold text-text-muted uppercase tracking-widest mb-1 text-center">SKU</p>
                        <p class="text-sm font-black text-text-main text-center truncate">{{ $product->sku }}</p>
                    </div>
                    <div class="p-4 rounded-xl border border-border-subtle bg-sidebar-active/40 transition-colors duration-300">
                        <p class="text-[10px] font-bold text-text-muted uppercase tracking-widest mb-1 text-center">Seuil Alerte</p>
                        <p class="text-sm font-black text-text-main text-center">{{ $product->min_stock }} unités</p>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Right Column: Movements & Adjustments -->
        <div class="lg:col-span-7 space-y-8" id="adjust-section">
            <x-ui.card :padding="false">
                <div class="flex border-b border-border-subtle">
                    <button 
                        @click="activeTab = 'movements'"
                        :class="activeTab === 'movements' ? 'border-brand-primary text-brand-primary' : 'border-transparent text-text-muted hover:text-text-main'"
                        class="flex-1 py-4 text-xs font-bold uppercase tracking-widest border-b-2 transition-all"
                    >
                        <i class="fas fa-exchange-alt mr-2"></i> Mouvements
                    </button>
                    <button 
                        @click="activeTab = 'adjust'"
                        :class="activeTab === 'adjust' ? 'border-brand-primary text-brand-primary' : 'border-transparent text-text-muted hover:text-text-main'"
                        class="flex-1 py-4 text-xs font-bold uppercase tracking-widest border-b-2 transition-all"
                    >
                        <i class="fas fa-tools mr-2"></i> Ajuster Stock
                    </button>
                </div>

                <div class="p-6">
                    <!-- Movements Tab -->
                    <div x-show="activeTab === 'movements'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[10px] font-bold text-text-muted uppercase tracking-widest border-b border-border-subtle">
                                        <th class="pb-3">Date</th>
                                        <th class="pb-3 text-center">Type</th>
                                        <th class="pb-3 text-center">Qté</th>
                                        <th class="pb-3">Réf.</th>
                                        <th class="pb-3">Motif</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border-subtle">
                                    @forelse($product->stockMovements()->orderBy('created_at', 'desc')->limit(12)->get() as $movement)
                                        <tr class="group hover:bg-slate-50/50 transition-colors">
                                            <td class="py-3">
                                                <div class="text-[10px] font-bold text-text-main leading-tight">{{ $movement->created_at->format('d/m/Y') }}</div>
                                                <div class="text-[9px] text-text-muted mt-0.5">{{ $movement->created_at->format('H:i') }}</div>
                                            </td>
                                            <td class="py-3 text-center">
                                                @if($movement->type === 'in')
                                                    <x-ui.badge variant="success" size="sm">Entrée</x-ui.badge>
                                                @else
                                                    <x-ui.badge variant="danger" size="sm">Sortie</x-ui.badge>
                                                @endif
                                            </td>
                                            <td class="py-3 text-center text-xs font-black {{ $movement->type === 'in' ? 'text-emerald-600' : 'text-rose-600' }}">
                                                {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                            </td>
                                            <td class="py-3">
                                                <span class="text-[10px] font-mono text-text-muted opacity-75 truncate block max-w-[80px]">
                                                    {{ $movement->invoice->invoice_number ?? 'Mouvement' }}
                                                </span>
                                            </td>
                                            <td class="py-3">
                                                <span class="text-[10px] text-text-muted italic line-clamp-1 max-w-[120px]">
                                                    {{ $movement->reason ?? '-' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-12 text-center text-xs text-text-muted italic">Aucun mouvement récent</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6 flex justify-center">
                            <x-ui.button href="{{ route('stock-movements.index') }}?product_id={{ $product->id }}" tag="a" variant="ghost" size="sm">
                                Voir tout l'historique <i class="fas fa-arrow-right ml-2 text-[10px]"></i>
                            </x-ui.button>
                        </div>
                    </div>

                    <!-- Adjustment Tab -->
                    <div x-show="activeTab === 'adjust'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0">
                        <form action="{{ route('products.adjust-stock', $product) }}" method="POST" id="adjust_form" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-forms.label for="adj_type" :required="true">Type d'opération</x-forms.label>
                                    <select name="type" id="adj_type" class="w-full rounded-lg border border-border-subtle bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-primary/10 focus:border-brand-primary focus:outline-none transition-all" required>
                                        <option value="in">🟢 Achat (Entrée de stock)</option>
                                        <option value="out">🔴 Vente (Sortie de stock)</option>
                                        <option value="adjustment">🔵 Ajustement manuel</option>
                                    </select>
                                </div>
                                <div>
                                    <x-forms.label for="adj_quantity" :required="true">Quantité</x-forms.label>
                                    <x-forms.input type="number" id="adj_quantity" name="quantity" value="1" required min="1" />
                                </div>
                                <div>
                                    <x-forms.label id="label_unit_price">Prix unitaire (MAD)</x-forms.label>
                                    <x-forms.input type="number" step="0.01" id="adj_purchase_price" name="unit_price" value="{{ $product->purchase_price ?? 0 }}" icon="fas fa-tag" />
                                </div>
                                <div id="field_customer">
                                    <x-forms.label>Client / Fournisseur</x-forms.label>
                                    <x-forms.input type="text" name="customer_supplier" placeholder="Nom du partenaire..." icon="fas fa-user" />
                                </div>
                                <div id="field_payment" class="md:col-span-2">
                                    <x-forms.label>Mode de paiement</x-forms.label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(['Espèces', 'Carte bancaire', 'Virement'] as $method)
                                            <label class="flex-1 min-w-[100px] cursor-pointer">
                                                <input type="radio" name="payment_method" value="{{ $method }}" class="hidden peer" {{ $loop->first ? 'checked' : '' }}>
                                                <div class="w-full py-2.5 text-center text-xs font-bold rounded-lg border border-border-subtle bg-white peer-checked:bg-brand-primary peer-checked:text-white peer-checked:border-brand-primary transition-all duration-200">
                                                    {{ $method }}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <x-forms.label>Motif / Commentaire</x-forms.label>
                                    <textarea name="reason" rows="2" class="w-full rounded-lg border border-border-subtle bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-primary/10 focus:border-brand-primary focus:outline-none transition-all placeholder-slate-400" placeholder="Ex: Stock initial, retour client, achat fournisseur..."></textarea>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-border-subtle">
                                <div class="bg-brand-primary/5 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative">
                                    <div class="flex-1">
                                        <p class="text-[10px] font-bold text-brand-primary uppercase tracking-widest mb-1">Montant Total Estimé</p>
                                        <h3 class="text-3xl font-black text-text-main" id="adj_total_display">0,00 MAD</h3>
                                    </div>
                                    <x-ui.button type="submit" id="submit_adj_btn" class="w-full md:w-auto px-10 py-4 shadow-premium shadow-brand-primary/20" icon="fas fa-check shadow-sm">
                                        <span id="btn_text">Valider l'opération</span>
                                        <span id="btn_loading" class="hidden"><i class="fas fa-spinner fa-spin mr-2"></i>Traitement...</span>
                                    </x-ui.button>
                                    
                                    <!-- Decorative circle -->
                                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-brand-primary/5 rounded-full blur-xl"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>
</div>

<script>
const PRODUCT_DATA = {
    purchasePrice: parseFloat('{{ $product->purchase_price ?? 0 }}') || 0,
    sellingPrice:  parseFloat('{{ $product->price ?? 0 }}') || 0,
    stock:         parseInt('{{ $product->quantity }}') || 0
};

function formatMAD(amount) {
    return new Intl.NumberFormat('fr-FR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(amount) + ' MAD';
}

function updateCalculations() {
    const typeEl    = document.getElementById('adj_type');
    const qtyEl     = document.getElementById('adj_quantity');
    const priceEl   = document.getElementById('adj_purchase_price');
    const display   = document.getElementById('adj_total_display');
    const submitBtn = document.getElementById('submit_adj_btn');
    const labelPrice = document.getElementById('label_unit_price');
    const fieldCustomer = document.getElementById('field_customer');
    const fieldPayment  = document.getElementById('field_payment');

    if (!typeEl || !qtyEl || !priceEl) return;

    const type     = typeEl.value;
    const quantity = Math.max(0, parseInt(qtyEl.value) || 0);

    // Update labels and visibility
    if (labelPrice) {
        labelPrice.textContent = type === 'out' ? 'Prix de vente (MAD)' : 'Prix d\'achat (MAD)';
    }
    
    const isAdjustment = type === 'adjustment';
    if (fieldCustomer) fieldCustomer.classList.toggle('hidden', isAdjustment);
    if (fieldPayment)  fieldPayment.classList.toggle('opacity-50', isAdjustment);
    if (fieldPayment)  fieldPayment.classList.toggle('pointer-events-none', isAdjustment);

    // Auto-fill price if not edited
    if (priceEl && !priceEl.dataset.userEdited) {
        const autoPrice = type === 'out' ? PRODUCT_DATA.sellingPrice : PRODUCT_DATA.purchasePrice;
        priceEl.value = autoPrice.toFixed(2);
    }

    const finalPrice = parseFloat(priceEl.value) || 0;
    const total      = finalPrice * quantity;

    if (display) display.textContent = formatMAD(total);
}

document.getElementById('adj_purchase_price')?.addEventListener('input', function() {
    this.dataset.userEdited = 'true';
    updateCalculations();
});

document.getElementById('adj_type')?.addEventListener('change', function() {
    const priceEl = document.getElementById('adj_purchase_price');
    if (priceEl) delete priceEl.dataset.userEdited;
    updateCalculations();
});

document.getElementById('adj_quantity')?.addEventListener('input', updateCalculations);

document.getElementById('adjust_form')?.addEventListener('submit', function() {
    const btn = document.getElementById('submit_adj_btn');
    const btnText = document.getElementById('btn_text');
    const btnLoad = document.getElementById('btn_loading');
    if (btn && btnText && btnLoad) {
        btn.disabled = true;
        btnText.classList.add('hidden');
        btnLoad.classList.remove('hidden');
    }
});

function recalculateStock(productId) {
    fetch('/products/' + productId + '/recalculate-stock', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    }).then(() => location.reload());
}

document.addEventListener('DOMContentLoaded', updateCalculations);
</script>
@endsection