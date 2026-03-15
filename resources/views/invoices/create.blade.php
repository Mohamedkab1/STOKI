<!-- resources/views/invoices/create.blade.php -->
@extends('layouts.app')

@section('title', 'Nouvelle Facture')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-down">
    <div>
        <h1 class="text-gradient mb-2">
            <i class="fas fa-plus-circle me-2"></i>
            Nouvelle Facture
        </h1>
        <p class="text-white-50">Créez une nouvelle facture</p>
    </div>
    <a href="{{ route('invoices.index') }}" class="btn-premium">
        <i class="fas fa-arrow-left me-2"></i> Retour
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="premium-card" data-aos="fade-up">
            <div class="card-header-premium">
                <h5 class="mb-0">
                    <i class="fas fa-file-invoice me-2"></i>
                    Informations de la facture
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('invoices.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-white-50 mb-2">Type de facture</label>
                            <select name="type" id="type" class="form-control bg-dark text-white border-0" required>
                                <option value="">Sélectionner</option>
                                <option value="purchase">Achat (Entrée stock)</option>
                                <option value="sale">Vente (Sortie stock)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-white-50 mb-2">Type de mouvement</label>
                            <select name="movement_type" id="movement_type" class="form-control bg-dark text-white border-0" required>
                                <option value="">Sélectionner</option>
                                <option value="in">Entrée</option>
                                <option value="out">Sortie</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-white-50 mb-2">Produit</label>
                            <select name="product_id" id="product_id" class="form-control bg-dark text-white border-0" required>
                                <option value="">Sélectionner un produit</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-price="{{ $product->price }}"
                                            data-stock="{{ $product->quantity }}">
                                        {{ $product->name }} (Stock: {{ $product->quantity }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="text-white-50 mb-2">Quantité</label>
                            <input type="number" name="quantity" id="quantity" 
                                   class="form-control bg-dark text-white border-0" min="1" required>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="text-white-50 mb-2">Prix unitaire (€)</label>
                            <input type="number" step="0.01" name="unit_price" id="unit_price" 
                                   class="form-control bg-dark text-white border-0" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-white-50 mb-2">Client / Fournisseur</label>
                            <input type="text" name="customer_supplier" 
                                   class="form-control bg-dark text-white border-0" 
                                   placeholder="Nom du client ou fournisseur" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-white-50 mb-2">Mode de paiement</label>
                            <select name="payment_method" class="form-control bg-dark text-white border-0">
                                <option value="">Sélectionner</option>
                                <option value="Espèces">💵 Espèces</option>
                                <option value="Carte bancaire">💳 Carte bancaire</option>
                                <option value="Virement">🏦 Virement</option>
                                <option value="Chèque">📝 Chèque</option>
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label class="text-white-50 mb-2">Motif / Description</label>
                            <textarea name="reason" class="form-control bg-dark text-white border-0" 
                                      rows="3"></textarea>
                        </div>
                        
                        <div class="col-12">
                            <div class="alert p-4 text-center" style="background: rgba(0, 243, 255, 0.1); border: 1px solid rgba(0, 243, 255, 0.3);">
                                <strong class="text-gradient d-block mb-2">Total à payer :</strong>
                                <span id="totalAmount" class="h1 text-white">0.00</span> <span class="h4 text-gradient">€</span>
                            </div>
                        </div>
                        
                        <div class="col-12 text-center">
                            <button type="submit" class="btn-premium btn-premium-primary px-5 py-3">
                                <i class="fas fa-save me-2"></i>
                                Créer la facture
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('unit_price');
    const totalSpan = document.getElementById('totalAmount');
    const typeSelect = document.getElementById('type');
    const movementTypeSelect = document.getElementById('movement_type');
    
    function updateTotal() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        totalSpan.textContent = (quantity * price).toFixed(2);
    }
    
    function updateProductInfo() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        if (selectedOption.value) {
            const price = selectedOption.dataset.price;
            priceInput.value = price;
            updateTotal();
        }
    }
    
    productSelect.addEventListener('change', updateProductInfo);
    quantityInput.addEventListener('input', updateTotal);
    priceInput.addEventListener('input', updateTotal);
    
    typeSelect.addEventListener('change', function() {
        if (this.value === 'purchase') {
            movementTypeSelect.value = 'in';
        } else if (this.value === 'sale') {
            movementTypeSelect.value = 'out';
        }
    });
});
</script>
@endpush
@endsection