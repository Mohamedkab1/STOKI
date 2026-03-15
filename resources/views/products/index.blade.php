<!-- resources/views/products/index.blade.php -->
@extends('layouts.app')

@section('title', 'Produits')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-down">
    <div>
        <h1 class="text-gradient mb-2">
            <i class="fas fa-box me-2"></i>
            Catalogue Produits
        </h1>
        <p class="text-white-50">Gérez votre inventaire de produits</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn-premium">
        <i class="fas fa-plus me-2"></i> Nouveau Produit
    </a>
</div>

<!-- Barre de recherche -->
<div class="premium-card mb-4" data-aos="fade-up">
    <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control bg-dark text-white border-0" 
                       placeholder="Rechercher un produit..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-control bg-dark text-white border-0">
                    <option value="">Toutes catégories</option>
                    @foreach(App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn-premium w-100">
                    <i class="fas fa-search me-2"></i> Filtrer
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('products.index') }}" class="btn-premium w-100">
                    <i class="fas fa-redo me-2"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Grille des produits -->
<div class="row g-4">
    @forelse($products as $product)
        <div class="col-xl-3 col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 50 }}">
            <div class="product-card-premium">
                <div class="product-image-premium">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                    @else
                        <div class="text-center">
                            <i class="fas fa-box fa-4x text-gradient"></i>
                            <p class="text-white-50 small mt-2">Aucune image</p>
                        </div>
                    @endif
                    
                    @if($product->isLowStock())
                        <span class="product-badge-premium">
                            <i class="fas fa-exclamation-triangle me-1"></i>Stock faible
                        </span>
                    @elseif($product->quantity == 0)
                        <span class="product-badge-premium bg-danger">Rupture</span>
                    @else
                        <span class="product-badge-premium" style="background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);">En stock</span>
                    @endif
                </div>
                
                <div class="product-info-premium">
                    <h6 class="product-title-premium">{{ $product->name }}</h6>
                    <p class="product-sku-premium">
                        <i class="fas fa-barcode me-1"></i> {{ $product->sku }}
                    </p>
                    
                    <div class="product-price-premium">
                        {{ number_format($product->price, 2) }} €
                    </div>
                    
                    <div class="product-stock mb-3">
                        <span class="text-white-50">
                            <i class="fas fa-cubes me-1"></i>Stock
                        </span>
                        <span class="fw-bold {{ $product->isLowStock() ? 'text-danger' : 'text-gradient' }}">
                            {{ $product->quantity }} unités
                        </span>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('products.show', $product) }}" 
                           class="btn-premium flex-grow-1"
                           data-bs-toggle="tooltip" title="Voir détails">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('products.edit', $product) }}" 
                           class="btn-premium"
                           data-bs-toggle="tooltip" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-premium"
                                    onclick="return confirm('Supprimer ce produit ?')"
                                    data-bs-toggle="tooltip" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="premium-card text-center p-5" data-aos="fade-up">
                <i class="fas fa-box-open fa-4x text-gradient mb-3"></i>
                <h4 class="text-white">Aucun produit trouvé</h4>
                <p class="text-white-50 mb-4">Commencez par ajouter votre premier produit</p>
                <a href="{{ route('products.create') }}" class="btn-premium">
                    <i class="fas fa-plus me-2"></i> Ajouter un produit
                </a>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-5">
    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>

<style>
.product-image-premium {
    height: 200px;
    background: linear-gradient(135deg, #5C2018 0%, #BC4639 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.product-image-premium img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.product-image-premium:hover img {
    transform: scale(1.1);
}

.product-badge-premium {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px 15px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    z-index: 1;
    background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);
    color: #F3E0DC;
    box-shadow: 0 5px 15px rgba(66, 133, 244, 0.3);
}
</style>
@endsection