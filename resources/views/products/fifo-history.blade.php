<!-- resources/views/products/fifo-history.blade.php -->
@extends('layouts.app')

@section('title', 'Historique FIFO - ' . $product->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-down">
    <div>
        <h1 class="text-gradient mb-2">
            <i class="fas fa-chart-line me-2"></i>
            Historique FIFO
        </h1>
        <p class="text-white-50">{{ $product->name }} ({{ $product->sku }})</p>
    </div>
    <a href="{{ route('products.show', $product) }}" class="btn-premium">
        <i class="fas fa-arrow-left me-2"></i> Retour
    </a>
</div>

<div class="premium-card" data-aos="fade-up">
    <div class="card-header-premium">
        <h5 class="mb-0">
            <i class="fas fa-table me-2"></i>
            Mouvements FIFO
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th rowspan="2" class="align-middle">Date</th>
                        <th colspan="3" class="text-center" style="color: #00f3ff;">Entrées</th>
                        <th colspan="3" class="text-center text-danger">Sorties</th>
                        <th colspan="3" class="text-center text-info">Stock théorique</th>
                    </tr>
                    <tr>
                        <th style="color: #00f3ff;">Qté</th>
                        <th style="color: #00f3ff;">PU</th>
                        <th style="color: #00f3ff;">Valeur</th>
                        <th class="text-danger">Qté</th>
                        <th class="text-danger">PU</th>
                        <th class="text-danger">Valeur</th>
                        <th class="text-info">Qté</th>
                        <th class="text-info">PU Moyen</th>
                        <th class="text-info">Valeur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fifoHistory as $entry)
                    <tr>
                        <td class="fw-bold text-white">{{ $entry['date'] }}</td>
                        
                        @if($entry['type'] == 'in')
                            <td style="color: #00f3ff;">{{ $entry['quantity'] }}</td>
                            <td style="color: #00f3ff;">{{ number_format($entry['unit_price'], 2) }} €</td>
                            <td style="color: #00f3ff;">{{ number_format($entry['total_value'], 2) }} €</td>
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
                                        <small class="d-block">{{ $detail['unit_price'] }} € (lot {{ $detail['batch_number'] }})</small>
                                    @endforeach
                                @else
                                    {{ number_format($entry['unit_price'], 2) }} €
                                @endif
                            </td>
                            <td class="text-danger">{{ number_format($entry['total_value'], 2) }} €</td>
                        @else
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        @endif
                        
                        <td class="fw-bold text-info">{{ $entry['stock_after'] }}</td>
                        <td class="text-info">
                            @if($entry['stock_after'] > 0)
                                {{ number_format($entry['stock_value_after'] / $entry['stock_after'], 2) }} €
                            @else
                                -
                            @endif
                        </td>
                        <td class="fw-bold text-info">{{ number_format($entry['stock_value_after'], 2) }} €</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-white-50">Aucun mouvement trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-active">
                    <tr>
                        <th colspan="9" class="text-end text-white">Stock final :</th>
                        <th class="text-white">{{ $fifoHistory ? end($fifoHistory)['stock_after'] : 0 }}</th>
                    </tr>
                    <tr>
                        <th colspan="9" class="text-end text-white">Valeur du stock final :</th>
                        <th class="text-white">{{ $fifoHistory ? number_format(end($fifoHistory)['stock_value_after'], 2) : 0 }} €</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection