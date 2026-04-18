<!-- resources/views/products/fifo-history.blade.php -->
@extends('layouts.app')

@section('title', 'Historique FIFO - ' . $product->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 fade-in">
    <div>
        <h1 class="h2 mb-1">Historique FIFO</h1>
        <p class="text-muted">{{ $product->name }} ({{ $product->sku }})</p>
    </div>
    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-custom">
        <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="card-classic">
    <div class="card-header-classic">
        <i class="fas fa-chart-line me-2"></i> Mouvements FIFO
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-classic mb-0">
                <thead>
                    <tr>
                        <th rowspan="2" class="align-middle">Date</th>
                        <th colspan="3" class="text-center bg-success bg-opacity-10">Entrées</th>
                        <th colspan="3" class="text-center bg-danger bg-opacity-10">Sorties</th>
                        <th colspan="3" class="text-center bg-info bg-opacity-10">Stock théorique</th>
                    </tr>
                    <tr>
                        <th>Qté</th>
                        <th>PU</th>
                        <th>Valeur</th>
                        <th>Qté</th>
                        <th>PU</th>
                        <th>Valeur</th>
                        <th>Qté</th>
                        <th>PU Moyen</th>
                        <th>Valeur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fifoHistory as $entry)
                    <tr>
                        <td class="fw-bold">{{ $entry['date'] }}</td>
                        
                        @if($entry['type'] == 'in')
                            <td class="text-success">{{ $entry['quantity'] }}</td>
                            <td class="text-success">{{ number_format($entry['unit_price'], 2) }} DH</td>
                            <td class="text-success">{{ number_format($entry['total_value'], 2) }} DH</td>
                        @else
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        @endif
                        
                        @if($entry['type'] == 'out')
                            <td class="text-danger">{{ $entry['quantity'] }}</td>
                            <td class="text-danger">
                                @if(!empty($entry['details']))
                                    @foreach($entry['details'] as $detail)
                                        <small class="d-block">{{ $detail['unit_price'] }} DH</small>
                                    @endforeach
                                @else
                                    {{ number_format($entry['unit_price'], 2) }} DH
                                @endif
                            </td>
                            <td class="text-danger">{{ number_format($entry['total_value'], 2) }} DH</td>
                        @else
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        @endif
                        
                        <td class="fw-bold">{{ $entry['stock_after'] }}</td>
                        <td>
                            @if($entry['stock_after'] > 0)
                                {{ number_format($entry['stock_value_after'] / $entry['stock_after'], 2) }} DH
                            @else
                                -
                            @endif
                        </td>
                        <td class="fw-bold">{{ number_format($entry['stock_value_after'], 2) }} DH</td>
                    </tr>
                    
                    @if($entry['type'] == 'out' && !empty($entry['details']))
                    <tr class="table-light">
                        <td colspan="10" class="small text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Détail FIFO:
                            @foreach($entry['details'] as $detail)
                                <span class="badge bg-secondary me-1">Lot: {{ $detail['quantity'] }} x {{ number_format($detail['unit_price'], 2) }}DH = {{ number_format($detail['subtotal'], 2) }}DH</span>
                            @endforeach
                        </td>
                    </tr>
                    @endif
                    
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <i class="fas fa-chart-line fa-2x mb-2"></i>
                            <p>Aucun mouvement FIFO trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="9" class="text-end">Stock final :</th>
                        <th>{{ $fifoHistory ? end($fifoHistory)['stock_after'] : 0 }}</th>
                    </tr>
                    <tr>
                        <th colspan="9" class="text-end">Valeur du stock final :</th>
                        <th class="fw-bold">{{ $fifoHistory ? number_format(end($fifoHistory)['stock_value_after'], 2) : 0 }} DH</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Lots en stock -->
<div class="card-classic mt-4">
    <div class="card-header-classic">
        <i class="fas fa-cubes me-2"></i> Lots en stock
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-classic mb-0">
                <thead>
                    <tr>
                        <th>Lot</th>
                        <th>Date réception</th>
                        <th>Quantité restante</th>
                        <th>Prix unitaire</th>
                        <th>Valeur</th>
                        <th>Expiration</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $batches = App\Models\ProductBatch::where('product_id', $product->id)
                            ->where('remaining_quantity', '>', 0)
                            ->orderBy('received_date', 'asc')
                            ->get();
                    @endphp
                    
                    @forelse($batches as $batch)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $batch->batch_number }}</span></td>
                        <td>{{ $batch->received_date->format('d/m/Y') }}</td>
                        <td>{{ $batch->remaining_quantity }}</td>
                        <td>{{ number_format($batch->purchase_price, 2) }} DH</td>
                        <td class="fw-bold">{{ number_format($batch->remaining_quantity * $batch->purchase_price, 2) }} DH</td>
                        <td>
                            @if($batch->expiry_date)
                                @if($batch->expiry_date->isPast())
                                    <span class="badge bg-danger">Expiré</span>
                                @else
                                    {{ $batch->expiry_date->format('d/m/Y') }}
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-box-open fa-2x mb-2"></i>
                            <p>Aucun lot en stock</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="2">Total</th>
                        <th>{{ $batches->sum('remaining_quantity') }}</th>
                        <th></th>
                        <th class="fw-bold">{{ number_format($batches->sum(function($b) { return $b->remaining_quantity * $b->purchase_price; }), 2) }} DH</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection