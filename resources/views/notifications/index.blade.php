<!-- resources/views/notifications/index.blade.php -->
@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<!-- En-tête de la page -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4" data-aos="fade-down">
    <div class="mb-3 mb-md-0">
        <h1 class="text-gradient mb-2">
            <i class="fas fa-bell me-2"></i>
            Centre de Notifications
        </h1>
        <p class="text-white-50">Restez informé de toutes les activités</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        @if($unreadCount > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn-premium">
                    <i class="fas fa-check-double me-2"></i> Tout marquer comme lu
                </button>
            </form>
        @endif
        <form action="{{ route('notifications.destroy-all') }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-premium" onclick="return confirm('Êtes-vous sûr de vouloir supprimer toutes les notifications ?')">
                <i class="fas fa-trash me-2"></i> Tout supprimer
            </button>
        </form>
    </div>
</div>

<!-- Statistiques des notifications -->
<div class="row g-3 mb-4">
    @php
        $totalNotifications = $notifications->total();
        $readCount = $totalNotifications - $unreadCount;
        $lowStockCount = 0;
        $movementCount = 0;
        $invoiceCount = 0;
        
        foreach($notifications as $notif) {
            $type = $notif->data['type'] ?? 'general';
            if($type == 'low_stock') $lowStockCount++;
            if($type == 'stock_movement') $movementCount++;
            if($type == 'invoice_generated') $invoiceCount++;
        }
    @endphp
    
    <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card-premium">
            <div class="stat-icon-premium">
                <i class="fas fa-bell"></i>
            </div>
            <div class="stat-value">{{ $totalNotifications }}</div>
            <div class="stat-label">Total</div>
        </div>
    </div>
    
    <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-card-premium">
            <div class="stat-icon-premium">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-value">{{ $unreadCount }}</div>
            <div class="stat-label">Non lues</div>
        </div>
    </div>
    
    <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
        <div class="stat-card-premium">
            <div class="stat-icon-premium">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">{{ $readCount }}</div>
            <div class="stat-label">Lues</div>
        </div>
    </div>
    
    <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="400">
        <div class="stat-card-premium">
            <div class="stat-icon-premium">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-value">{{ $lowStockCount }}</div>
            <div class="stat-label">Alertes</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Filtres latéraux -->
    <div class="col-lg-3" data-aos="fade-right">
        <div class="premium-card sticky-top" style="top: 90px; z-index: 99;">
            <div class="card-header-premium">
                <h5 class="mb-0">
                    <i class="fas fa-filter me-2"></i>
                    Filtres
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush bg-transparent">
                    <a href="{{ route('notifications.index') }}" 
                       class="list-group-item list-group-item-action bg-transparent border-0 d-flex justify-content-between align-items-center py-3 px-4"
                       style="{{ !request('type') ? 'background: rgba(66, 133, 244, 0.15); border-left: 4px solid #4285f4; color: #4285f4;' : 'color: #F3E0DC;' }}">
                        <span>
                            <i class="fas fa-bell me-2" style="color: {{ !request('type') ? '#4285f4' : '#D4A59A' }};"></i>
                            Toutes
                        </span>
                        <span class="badge" style="background: {{ !request('type') ? 'linear-gradient(135deg, #4285f4 0%, #5C2018 100%)' : 'rgba(212, 165, 154, 0.3)' }};">
                            {{ $totalNotifications }}
                        </span>
                    </a>
                    
                    <a href="{{ route('notifications.index', ['type' => 'low_stock']) }}" 
                       class="list-group-item list-group-item-action bg-transparent border-0 d-flex justify-content-between align-items-center py-3 px-4"
                       style="{{ request('type') == 'low_stock' ? 'background: rgba(188, 70, 57, 0.15); border-left: 4px solid #BC4639; color: #BC4639;' : 'color: #F3E0DC;' }}">
                        <span>
                            <i class="fas fa-exclamation-triangle me-2" style="color: {{ request('type') == 'low_stock' ? '#BC4639' : '#D4A59A' }};"></i>
                            Stocks faibles
                        </span>
                        <span class="badge" style="background: {{ request('type') == 'low_stock' ? '#BC4639' : 'rgba(212, 165, 154, 0.3)' }};">
                            {{ $lowStockCount }}
                        </span>
                    </a>
                    
                    <a href="{{ route('notifications.index', ['type' => 'stock_movement']) }}" 
                       class="list-group-item list-group-item-action bg-transparent border-0 d-flex justify-content-between align-items-center py-3 px-4"
                       style="{{ request('type') == 'stock_movement' ? 'background: rgba(66, 133, 244, 0.15); border-left: 4px solid #4285f4; color: #4285f4;' : 'color: #F3E0DC;' }}">
                        <span>
                            <i class="fas fa-exchange-alt me-2" style="color: {{ request('type') == 'stock_movement' ? '#4285f4' : '#D4A59A' }};"></i>
                            Mouvements
                        </span>
                        <span class="badge" style="background: {{ request('type') == 'stock_movement' ? 'linear-gradient(135deg, #4285f4 0%, #5C2018 100%)' : 'rgba(212, 165, 154, 0.3)' }};">
                            {{ $movementCount }}
                        </span>
                    </a>
                    
                    <a href="{{ route('notifications.index', ['type' => 'invoice_generated']) }}" 
                       class="list-group-item list-group-item-action bg-transparent border-0 d-flex justify-content-between align-items-center py-3 px-4"
                       style="{{ request('type') == 'invoice_generated' ? 'background: rgba(212, 165, 154, 0.15); border-left: 4px solid #D4A59A; color: #D4A59A;' : 'color: #F3E0DC;' }}">
                        <span>
                            <i class="fas fa-file-invoice me-2" style="color: {{ request('type') == 'invoice_generated' ? '#D4A59A' : '#D4A59A' }};"></i>
                            Factures
                        </span>
                        <span class="badge" style="background: {{ request('type') == 'invoice_generated' ? '#D4A59A' : 'rgba(212, 165, 154, 0.3)' }}; color: {{ request('type') == 'invoice_generated' ? '#5C2018' : '#F3E0DC' }};">
                            {{ $invoiceCount }}
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Liste des notifications -->
    <div class="col-lg-9" data-aos="fade-left">
        <div class="premium-card">
            <div class="card-header-premium d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="mb-2 mb-sm-0">
                    <i class="fas fa-list me-2"></i>
                    Liste des notifications
                </h5>
                <span class="badge" style="background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);">
                    {{ $totalNotifications }} notification(s)
                </span>
            </div>
            <div class="card-body p-0">
                @forelse($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $notificationType = $data['type'] ?? 'general';
                        
                        // Configuration des couleurs par type
                        $typeConfig = [
                            'low_stock' => [
                                'color' => '#BC4639',
                                'bgColor' => 'rgba(188, 70, 57, 0.1)',
                                'icon' => 'exclamation-triangle',
                                'border' => '#BC4639'
                            ],
                            'stock_movement' => [
                                'color' => '#4285f4',
                                'bgColor' => 'rgba(66, 133, 244, 0.1)',
                                'icon' => 'exchange-alt',
                                'border' => '#4285f4'
                            ],
                            'invoice_generated' => [
                                'color' => '#D4A59A',
                                'bgColor' => 'rgba(212, 165, 154, 0.1)',
                                'icon' => 'file-invoice',
                                'border' => '#D4A59A'
                            ],
                            'default' => [
                                'color' => '#F3E0DC',
                                'bgColor' => 'rgba(243, 224, 220, 0.1)',
                                'icon' => 'bell',
                                'border' => '#F3E0DC'
                            ]
                        ];
                        
                        $config = $typeConfig[$notificationType] ?? $typeConfig['default'];
                    @endphp
                    
                    <div class="notification-item p-3 p-md-4 {{ !$loop->last ? 'border-bottom' : '' }}" 
                         style="background: {{ $config['bgColor'] }}; border-left: 4px solid {{ $config['border'] }}; cursor: pointer; transition: all 0.3s ease;"
                         onclick="window.location='{{ route('notifications.show', $notification->id) }}'">
                        
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start">
                            <div class="d-flex mb-2 mb-sm-0 w-100">
                                <!-- Icône -->
                                <div class="me-3">
                                    <span class="badge p-3 d-inline-flex align-items-center justify-content-center" 
                                          style="background: {{ $config['color'] }}20; width: 45px; height: 45px; border-radius: 50%;">
                                        <i class="fas fa-{{ $config['icon'] }}" style="color: {{ $config['color'] }}; font-size: 18px;"></i>
                                    </span>
                                </div>
                                
                                <!-- Contenu -->
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                        <h6 class="mb-0 {{ $notification->unread() ? 'fw-bold' : '' }}" 
                                            style="color: {{ $notification->unread() ? $config['color'] : '#F3E0DC' }}; font-size: 0.95rem;">
                                            {{ Str::limit($data['message'] ?? 'Nouvelle notification', 60) }}
                                        </h6>
                                        @if($notification->unread())
                                            <span class="badge" style="background: {{ $config['color'] }}; font-size: 0.7rem;">Nouveau</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Aperçu des détails responsive -->
                                    <div class="mb-2">
                                        @if($notificationType == 'low_stock' && isset($data['product_name']))
                                            <small class="text-white-50 d-block d-sm-inline">
                                                <i class="fas fa-box me-1" style="color: {{ $config['color'] }};"></i>
                                                {{ Str::limit($data['product_name'], 30) }} - 
                                                Stock: <span style="color: #BC4639;">{{ $data['current_stock'] ?? 0 }}</span> / Min: {{ $data['min_stock'] ?? 0 }}
                                            </small>
                                            
                                        @elseif($notificationType == 'stock_movement' && isset($data['product_name']))
                                            <small class="text-white-50 d-block d-sm-inline">
                                                <i class="fas fa-box me-1" style="color: {{ $config['color'] }};"></i>
                                                {{ Str::limit($data['product_name'], 30) }} - 
                                                @if(($data['movement_type'] ?? '') == 'in')
                                                    <span style="color: #4285f4;">Entrée</span>
                                                @else
                                                    <span style="color: #BC4639;">Sortie</span>
                                                @endif
                                                : {{ $data['quantity'] ?? 0 }} unités
                                            </small>
                                            
                                        @elseif($notificationType == 'invoice_generated' && isset($data['invoice_number']))
                                            <small class="text-white-50 d-block d-sm-inline">
                                                <i class="fas fa-file-invoice me-1" style="color: {{ $config['color'] }};"></i>
                                                Facture {{ $data['invoice_number'] }} - 
                                                {{ number_format($data['total_amount'] ?? 0, 2) }} €
                                            </small>
                                        @endif
                                    </div>
                                    
                                    <!-- Date -->
                                    <small class="text-white-50">
                                        <i class="fas fa-clock me-1" style="color: {{ $config['color'] }};"></i>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            
                            <!-- Menu d'actions -->
                            <div class="dropdown ms-sm-3 align-self-start">
                                <button class="btn-premium btn-sm" type="button" data-bs-toggle="dropdown" 
                                        style="padding: 5px 10px; min-width: auto;" onclick="event.stopPropagation();">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end premium-card" style="min-width: 200px;">
                                    @if($notification->unread())
                                        <li>
                                            <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item" style="color: #4285f4;" onclick="event.stopPropagation();">
                                                    <i class="fas fa-check me-2"></i> Marquer comme lu
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{ route('notifications.show', $notification->id) }}" style="color: #D4A59A;">
                                            <i class="fas fa-eye me-2"></i> Voir détails
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item" style="color: #BC4639;" onclick="event.stopPropagation();">
                                                <i class="fas fa-trash me-2"></i> Supprimer
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                @empty
                    <div class="text-center py-5 px-3">
                        <i class="fas fa-bell-slash fa-4x mb-3" style="color: #D4A59A;"></i>
                        <h4 style="color: #F3E0DC;">Aucune notification</h4>
                        <p class="text-white-50">Vous n'avez pas encore de notifications.</p>
                        <p class="text-white-50 small">Les notifications apparaîtront ici après chaque opération.</p>
                    </div>
                @endforelse
                
                <!-- Pagination -->
                @if($notifications->hasPages())
                    <div class="d-flex justify-content-center py-4">
                        {{ $notifications->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
