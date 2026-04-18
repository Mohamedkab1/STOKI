@extends('layouts.app')

@section('title', 'Nouveau Produit')

@section('content')
<div class="space-y-8 animate-in">
    <!-- Header Section -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Ajouter un produit</h1>
            <p class="text-text-muted mt-1">Complétez les informations pour référencer un nouvel article.</p>
        </div>
        <x-ui.button href="{{ route('products.index') }}" tag="a" variant="outline" icon="fas fa-arrow-left">
            Retour
        </x-ui.button>
    </div>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Side: Basic Info -->
            <div class="lg:col-span-7 space-y-6">
                <x-ui.card title="Informations générales" subtitle="Détails de base du produit">
                    <div class="space-y-6">
                        <div>
                            <x-forms.label for="name" :required="true">Nom du produit</x-forms.label>
                            <x-forms.input name="name" id="name" placeholder="Ex: iPhone 15 Pro Max" value="{{ old('name') }}" required />
                            @error('name') <p class="text-[10px] font-bold text-rose-500 mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="form-grid">
                            <div>
                                <x-forms.label for="sku" :required="true">Code SKU / Référence</x-forms.label>
                                <x-forms.input name="sku" id="sku" placeholder="Ex: IP15-PRO-256" value="{{ old('sku') }}" required />
                                @error('sku') <p class="text-[10px] font-bold text-rose-500 mt-1 uppercase">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <x-forms.label for="category_id" :required="true">Catégorie</x-forms.label>
                                <select name="category_id" id="category_id" class="w-full rounded-lg border border-border-subtle bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-primary/10 focus:border-brand-primary focus:outline-none transition-all" required>
                                    <option value="">Choisir...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <p class="text-[10px] font-bold text-rose-500 mt-1 uppercase">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <x-forms.label for="description">Description détaillée</x-forms.label>
                            <textarea name="description" id="description" rows="5" class="w-full rounded-lg border border-border-subtle bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-primary/10 focus:border-brand-primary focus:outline-none transition-all placeholder-slate-400" placeholder="Décrivez les caractéristiques du produit...">{{ old('description') }}</textarea>
                            @error('description') <p class="text-[10px] font-bold text-rose-500 mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <!-- Right Side: Inventory & Pricing -->
            <div class="lg:col-span-5 space-y-6">
                <x-ui.card title="Inventaire & Prix" subtitle="Gestion des stocks et tarification">
                    <div class="space-y-6">
                        <div>
                            <x-forms.label for="purchase_price">Prix d'achat (MAD)</x-forms.label>
                            <x-forms.input type="number" step="0.01" name="purchase_price" id="purchase_price" value="{{ old('purchase_price') }}" icon="fas fa-tag" />
                            @error('purchase_price') <p class="text-[10px] font-bold text-rose-500 mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-forms.label for="selling_price" :required="true">Prix de vente (MAD)</x-forms.label>
                            <x-forms.input type="number" step="0.01" name="price" id="selling_price" value="{{ old('price') }}" icon="fas fa-coins" required />
                            @error('selling_price') <p class="text-[10px] font-bold text-rose-500 mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>
                        <input type="hidden" name="selling_price" id="hidden_selling_price" value="{{ old('selling_price') }}">

                        <div class="form-grid">
                            <div>
                                <x-forms.label for="quantity">Stock initial</x-forms.label>
                                <x-forms.input type="number" name="quantity" id="quantity" value="{{ old('quantity', 0) }}" />
                                @error('quantity') <p class="text-[10px] font-bold text-rose-500 mt-1 uppercase">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <x-forms.label for="min_stock">Seuil d'alerte</x-forms.label>
                                <x-forms.input type="number" name="min_stock" id="min_stock" value="{{ old('min_stock', 5) }}" />
                                @error('min_stock') <p class="text-[10px] font-bold text-rose-500 mt-1 uppercase">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <x-forms.label>Image du produit</x-forms.label>
                            <div class="group relative mt-2">
                                <input type="file" name="image" id="product_image" class="hidden" accept="image/*" onchange="previewImage(this)">
                                <label for="product_image" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-border-subtle rounded-2xl bg-slate-50/50 cursor-pointer group-hover:bg-slate-50 group-hover:border-brand-primary transition-all duration-300">
                                    <div id="preview-placeholder" class="text-center">
                                        <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center mx-auto mb-3 text-text-muted group-hover:text-brand-primary group-hover:scale-110 transition-all duration-300">
                                            <i class="fas fa-cloud-upload-alt text-xl"></i>
                                        </div>
                                        <p class="text-xs font-bold text-text-muted group-hover:text-brand-primary">Cliquez pour télécharger</p>
                                        <p class="text-[10px] text-text-muted mt-1 opacity-60">PNG, JPG up to 5MB</p>
                                    </div>
                                    <img id="image-preview" src="#" alt="Aperçu" class="hidden absolute inset-0 w-full h-full object-cover rounded-2xl p-1 bg-white">
                                </label>
                            </div>
                            @error('image') <p class="text-[10px] font-bold text-rose-500 mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </x-ui.card>

                <div class="flex items-center gap-3 pt-4">
                    <x-ui.button type="reset" variant="outline" class="flex-1">
                        Réinitialiser
                    </x-ui.button>
                    <x-ui.button type="submit" class="flex-1 px-8 shadow-premium shadow-brand-primary/20" icon="fas fa-save">
                        Enregistrer
                    </x-ui.button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    const placeholder = document.getElementById('preview-placeholder');
    const preview = document.getElementById('image-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Auto-calcul du prix de vente (Simple Logic)
document.getElementById('purchase_price').addEventListener('input', function() {
    const purchase = parseFloat(this.value);
    if (!isNaN(purchase)) {
        const selling = (purchase * 1.3).toFixed(2);
        document.getElementById('selling_price').value = selling;
        document.getElementById('hidden_selling_price').value = selling;
    }
});

document.getElementById('selling_price').addEventListener('input', function() {
    document.getElementById('hidden_selling_price').value = this.value;
});
</script>
@endsection