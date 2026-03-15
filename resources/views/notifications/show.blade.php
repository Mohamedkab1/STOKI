<!-- resources/views/notifications/show.blade.php -->
@extends('layouts.app')

@section('title', 'Détail de la notification')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-down">
    <div>
        <h1 class="text-gradient mb-2">
            <i class="fas fa-bell me-2"></i>
            Détail de la notification
        </h1>
    </div>
    <div>
        <a href="{{ route('notifications.index') }}" class="btn-premium me-2">
            <i class="fas fa-arrow-left me-2"></i> Retour
        </a>
        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-premium" onclick="return confirm('Supprimer cette notification ?')">
                <i class="fas fa-trash me-2"></i> Supprimer
            </button>
        </form>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        @php
            $data = $notification->data;
            $notificationType = $data['type'] ?? 'general';
            
            // Configuration des types
            $typeConfig = [
                'low_stock' => [
                    'name' => 'Alerte Stock Faible',
                    'icon' => 'exclamation-triangle',
                    'color' => '#BC4639',
                    'bgColor' => 'rgba(188, 70, 57, 0.1)',
                    'borderColor' => '#BC4639'
                ],
                'stock_movement' => [
                    'name' => 'Mouvement de Stock',
                    'icon' => 'exchange-alt',
                    'color' => '#4285f4',
                    'bgColor' => 'rgba(66, 133, 244, 0.1)',
                    'borderColor' => '#4285f4'
                ],
                'invoice_generated' => [
                    'name' => 'Facture Générée',
                    'icon' => 'file-invoice',
                    'color' => '#D4A59A',
                    'bgColor' => 'rgba(212, 165, 154, 0.1)',
                    'borderColor' => '#D4A59A'
                ],
                'default' => [
                    'name' => 'Notification',
                    'icon' => 'bell',
                    'color' => '#F3E0DC',
                    'bgColor' => 'rgba(243, 224, 220, 0.1)',
                    'borderColor' => '#F3E0DC'
                ]
            ];
            
            $config = $typeConfig[$notificationType] ?? $typeConfig['default'];
        @endphp
        
        <!-- Carte principale -->
        <div class="premium-card mb-4" data-aos="fade-up">
            <!-- En-tête -->
            <div class="card-header-premium d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-center mb-2 mb-sm-0">
                    <span class="badge me-2 p-2" style="background: {{ $config['color'] }};">
                        <i class="fas fa-{{ $config['icon'] }} me-1"></i>
                        {{ $config['name'] }}
                    </span>
                    @if($notification->unread())
                        <span class="badge" style="background: #4285f4;">Non lue</span>
                    @else
                        <span class="badge" style="background: #5C2018;">Lue</span>
                    @endif
                </div>
                <small class="text-white-50">
                    <i class="fas fa-clock me-1" style="color: {{ $config['color'] }};"></i>
                    {{ $notification->created_at->format('d/m/Y H:i:s') }}
                </small>
            </div>
            
            <!-- Corps -->
            <div class="card-body">
                <!-- Icône et message principal -->
                <div class="text-center mb-4">
                    <span class="badge p-4 mb-3 d-inline-flex align-items-center justify-content-center" 
                          style="background: {{ $config['bgColor'] }}; width: 100px; height: 100px; border-radius: 50%; border: 2px solid {{ $config['color'] }};">
                        <i class="fas fa-{{ $config['icon'] }} fa-3x" style="color: {{ $config['color'] }};"></i>
                    </span>
                    <h3 style="color: {{ $config['color'] }};">{{ $data['message'] ?? 'Notification' }}</h3>
                </div>
                
                <!-- Détails selon le type -->
                @if($notificationType == 'low_stock')
                    @include('notifications.partials.low-stock-details', ['data' => $data, 'config' => $config])
                    
                @elseif($notificationType == 'stock_movement')
                    @include('notifications.partials.stock-movement-details', ['data' => $data, 'config' => $config])
                    
                @elseif($notificationType == 'invoice_generated')
                    @include('notifications.partials.invoice-details', ['data' => $data, 'config' => $config])
                    
                @else
                    @include('notifications.partials.default-details', ['data' => $data, 'config' => $config])
                @endif
                
                <!-- Bouton d'action -->
                @if(isset($data['action_url']))
                    <div class="text-center mt-4">
                        <a href="{{ url($data['action_url']) }}" class="btn-premium btn-premium-primary px-5 py-3">
                            <i class="fas fa-eye me-2"></i>
                            Voir les détails complets
                        </a>
                    </div>
                @endif
            </div>
            
            <!-- Pied de page -->
            <div class="card-footer" style="background: rgba(0,0,0,0.2);">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <small class="text-white-50">
                        <i class="fas fa-clock me-1" style="color: {{ $config['color'] }};"></i>
                        Reçue le {{ $notification->created_at->format('d/m/Y à H:i:s') }}
                    </small>
                    @if($notification->read_at)
                        <small class="text-white-50">
                            <i class="fas fa-check-circle me-1" style="color: #4285f4;"></i>
                            Lue le {{ $notification->read_at->format('d/m/Y à H:i:s') }}
                        </small>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Actions supplémentaires -->
        <div class="text-center">
            @if($notification->unread())
                <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-premium me-2">
                        <i class="fas fa-check me-2"></i> Marquer comme lu
                    </button>
                </form>
            @endif
            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-premium" onclick="return confirm('Supprimer cette notification ?')">
                    <i class="fas fa-trash me-2"></i> Supprimer définitivement
                </button>
            </form>
        </div>
    </div>
</div>
@endsection