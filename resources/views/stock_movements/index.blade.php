@extends('layouts.app')

@section('title', 'Mouvements de stock')

@section('content')
<div class="space-y-8 animate-in" x-data="{}">

  <!-- Header -->
  <div class="page-header">
      <div>
          <h1 class="page-title">Mouvements de stock</h1>
          <p class="text-text-muted mt-1 font-medium italic opacity-80">Traçabilité complète de vos entrées, sorties et ajustements.</p>
      </div>
      <x-ui.button @click="$dispatch('open-modal', 'add-movement')" size="sm" icon="fas fa-plus shadow-sm">
          Nouveau mouvement
      </x-ui.button>
  </div>

  <!-- Stats mini row -->
  <div class="stats-grid" style="grid-template-columns: repeat(4, 1fr);">
      <div class="stat-card">
          <div class="stat-icon stat-icon-green">
              <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
          </div>
          <div>
              <div class="stat-label">Entrées</div>
              <div class="stat-value">{{ \App\Models\StockMovement::where('type','in')->whereMonth('created_at', now()->month)->count() }}</div>
          </div>
      </div>
      <div class="stat-card">
          <div class="stat-icon stat-icon-orange">
              <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          </div>
          <div>
              <div class="stat-label">Sorties</div>
              <div class="stat-value">{{ \App\Models\StockMovement::where('type','out')->whereMonth('created_at', now()->month)->count() }}</div>
          </div>
      </div>
      <div class="stat-card">
          <div class="stat-icon stat-icon-blue">
              <svg viewBox="0 0 24 24"><polyline points="16 16 12 12 8 16"></polyline><line x1="12" y1="12" x2="12" y2="21"></line><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path><polyline points="16 16 12 12 8 16"></polyline></svg>
          </div>
          <div>
              <div class="stat-label">Stock Total</div>
              <div class="stat-value">{{ \App\Models\Product::sum('quantity') }}</div>
          </div>
      </div>
      <div class="stat-card">
          <div class="stat-icon stat-icon-purple">
              <svg viewBox="0 0 24 24"><path d="M12 20V10"></path><path d="M18 20V4"></path><path d="M6 20v-4"></path></svg>
          </div>
          <div>
              <div class="stat-label">Ajustements</div>
              <div class="stat-value">{{ \App\Models\StockMovement::where('type','adjustment')->whereMonth('created_at', now()->month)->count() }}</div>
          </div>
      </div>
  </div>

  <!-- Filters -->
  <form action="{{ route('stock-movements.index') }}" method="GET" class="filters-bar">
      <div class="filter-search" style="min-width: 250px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          <input type="text" name="search" placeholder="Rechercher un produit..." value="{{ request('search') }}">
      </div>
      
      <select name="type" class="filter-select">
          <option value="">Tous les types</option>
          <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Entrée</option>
          <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Sortie</option>
          <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Ajustement</option>
      </select>
      
      <div style="display:flex; align-items:center; gap:6px;">
          <input type="date" name="date" value="{{ request('date') }}" class="filter-select" style="min-width: 130px;">
      </div>

      <button type="submit" class="btn-filter">Filtrer</button>
      <a href="{{ route('stock-movements.index') }}" class="btn-reset" title="Réinitialiser">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path></svg>
      </a>
  </form>

  <!-- Table -->
  <div class="table-responsive">
      <table class="modern-table">
          <thead>
              <tr>
                  <th>Produit</th>
                  <th>Type</th>
                  <th class="text-center">Quantité</th>
                  <th>Note / Motif</th>
                  <th>Date</th>
                  <th width="80">Actions</th>
              </tr>
          </thead>
          <tbody>
              @forelse($movements as $mvt)
                  <tr>
                      <td class="font-medium text-text-primary">
                          {{ $mvt->product->name }}
                          <div class="text-[11px] font-normal text-text-secondary mt-0.5">#{{ $mvt->product->sku }}</div>
                      </td>
                      <td>
                          @if($mvt->type === 'in')
                              <span class="stock-badge badge-success relative top-0 right-0">Entrée</span>
                          @elseif($mvt->type === 'out')
                              <span class="stock-badge badge-danger relative top-0 right-0">Sortie</span>
                          @else
                              <span class="stock-badge badge-warning relative top-0 right-0">Ajustement</span>
                          @endif
                      </td>
                      <td class="text-center font-medium {{ $mvt->type === 'in' ? 'text-green-500' : ($mvt->type === 'out' ? 'text-red-500' : '') }}">
                          {{ $mvt->type === 'in' ? '+' : ($mvt->type === 'out' ? '-' : '') }}{{ $mvt->quantity }}
                      </td>
                      <td class="text-text-secondary text-[13px] limit-length max-w-[200px] truncate" title="{{ $mvt->note ?? $mvt->reason }}">
                          {{ $mvt->note ?? $mvt->reason ?? '—' }}
                      </td>
                      <td class="text-text-secondary text-[12px]">
                          {{ $mvt->created_at->format('d/m/Y H:i') }}
                      </td>
                      <td>
                          <div class="flex justify-end gap-1.5">
                              @if($mvt->invoice_id)
                                  <a href="{{ route('invoices.show', $mvt->invoice_id) }}" class="act-btn act-view" title="Voir facture">
                                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                  </a>
                              @endif
                          </div>
                      </td>
                  </tr>
              @empty
                  <tr>
                      <td colspan="6" class="text-center text-text-secondary py-10">
                          Aucun mouvement enregistré.
                      </td>
                  </tr>
              @endforelse
          </tbody>
      </table>
  </div>

  @if($movements->hasPages())
      <div class="pt-6">
          {{ $movements->links() }}
      </div>
  @endif

  <!-- Slide-over / Modal for New Movement exists untouched from original -->
  {{-- Using the original component code you already had internally --}}
  <x-ui.modal name="add-movement" maxWidth="md">
        <div class="p-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-brand-primary/10 text-brand-primary flex items-center justify-center text-xl shadow-sm border border-brand-primary/20">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div>
                    <h2 class="text-xl font-medium text-text-primary capitalize">Nouveau mouvement</h2>
                    <p class="text-[13px] text-text-secondary mt-1">Enregistrer une opération manuelle.</p>
                </div>
            </div>

            <form action="{{ route('stock-movements.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-[13px] font-medium text-text-primary mb-1">Produit</label>
                    <select name="product_id" class="w-full h-10 rounded-lg border border-border-color bg-bg-surface px-3 text-[13px] text-text-primary focus:outline-none focus:border-brand-primary" required>
                        @foreach(\App\Models\Product::orderBy('name')->get() as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->quantity }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-grid" style="grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label class="block text-[13px] font-medium text-text-primary mb-1">Type</label>
                        <select name="type" class="w-full h-10 rounded-lg border border-border-color bg-bg-surface px-3 text-[13px] text-text-primary focus:outline-none focus:border-brand-primary" required>
                            <option value="in">Entrée</option>
                            <option value="out">Sortie</option>
                            <option value="adjustment">Ajustement</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-text-primary mb-1">Quantité</label>
                        <input type="number" name="quantity" value="1" min="1" class="w-full h-10 rounded-lg border border-border-color bg-bg-surface px-3 text-[13px] text-text-primary focus:outline-none focus:border-brand-primary" required>
                    </div>
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-text-primary mb-1">Motif</label>
                    <textarea name="note" rows="2" class="w-full rounded-lg border border-border-color bg-bg-surface px-3 py-2 text-[13px] text-text-primary focus:outline-none focus:border-brand-primary"></textarea>
                </div>

                <div class="flex pt-4 gap-3">
                    <button type="button" @click="show = false" class="flex-1 h-10 rounded-lg border border-border-color text-[13px] font-medium text-text-secondary hover:bg-bg-surface">Annuler</button>
                    <button type="submit" class="flex-1 h-10 rounded-lg bg-brand-primary text-white text-[13px] font-medium">Enregistrer</button>
                </div>
            </form>
        </div>
  </x-ui.modal>

</div>
@endsection
