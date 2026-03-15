<!-- resources/views/notifications/partials/low-stock-details.blade.php -->
<div class="p-4 rounded" style="background: {{ $config['bgColor'] }};">
    <h5 class="mb-3" style="color: {{ $config['color'] }};">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Détails de l'alerte stock
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
                <small class="text-white-50 d-block">SKU</small>
                <span class="fw-bold text-white">{{ $data['sku'] ?? 'N/A' }}</span>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Stock actuel</small>
                <span class="fw-bold" style="color: #BC4639; font-size: 1.2rem;">{{ $data['current_stock'] ?? 0 }}</span>
                <small class="text-white-50">unités</small>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Stock minimum</small>
                <span class="fw-bold text-white">{{ $data['min_stock'] ?? 0 }}</span>
                <small class="text-white-50">unités</small>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                <small class="text-white-50 d-block">Manque</small>
                @php
                    $manque = ($data['min_stock'] ?? 0) - ($data['current_stock'] ?? 0);
                @endphp
                <span class="fw-bold" style="color: {{ $manque > 0 ? '#BC4639' : '#4285f4' }};">{{ max(0, $manque) }}</span>
                <small class="text-white-50">unités</small>
            </div>
        </div>
        
        @if(isset($data['current_stock']) && isset($data['min_stock']) && $data['min_stock'] > 0)
            @php
                $percentage = min(100, ($data['current_stock'] / $data['min_stock']) * 100);
                $progressColor = $percentage < 50 ? '#BC4639' : ($percentage < 75 ? '#D4A59A' : '#4285f4');
            @endphp
            <div class="col-12">
                <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                    <small class="text-white-50 d-block mb-2">Niveau de stock</small>
                    <div class="progress" style="height: 25px; background: rgba(0,0,0,0.3); border-radius: 12px;">
                        <div class="progress-bar" 
                             role="progressbar"
                             style="width: {{ $percentage }}%; background: {{ $progressColor }}; border-radius: 12px;"
                             aria-valuenow="{{ $percentage }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ number_format($percentage, 1) }}%
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>