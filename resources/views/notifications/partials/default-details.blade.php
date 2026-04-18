<!-- resources/views/notifications/partials/default-details.blade.php -->
<div class="p-4 rounded" style="background: {{ $config['bgColor'] }};">
    <h5 class="mb-3" style="color: {{ $config['color'] }};">
        <i class="fas fa-info-circle me-2"></i>
        Informations
    </h5>
    
    <div class="row g-3">
        @foreach($data as $key => $value)
            @if(!in_array($key, ['icon', 'color', 'message', 'action_url', 'type']))
                <div class="col-md-6">
                    <div class="p-3 rounded" style="background: rgba(0,0,0,0.2);">
                        <small class="text-white-50 d-block">{{ ucfirst(str_replace('_', ' ', $key)) }}</small>
                        <span class="fw-bold text-white">
                            @if(is_array($value))
                                {{ json_encode($value) }}
                            @elseif(is_numeric($value) && (strpos($key, 'price') !== false || strpos($key, 'amount') !== false || strpos($key, 'total') !== false))
                                {{ number_format($value, 2) }} DH
                            @else
                                {{ $value }}
                            @endif
                        </span>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>