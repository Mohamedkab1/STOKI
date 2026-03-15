<!-- resources/views/invoices/index.blade.php -->
@extends('layouts.app')

@section('title', 'Factures')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-down">
    <div>
        <h1 class="text-gradient mb-2">
            <i class="fas fa-file-invoice me-2"></i>
            Factures
        </h1>
        <p class="text-white-50">Gérez toutes vos factures</p>
    </div>
    <a href="{{ route('invoices.create') }}" class="btn-premium">
        <i class="fas fa-plus me-2"></i> Nouvelle Facture
    </a>
</div>

<!-- Filtres -->
<div class="premium-card mb-4" data-aos="fade-up">
    <div class="card-header-premium">
        <h5 class="mb-0">
            <i class="fas fa-filter me-2"></i>
            Filtres
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('invoices.index') }}" class="row g-3">
            <div class="col-md-3">
                <select name="type" class="form-control bg-dark text-white border-0">
                    <option value="">Tous les types</option>
                    <option value="purchase" {{ request('type') == 'purchase' ? 'selected' : '' }}>Achat</option>
                    <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>Vente</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <select name="payment_status" class="form-control bg-dark text-white border-0">
                    <option value="">Tous les statuts</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Payé</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="cancelled" {{ request('payment_status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control bg-dark text-white border-0" 
                       value="{{ request('date_from') }}" placeholder="Date de">
            </div>
            
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control bg-dark text-white border-0" 
                       value="{{ request('date_to') }}" placeholder="Date à">
            </div>
            
            <div class="col-md-2">
                <button type="submit" class="btn-premium w-100">
                    <i class="fas fa-search me-2"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statistiques des factures -->
<div class="row g-4 mb-4">
    <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card-premium">
            <div class="stat-icon-premium">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div class="stat-value">{{ $invoices->total() }}</div>
            <div class="stat-label">Total factures</div>
        </div>
    </div>
    
    <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-card-premium">
            <div class="stat-icon-premium">
                <i class="fas fa-shopping-cart"></i>
            </div>
            @php
                $totalAchats = $invoices->where('type', 'purchase')->sum('total_amount');
            @endphp
            <div class="stat-value">{{ number_format($totalAchats, 0) }} €</div>
            <div class="stat-label">Total achats</div>
        </div>
    </div>
    
    <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
        <div class="stat-card-premium">
            <div class="stat-icon-premium">
                <i class="fas fa-chart-line"></i>
            </div>
            @php
                $totalVentes = $invoices->where('type', 'sale')->sum('total_amount');
            @endphp
            <div class="stat-value">{{ number_format($totalVentes, 0) }} €</div>
            <div class="stat-label">Total ventes</div>
        </div>
    </div>
    
    <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
        <div class="stat-card-premium">
            <div class="stat-icon-premium">
                <i class="fas fa-clock"></i>
            </div>
            @php
                $enAttente = $invoices->where('payment_status', 'pending')->count();
            @endphp
            <div class="stat-value">{{ $enAttente }}</div>
            <div class="stat-label">En attente</div>
        </div>
    </div>
</div>

<!-- Liste des factures -->
<div class="premium-card" data-aos="fade-up">
    <div class="card-header-premium d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>
            Liste des factures
        </h5>
        <span class="badge" style="background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);">
            {{ $invoices->total() }} facture(s)
        </span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>N° Facture</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Montant</th>
                        <th>Client/Fournisseur</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                    <tr>
                        <td class="fw-bold">
                            <span style="color: #4285f4;">{{ $invoice->invoice_number }}</span>
                        </td>
                        <td>{{ $invoice->invoice_date->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($invoice->type == 'purchase')
                                <span class="badge" style="background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);">Achat</span>
                            @else
                                <span class="badge" style="background: linear-gradient(135deg, #BC4639 0%, #5C2018 100%);">Vente</span>
                            @endif
                        </td>
                        <td>{{ $invoice->product->name }}</td>
                        <td>{{ $invoice->quantity }}</td>
                        <td class="fw-bold" style="color: #4285f4;">{{ number_format($invoice->total_amount, 2) }} €</td>
                        <td>{{ $invoice->customer_supplier ?? '-' }}</td>
                        <td>
                            @if($invoice->payment_status == 'paid')
                                <span class="badge" style="background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);">Payé</span>
                            @elseif($invoice->payment_status == 'pending')
                                <span class="badge" style="background: linear-gradient(135deg, #BC4639 0%, #5C2018 100%);">En attente</span>
                            @else
                                <span class="badge" style="background: #5C2018;">Annulé</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('invoices.show', $invoice) }}" 
                               class="btn-premium btn-sm me-1"
                               data-bs-toggle="tooltip" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('invoices.pdf', $invoice) }}" 
                               class="btn-premium btn-sm me-1"
                               data-bs-toggle="tooltip" title="Télécharger PDF">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            @if($invoice->payment_status == 'pending')
                            <form action="{{ route('invoices.update-payment-status', $invoice) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="payment_status" value="paid">
                                <button type="submit" class="btn-premium btn-sm"
                                        data-bs-toggle="tooltip" title="Marquer comme payé">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="fas fa-file-invoice fa-3x mb-3" style="color: #D4A59A;"></i>
                            <p class="text-white-50">Aucune facture trouvée</p>
                            <a href="{{ route('invoices.create') }}" class="btn-premium btn-sm">
                                <i class="fas fa-plus me-2"></i>Créer une facture
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination stylisée -->
        <div class="d-flex justify-content-center mt-4">
            @if ($invoices->hasPages())
                <nav aria-label="Pagination">
                    <ul class="pagination-custom">
                        {{-- Page précédente --}}
                        @if ($invoices->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $invoices->previousPageUrl() }}">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Numéros de page --}}
                        @foreach ($invoices->getUrlRange(1, $invoices->lastPage()) as $page => $url)
                            @if ($page == $invoices->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Page suivante --}}
                        @if ($invoices->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $invoices->nextPageUrl() }}">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            </li>
                        @endif
                    </ul>
                </nav>
            @endif
        </div>
    </div>
</div>
@endsection