<!-- resources/views/notifications/show.blade.php -->
@extends('layouts.app')

@section('title', 'Détail notification')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 fade-in">
    <div>
        <h1 class="h2 mb-1">
            <i class="fas fa-bell me-2 text-primary"></i>Détail de la notification
        </h1>
    </div>
    <div>
        <a href="{{ route('notifications.index') }}" class="btn btn-outline-custom me-2">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger-custom" onclick="return confirm('Supprimer cette notification ?')">
                <i class="fas fa-trash me-1"></i> Supprimer
            </button>
        </form>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        @php
            $data = $notification->data;
            $notificationType = $data['type'] ?? 'general';
            $isUnread = $notification->unread();
            
            $typeConfig = [
                'low_stock' => [
                    'name' => 'Alerte Stock Faible',
                    'icon' => 'exclamation-triangle',
                    'color' => 'warning',
                    'bgSoft' => 'rgba(245, 158, 11, 0.1)'
                ],
                'stock_movement' => [
                    'name' => 'Mouvement de Stock',
                    'icon' => 'exchange-alt',
                    'color' => 'info',
                    'bgSoft' => 'rgba(59, 130, 246, 0.1)'
                ],
                'invoice_generated' => [
                    'name' => 'Facture Générée',
                    'icon' => 'file-invoice',
                    'color' => 'success',
                    'bgSoft' => 'rgba(16, 185, 129, 0.1)'
                ],
                'default' => [
                    'name' => 'Notification',
                    'icon' => 'bell',
                    'color' => 'secondary',
                    'bgSoft' => 'rgba(100, 116, 139, 0.1)'
                ]
            ];
            
            $config = $typeConfig[$notificationType] ?? $typeConfig['default'];
        @endphp
        
        <div class="card-classic">
            <div class="card-header-classic d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <span class="badge bg-{{ $config['color'] }} me-2">
                        <i class="fas fa-{{ $config['icon'] }} me-1"></i> {{ $config['name'] }}
                    </span>
                    @if($isUnread)
                        <span class="badge bg-primary">Non lue</span>
                    @else
                        <span class="badge bg-secondary">Lue</span>
                    @endif
                </div>
                <small class="text-muted">
                    <i class="fas fa-clock me-1"></i> {{ $notification->created_at->format('d/m/Y H:i:s') }}
                </small>
            </div>
            
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: {{ $config['bgSoft'] }};">
                        <i class="fas fa-{{ $config['icon'] }} fa-3x text-{{ $config['color'] }}"></i>
                    </div>
                    <h3 class="text-{{ $config['color'] }}">{{ $data['message'] ?? 'Notification' }}</h3>
                </div>
                
                @if($notificationType == 'low_stock')
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i> Détails de l'alerte stock</h5>
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Produit :</th>
                                <td>{{ $data['product_name'] ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>SKU :</th>
                                <td>{{ $data['sku'] ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Stock actuel :</th>
                                <td><span class="badge bg-danger">{{ $data['current_stock'] ?? 0 }} unités</span></td>
                            </tr>
                            <tr>
                                <th>Stock minimum :</th>
                                <td>{{ $data['min_stock'] ?? 0 }} unités</span></td>
                            </tr>
                            <tr>
                                <th>Quantité manquante :</th>
                                <td class="text-danger fw-bold">{{ ($data['min_stock'] ?? 0) - ($data['current_stock'] ?? 0) }} unités</span></td>
                            </tr>
                        </table>
                        
                        @if(isset($data['current_stock']) && isset($data['min_stock']) && $data['min_stock'] > 0)
                            @php
                                $percentage = min(100, ($data['current_stock'] / $data['min_stock']) * 100);
                            @endphp
                            <div class="mt-3">
                                <small class="text-muted">Niveau de stock</small>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $percentage < 50 ? 'danger' : ($percentage < 75 ? 'warning' : 'success') }}" 
                                         style="width: {{ $percentage }}%;"></div>
                                </div>
                                <small class="text-muted">{{ number_format($percentage, 1) }}%</small>
                            </div>
                        @endif
                    </div>
                    
                @elseif($notificationType == 'stock_movement')
                    <div class="alert alert-info">
                        <h5><i class="fas fa-exchange-alt me-2"></i> Détails du mouvement</h5>
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Produit :</th>
                                <td>{{ $data['product_name'] ?? 'N/A' }}</span></td>
                            </tr>
                            <tr>
                                <th>Type :</th>
                                <td>
                                    @if(($data['movement_type'] ?? '') == 'in')
                                        <span class="badge bg-success">Entrée</span>
                                    @else
                                        <span class="badge bg-danger">Sortie</span>
                                    @endif
                                 </span>
                            </td>
                            <tr>
                                <th>Quantité :</th>
                                <td>{{ $data['quantity'] ?? 0 }} unités</span></td>
                            </tr>
                            @if(isset($data['unit_price']))
                            <tr>
                                <th>Prix unitaire :</th>
                                <td>{{ number_format($data['unit_price'], 2) }} DH</span></td>
                            </tr>
                            @endif
                            @if(isset($data['total_price']))
                            <tr>
                                <th>Total :</th>
                                <td class="fw-bold text-{{ $config['color'] }}">{{ number_format($data['total_price'], 2) }} DH</span></td>
                            </tr>
                            @endif
                            @if(isset($data['reason']))
                            <tr>
                                <th>Motif :</th>
                                <td>{{ $data['reason'] }}</span></td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    
                @elseif($notificationType == 'invoice_generated')
                    <div class="alert alert-success">
                        <h5><i class="fas fa-file-invoice me-2"></i> Détails de la facture</h5>
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Numéro facture :</th>
                                <td><strong class="text-{{ $config['color'] }}">{{ $data['invoice_number'] ?? 'N/A' }}</strong></td>
                            </tr>
                            <tr>
                                <th>Produit :</th>
                                <td>{{ $data['product_name'] ?? 'N/A' }}</span></td>
                            </tr>
                            <tr>
                                <th>Quantité :</th>
                                <td>{{ $data['quantity'] ?? 0 }} unités</span></td>
                            </tr>
                            @if(isset($data['unit_price']))
                            <tr>
                                <th>Prix unitaire :</th>
                                <td>{{ number_format($data['unit_price'], 2) }} DH</span></td>
                            </tr>
                            @endif
                            <tr>
                                <th>Total :</th>
                                <td class="fw-bold text-{{ $config['color'] }}">{{ number_format($data['total_amount'] ?? 0, 2) }} DH</span></td>
                            </tr>
                            @if(isset($data['customer_supplier']))
                            <tr>
                                <th>Client/Fournisseur :</th>
                                <td>{{ $data['customer_supplier'] }}</span></td>
                            </tr>
                            @endif
                        </table>
                    </div>
                @endif
                
                @if(isset($data['action_url']))
                    <div class="text-center mt-4">
                        <a href="{{ url($data['action_url']) }}" class="btn btn-primary-custom">
                            <i class="fas fa-eye me-1"></i> Voir les détails complets
                        </a>
                    </div>
                @endif
            </div>
            
            <div class="card-footer text-muted">
                <div class="d-flex justify-content-between">
                    <small>
                        <i class="fas fa-clock me-1"></i>
                        Reçue le {{ $notification->created_at->format('d/m/Y à H:i:s') }}
                    </small>
                    @if($notification->read_at)
                        <small>
                            <i class="fas fa-check-circle me-1 text-success"></i>
                            Lue le {{ $notification->read_at->format('d/m/Y à H:i:s') }}
                        </small>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Actions supplémentaires -->
        <div class="text-center mt-4">
            @if($isUnread)
                <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-custom">
                        <i class="fas fa-check me-1"></i> Marquer comme lu
                    </button>
                </form>
            @endif
            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline ms-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-custom text-danger" onclick="return confirm('Supprimer cette notification ?')">
                    <i class="fas fa-trash me-1"></i> Supprimer définitivement
                </button>
            </form>
        </div>
    </div>
</div>
@endsection