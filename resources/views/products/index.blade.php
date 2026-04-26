@extends('layouts.app')

@section('title', 'Catalogue Produits')

@section('content')
<div class="space-y-8 animate-in">

  <!-- Header Section -->
  <div class="page-header">
      <div>
          <h1 class="page-title">Catalogue Produits</h1>
          <p class="text-text-muted mt-1 font-medium italic opacity-80">Gérez votre inventaire et vos ventes en temps réel.</p>
      </div>
      <x-ui.button href="{{ route('products.create') }}" tag="a" size="sm" icon="fas fa-plus shadow-sm">
          Nouveau Produit
      </x-ui.button>
  </div>

  {{-- New Filter Section (inline) --}}
  <form action="{{ route('products.index') }}" method="GET" class="filters-bar">
    <div class="filter-search">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"></circle>
        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
      </svg>
      <input type="text" name="search" placeholder="Recherche par nom, SKU..." value="{{ request('search') }}">
    </div>

    <select name="category" class="filter-select">
      <option value="">Toutes les catégories</option>
      @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
          {{ $cat->name }}
        </option>
      @endforeach
    </select>

    <button type="submit" class="btn-filter">Filtrer</button>

    <a href="{{ route('products.index') }}" class="btn-reset" title="Réinitialiser">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
        <path d="M3 3v5h5"></path>
      </svg>
    </a>
  </form>

  {{-- Products Grid --}}
  <div class="products-grid">
    @forelse($products as $product)
      <div class="product-card">

        {{-- Image zone compact --}}
        <div class="product-card-img">
          @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
          @else
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" class="text-slate-300" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
              <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
            </svg>
          @endif

          {{-- Stock badge top-right --}}
          <span class="stock-badge {{ $product->quantity > 0 ? 'badge-success' : 'badge-danger' }}">
            Stock: {{ $product->quantity }}
          </span>
        </div>

        {{-- Body --}}
        <div class="product-card-body">
          <div class="product-card-top">
            <span class="product-cat">{{ $product->category->name ?? '—' }}</span>
            <span class="product-sku">#{{ $product->sku }}</span>
          </div>

          <h3 class="product-name" title="{{ $product->name }}">{{ $product->name }}</h3>
          <p class="product-desc" title="{{ $product->description ?? 'Aucune description' }}">
            {{ $product->description ?? '' }}
          </p>

          <div class="product-card-footer">
            <div class="product-price">
              {{ number_format($product->price, 2) }}
              <span>MAD</span>
            </div>
            
            <div class="product-actions">
              <a href="{{ route('products.show', $product) }}" class="act-btn act-view" title="Voir">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
              </a>
              <a href="{{ route('products.edit', $product) }}" class="act-btn act-edit" title="Modifier">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
              </a>
              <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="act-btn act-delete" title="Supprimer">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                  </svg>
                </button>
              </form>
            </div>
          </div>
        </div>

      </div>
    @empty
      <div class="products-empty">
        Aucun produit trouvé.
      </div>
    @endforelse
  </div>

  <!-- Pagination -->
  @if($products->hasPages())
    <div class="pt-8">
        {{ $products->links() }}
    </div>
  @endif

</div>
@endsection