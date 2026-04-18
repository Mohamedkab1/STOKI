@extends('layouts.app')

@section('title', 'Factures')

@section('content')
<div class="space-y-8 animate-in">

  <!-- Header -->
  <div class="page-header">
      <div>
          <h1 class="page-title">Factures</h1>
          <p class="text-text-muted mt-1 font-medium italic opacity-80">Historique complet de vos transactions.</p>
      </div>
      <x-ui.button href="{{ route('products.index') }}" tag="a" size="sm" icon="fas fa-plus shadow-sm">
          Nouvelle facture
      </x-ui.button>
  </div>

  <!-- Stats mini row -->
  <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr);">
      <div class="stat-card">
          <div class="stat-icon stat-icon-blue">
              <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
          </div>
          <div>
              <div class="stat-label">Total factures</div>
              <div class="stat-value">{{ $invoices->total() }}</div>
          </div>
      </div>
      <div class="stat-card">
          <div class="stat-icon stat-icon-green">
              <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
          </div>
          <div>
              <div class="stat-label">Montant total (Payé)</div>
              <div class="stat-value">{{ number_format(\App\Models\Invoice::where('payment_status', 'paid')->sum('total_amount'), 2) }} <span class="text-xs text-text-secondary">MAD</span></div>
          </div>
      </div>
      <div class="stat-card">
          <div class="stat-icon stat-icon-orange">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
          </div>
          <div>
              <div class="stat-label">En attente</div>
              <div class="stat-value">{{ \App\Models\Invoice::where('payment_status', 'pending')->count() }}</div>
          </div>
      </div>
  </div>

  <!-- Filters -->
  <form action="{{ route('invoices.index') }}" method="GET" class="filters-bar">
      <div class="filter-search" style="min-width: 250px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          <input type="text" name="search" placeholder="N° facture ou client..." value="{{ request('search') }}">
      </div>
      
      <select name="payment_status" class="filter-select">
          <option value="">Tous les statuts</option>
          <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Payée</option>
          <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>En attente</option>
      </select>
      
      <div style="display:flex; align-items:center; gap:6px;">
          <input type="date" name="date_from" value="{{ request('date_from') }}" class="filter-select" style="min-width: 130px;">
          <span class="text-text-secondary">-</span>
          <input type="date" name="date_to" value="{{ request('date_to') }}" class="filter-select" style="min-width: 130px;">
      </div>

      <button type="submit" class="btn-filter">Filtrer</button>
      <a href="{{ route('invoices.index') }}" class="btn-reset" title="Réinitialiser">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path></svg>
      </a>
  </form>

  <!-- Table -->
  <div class="table-responsive">
      <table class="modern-table">
          <thead>
              <tr>
                  <th>Numéro</th>
                  <th>Nature</th>
                  <th>Client / Fournisseur</th>
                  <th>Montant</th>
                  <th>Statut</th>
                  <th>Date</th>
                  <th width="100">Actions</th>
              </tr>
          </thead>
          <tbody>
              @forelse($invoices as $inv)
                  <tr>
                      <td class="font-medium text-text-primary">#{{ $inv->invoice_number }}</td>
                      <td>
                          @if($inv->type === 'sale')
                            Sortie (Vente)
                          @else
                            Entrée (Achat)
                          @endif
                      </td>
                      <td>{{ $inv->customer_supplier ?: '—' }}</td>
                      <td class="font-medium">{{ number_format($inv->total_amount, 2) }} MAD</td>
                      <td>
                          @if($inv->payment_status === 'paid')
                              <span class="stock-badge badge-success relative top-0 right-0">Payée</span>
                          @else
                              <span class="stock-badge badge-warning relative top-0 right-0">En attente</span>
                          @endif
                      </td>
                      <td class="text-text-secondary">{{ $inv->invoice_date->format('d/m/Y') }}</td>
                      <td>
                          <div class="flex gap-1.5">
                              <a href="{{ route('invoices.show', $inv) }}" class="act-btn act-view" title="Voir">
                                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                              </a>
                              <a href="{{ route('invoices.pdf', $inv) }}" class="act-btn" title="PDF">
                                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="text-text-secondary" stroke="currentColor" stroke-width="2"><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10.4 12.6a2 2 0 1 1 3 3L8 21l-4 1 1-4Z"></path><path d="M18 18h.01"></path><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h4"></path></svg>
                              </a>
                          </div>
                      </td>
                  </tr>
              @empty
                  <tr>
                      <td colspan="7" class="text-center text-text-secondary py-10">
                          Aucune facture trouvée.
                      </td>
                  </tr>
              @endforelse
          </tbody>
      </table>
  </div>

  @if($invoices->hasPages())
      <div class="pt-6">
          {{ $invoices->links() }}
      </div>
  @endif

</div>
@endsection