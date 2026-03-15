<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- En-tête -->
<div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-down">
    <div>
        <h1 class="text-gradient mb-2">Dashboard</h1>
        <p class="text-white-50">
            <i class="fas fa-calendar me-2"></i>
            {{ now()->format('l, d F Y') }}
        </p>
    </div>
    <div>
        <button class="btn-premium" onclick="window.location.reload()">
            <i class="fas fa-sync-alt me-2"></i> Actualiser
        </button>
    </div>
</div>

<!-- Statistiques -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card-premium">
            <div class="stat-icon-premium">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-value">{{ $totalProducts }}</div>
            <div class="stat-label">Total Produits</div>
            <small class="text-gradient">
                <i class="fas fa-arrow-up me-1"></i>+12% ce mois
            </small>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-card-premium">
            <div class="stat-icon-premium">
                <i class="fas fa-tags"></i>
            </div>
            <div class="stat-value">{{ $totalCategories }}</div>
            <div class="stat-label">Catégories</div>
            <small class="text-gradient">
                <i class="fas fa-arrow-up me-1"></i>+3 nouvelles
            </small>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
        <div class="stat-card-premium">
            <div class="stat-icon-premium">
                <i class="fas fa-cubes"></i>
            </div>
            <div class="stat-value">{{ number_format($totalStock) }}</div>
            <div class="stat-label">Stock Total</div>
            <small class="text-gradient">
                <i class="fas fa-chart-line me-1"></i>{{ number_format($stockValue, 2) }} €
            </small>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
        <div class="stat-card-premium">
            <div class="stat-icon-premium">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-value">{{ $lowStockProducts }}</div>
            <div class="stat-label">Stock Faible</div>
            <small class="text-danger">
                <i class="fas fa-exclamation-circle me-1"></i>Attention !
            </small>
        </div>
    </div>
</div>

<!-- Graphique à barres et Activités -->
<div class="row g-4 mb-5">
    <div class="col-xl-8" data-aos="fade-right">
        <div class="premium-card">
            <div class="card-header-premium d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="mb-2 mb-sm-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Évolution du Stock (7 derniers jours)
                </h5>
                <div class="d-flex gap-3">
                    <div class="d-flex align-items-center">
                        <span class="badge me-1" style="background: #4285f4; width: 12px; height: 12px; padding: 0;">&nbsp;</span>
                        <small class="text-white-50">Entrées</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge me-1" style="background: #BC4639; width: 12px; height: 12px; padding: 0;">&nbsp;</span>
                        <small class="text-white-50">Sorties</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge me-1" style="background: #D4A59A; width: 12px; height: 12px; padding: 0;">&nbsp;</span>
                        <small class="text-white-50">Net</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="stockChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4" data-aos="fade-left">
        <div class="premium-card">
            <div class="card-header-premium">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Derniers Mouvements
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentMovements as $movement)
                        <div class="list-group-item bg-transparent border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    @if($movement->type == 'in')
                                        <span class="badge p-2" style="background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);">
                                            <i class="fas fa-arrow-down"></i>
                                        </span>
                                    @else
                                        <span class="badge p-2" style="background: #BC4639;">
                                            <i class="fas fa-arrow-up"></i>
                                        </span>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-white">{{ $movement->product->name }}</h6>
                                    <small class="text-white-50">
                                        {{ $movement->quantity }} unités • 
                                        {{ $movement->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center p-4">
                            <i class="fas fa-box-open fa-3x text-gradient mb-3"></i>
                            <p class="text-white-50">Aucun mouvement récent</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deuxième rangée : Produits populaires et Dernières factures -->
<div class="row g-4 mb-5">
    <div class="col-xl-6" data-aos="fade-up">
        <div class="premium-card">
            <div class="card-header-premium">
                <h5 class="mb-0">
                    <i class="fas fa-fire me-2"></i>
                    Top 5 Produits les plus vendus
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Quantité vendue</th>
                                <th>Chiffre d'affaires</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" 
                                                     alt="{{ $product->name }}" 
                                                     style="width: 30px; height: 30px; object-fit: cover; border-radius: 5px; margin-right: 10px;">
                                            @else
                                                <i class="fas fa-box me-2" style="color: #4285f4;"></i>
                                            @endif
                                            {{ $product->name }}
                                        </div>
                                    </td>
                                    <td>{{ $product->total_sold ?? 0 }}</td>
                                    <td style="color: #4285f4;">{{ number_format(($product->total_sold ?? 0) * $product->price, 2) }} €</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-white-50">Aucune vente enregistrée</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-6" data-aos="fade-up">
        <div class="premium-card">
            <div class="card-header-premium">
                <h5 class="mb-0">
                    <i class="fas fa-file-invoice me-2"></i>
                    Dernières Factures
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>N° Facture</th>
                                <th>Date</th>
                                <th>Produit</th>
                                <th>Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentInvoices as $invoice)
                                <tr>
                                    <td><span style="color: #4285f4;">{{ $invoice->invoice_number }}</span></td>
                                    <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $invoice->product->name }}</td>
                                    <td style="color: #4285f4;">{{ number_format($invoice->total_amount, 2) }} €</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-white-50">Aucune facture récente</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Troisième rangée : Distribution par catégorie -->
@if(isset($categoryDistribution) && $categoryDistribution->count() > 0)
<div class="row g-4">
    <div class="col-12" data-aos="fade-up">
        <div class="premium-card">
            <div class="card-header-premium">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Distribution des produits par catégorie
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($categoryDistribution as $category)
                        @php
                            $totalProducts = $categoryDistribution->sum('products_count');
                            $percentage = $totalProducts > 0 ? round(($category->products_count / $totalProducts) * 100) : 0;
                        @endphp
                        <div class="col-md-4 mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-white-50">{{ $category->name }}</span>
                                <span style="color: #4285f4;">{{ $category->products_count }} produits</span>
                            </div>
                            <div class="progress" style="height: 10px; background: rgba(0,0,0,0.3);">
                                <div class="progress-bar" 
                                     style="width: {{ $percentage }}%; background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);"
                                     role="progressbar" 
                                     aria-valuenow="{{ $percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour le graphique
    const labels = {!! json_encode($stockEvolution['labels']) !!};
    const entriesData = {!! json_encode($stockEvolution['entries']) !!};
    const exitsData = {!! json_encode($stockEvolution['exits']) !!};
    const netData = {!! json_encode($stockEvolution['net']) !!};

    // Configuration du graphique à barres
    const ctx = document.getElementById('stockChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Entrées',
                    data: entriesData,
                    backgroundColor: '#4285f4',
                    borderColor: '#4285f4',
                    borderWidth: 1,
                    borderRadius: 5,
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                },
                {
                    label: 'Sorties',
                    data: exitsData,
                    backgroundColor: '#BC4639',
                    borderColor: '#BC4639',
                    borderWidth: 1,
                    borderRadius: 5,
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                },
                {
                    label: 'Net',
                    data: netData,
                    type: 'line',
                    borderColor: '#D4A59A',
                    backgroundColor: 'transparent',
                    borderWidth: 3,
                    pointBackgroundColor: '#D4A59A',
                    pointBorderColor: '#5C2018',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.1,
                    yAxisID: 'y'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Cacher la légende car on l'a déjà dans l'en-tête
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(92, 32, 24, 0.9)',
                    titleColor: '#F3E0DC',
                    bodyColor: '#D4A59A',
                    borderColor: '#4285f4',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(212, 165, 154, 0.1)'
                    },
                    ticks: {
                        color: '#D4A59A',
                        stepSize: 10,
                        callback: function(value) {
                            return value + ' unités';
                        }
                    },
                    title: {
                        display: true,
                        text: 'Quantité',
                        color: '#F3E0DC'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#D4A59A'
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
});
</script>
@endpush