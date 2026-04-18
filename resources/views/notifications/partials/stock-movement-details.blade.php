<!-- resources/views/notifications/partials/stock-movement-details.blade.php -->
<div class="p-4 rounded" style="background: {{ $config['bgColor'] }};">
    <h5 class="mb-3" style="color: {{ $config['color'] }};">
        <i class="fas fa-exchange-alt me-2"></i>
        Détails du mouvement
    </h5>
    
    <div class="row g-3">
        <div class="col-md-6">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Produit</small>
                <span class="fw-bold text-white">{{ $data['product_name'] ?? 'N/A' }}</span>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Type</small>
                @if(($data['movement_type'] ?? '') == 'in')
                    <span class="badge p-2" style="background: #4285f4; font-size: 14px;">ENTRÉE</span>
                @else
                    <span class="badge p-2" style="background: #BC4639; font-size: 14px;">SORTIE</span>
                @endif
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Quantité</small>
                <span class="fw-bold text-white">{{ $data['quantity'] ?? 0 }}</span>
                <small class="text-white-50">unités</small>
            </div>
        </div>
        
        @if(isset($data['unit_price']))
        <div class="col-md-4">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Prix unitaire</small>
                <span class="fw-bold text-white">{{ number_format($data['unit_price'], 2) }}</span>
                <small class="text-white-50">DH</small>
            </div>
        </div>
        @endif
        
        @if(isset($data['total_price']))
        <div class="col-md-4">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Total</small>
                <span class="fw-bold" style="color: #4285f4;">{{ number_format($data['total_price'], 2) }}</span>
                <small class="text-white-50">DH</small>
            </div>
        </div>
        @endif
        
        @if(isset($data['reason']))
        <div class="col-12">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Motif</small>
                <span class="text-white">{{ $data['reason'] }}</span>
            </div>
        </div>
        @endif
    </div>
</div>