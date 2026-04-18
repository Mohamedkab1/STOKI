@extends('layouts.app')

@section('title', 'Catégories')

@section('content')
<div class="space-y-8 animate-in">

  {{-- Page header --}}
  <div class="page-header">
    <div>
      <h1 class="page-title">Catégories</h1>
      <p class="text-text-muted mt-1 font-medium italic opacity-80">Gérez vos types de produits et organisez votre inventaire.</p>
    </div>
    <div>
      <x-ui.button href="{{ route('categories.create') }}" tag="a" size="sm" icon="fas fa-plus shadow-sm">
        Nouvelle catégorie
      </x-ui.button>
    </div>
  </div>

  {{-- Categories list --}}
  <div class="categories-list">

    {{-- List header --}}
    <div class="list-header">
      <span>Catégorie</span>
      <span>Description</span>
      <span>Produits</span>
      <span>Actions</span>
    </div>

    {{-- Empty state --}}
    @if($categories->isEmpty())
    <div class="list-empty">
      <i class="fas fa-folder-open text-4xl opacity-30"></i>
      <p>Aucune catégorie trouvée.</p>
      <x-ui.button href="{{ route('categories.create') }}" tag="a" size="sm">
        Créer une catégorie
      </x-ui.button>
    </div>
    @endif

    {{-- List items --}}
    @foreach($categories as $category)
    <div class="list-item">

      {{-- Nom + icône --}}
      <div class="cat-name">
        <div class="cat-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
          </svg>
        </div>
        <span class="cat-label">{{ $category->name }}</span>
      </div>

      {{-- Description --}}
      <div class="cat-desc">
        {{ $category->description ?? '—' }}
      </div>

      {{-- Nombre de produits --}}
      <div>
        <span class="cat-badge">
          {{ $category->products_count ?? $category->products->count() }}
          {{ ($category->products_count ?? $category->products->count()) > 1 ? 'produits' : 'produit' }}
        </span>
      </div>

      {{-- Actions --}}
      <div class="cat-actions">
        <a href="{{ route('categories.edit', $category) }}" class="act-btn edit" title="Modifier">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
          </svg>
        </a>
        
        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block m-0 p-0" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
          @csrf @method('DELETE')
          <button type="submit" class="act-btn delete" title="Supprimer">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="3 6 5 6 21 6"></polyline>
              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            </svg>
          </button>
        </form>
      </div>

    </div>
    @endforeach

  </div>
  
  <!-- Pagination -->
  @if($categories->hasPages())
  <div class="pt-8">
      {{ $categories->links() }}
  </div>
  @endif

</div>
@endsection