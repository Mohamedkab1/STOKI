<!-- resources/views/products/show.blade.php -->
@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-down">
    <div>
        <h1 class="text-gradient mb-2">{{ $product->name }}</h1>
        <p class="text-white-50">
            <i class="fas fa-barcode me-2"></i>SKU: {{ $product->sku }}
        </p>
    </div>
    <div>
        <a href="{{ route('products.fifo-history', $product) }}" class="btn-premium me-2">
            <i class="fas fa-chart-line me-2"></i> Historique FIFO
        </a>
        <a href="{{ route('products.edit', $product) }}" class="btn-premium me-2">
            <i class="fas fa-edit me-2"></i> Modifier
        </a>
        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-premium" onclick="return confirm('Êtes-vous sûr ?')">
                <i class="fas fa-trash me-2"></i> Supprimer
            </button>
        </form>
        <a href="{{ route('products.index') }}" class="btn-premium ms-2">
            <i class="fas fa-arrow-left me-2"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <!-- Colonne gauche - Informations et formulaire -->
    <div class="col-md-5">
        <!-- Informations produit -->
        <div class="premium-card mb-4" data-aos="fade-right">
            <div class="card-header-premium">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informations produit
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         style="max-height: 200px; border-radius: 10px; border: 3px solid #4285f4;">
                @else
                    <div class="p-4">
                        <i class="fas fa-box fa-5x text-gradient"></i>
                        <p class="text-white-50 mt-2">Aucune image disponible</p>
                    </div>
                @endif
            </div>
                
                <table class="table table-borderless text-white">
                    <tr>
                        <th class="text-white-50" width="40%">Catégorie :</th>
                        <td><span class="badge" style="background: linear-gradient(135deg, #00f3ff 0%, #00c8ff 100%);">{{ $product->category->name }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-white-50">Description :</th>
                        <td>{{ $product->description ?? 'Aucune description' }}</td>
                    </tr>
                    <tr>
                        <th class="text-white-50">Prix unitaire :</th>
                        <td><h4 class="text-gradient mb-0">{{ number_format($product->price, 2) }} €</h4></td>
                    </tr>
                    <tr>
                        <th class="text-white-50">Stock actuel :</th>
                        <td>
                            <span class="badge {{ $product->isLowStock() ? 'bg-danger' : '' }}" 
                                  style="{{ !$product->isLowStock() ? 'background: linear-gradient(135deg, #00f3ff 0%, #00c8ff 100%);' : '' }}">
                                <i class="fas {{ $product->isLowStock() ? 'fa-exclamation-triangle' : 'fa-check-circle' }} me-1"></i>
                                {{ $product->quantity }} unités
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-white-50">Stock minimum :</th>
                        <td>{{ $product->min_stock }} unités</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Formulaire d'opération -->
        <div class="premium-card" data-aos="fade-right" data-aos-delay="100">
            <div class="card-header-premium">
                <h5 class="mb-0">
                    <i class="fas fa-exchange-alt me-2"></i>
                    Nouvelle opération
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('products.adjust-stock', $product) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="text-white-50 mb-2">Type d'opération</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="type" id="type_in" value="in" autocomplete="off" checked>
                            <label class="btn btn-outline-success" for="type_in">
                                <i class="fas fa-arrow-down me-1"></i> Entrée (Achat)
                            </label>
                            
                            <input type="radio" class="btn-check" name="type" id="type_out" value="out" autocomplete="off">
                            <label class="btn btn-outline-danger" for="type_out">
                                <i class="fas fa-arrow-up me-1"></i> Sortie (Vente)
                            </label>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="text-white-50 mb-2">Quantité</label>
                            <input type="number" name="quantity" id="quantity" 
                                   class="form-control bg-dark text-white border-0" 
                                   value="{{ old('quantity') }}" min="1" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-white-50 mb-2">Prix unitaire</label>
                            <input type="number" step="0.01" name="unit_price" id="unit_price" 
                                   class="form-control bg-dark text-white border-0" 
                                   value="{{ $product->price }}" readonly>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="text-white-50 mb-2">Client/Fournisseur</label>
                            <input type="text" name="customer_supplier" 
                                   class="form-control bg-dark text-white border-0" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-white-50 mb-2">Mode de paiement</label>
                            <select name="payment_method" class="form-control bg-dark text-white border-0" required>
                                <option value="">Sélectionner</option>
                                <option value="Espèces">💵 Espèces</option>
                                <option value="Carte bancaire">💳 Carte bancaire</option>
                                <option value="Virement">🏦 Virement</option>
                                <option value="Chèque">📝 Chèque</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="text-white-50 mb-2">Motif</label>
                        <textarea name="reason" class="form-control bg-dark text-white border-0" 
                                  rows="2" placeholder="Raison de l'opération..."></textarea>
                    </div>
                    
                    <div class="alert" style="background: rgba(0, 243, 255, 0.1); border: 1px solid rgba(0, 243, 255, 0.3);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="text-gradient">Montant total :</strong>
                                <span id="previewTotal" class="h5 ms-2 text-white">0.00 €</span>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-premium btn-premium-primary w-100 py-3">
                        <i class="fas fa-file-invoice me-2"></i>
                        Valider et générer la facture
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Colonne droite - Historique -->
    <div class="col-md-7">
        <div class="premium-card" data-aos="fade-left">
            <div class="card-header-premium">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Historique des mouvements
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Qté</th>
                                <th>Client/Fournisseur</th>
                                <th>Motif</th>
                                <th>Date</th>
                                <th>Facture</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movements as $movement)
                            <tr>
                                <td>
                                    @if($movement->type == 'in')
                                        <span class="badge" style="background: linear-gradient(135deg, #00f3ff 0%, #00c8ff 100%);">Entrée</span>
                                    @else
                                        <span class="badge bg-danger">Sortie</span>
                                    @endif
                                </td>
                                <td>{{ $movement->quantity }}</td>
                                <td>{{ $movement->invoice->customer_supplier ?? '-' }}</td>
                                <td>{{ $movement->reason ?? '-' }}</td>
                                <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($movement->invoice)
                                        <a href="{{ route('invoices.show', $movement->invoice) }}" 
                                           class="btn-premium btn-sm">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $movements->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const unitPrice = {{ $product->price }};
    const previewTotal = document.getElementById('previewTotal');
    
    function updatePreview() {
        const quantity = parseInt(quantityInput.value) || 0;
        const total = quantity * unitPrice;
        previewTotal.textContent = total.toFixed(2) + ' €';
    }
    
    quantityInput.addEventListener('input', updatePreview);
});
</script>
@endpush
@endsection