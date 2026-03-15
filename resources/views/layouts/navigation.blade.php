<!-- resources/views/layouts/navigation.blade.php -->
<nav class="navbar navbar-expand-lg navbar-premium sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="fas fa-cubes me-2"></i>
            Stoki
        </a>
        
        <!-- BOUTON TOGGLE AVEC STYLE -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <i class="fas fa-bars"></i>
            </span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link nav-link-premium {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                       href="{{ route('dashboard') }}">
                        <i class="fas fa-home me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-premium {{ request()->routeIs('products.*') ? 'active' : '' }}" 
                       href="{{ route('products.index') }}">
                        <i class="fas fa-box me-2"></i>
                        <span>Produits</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-premium {{ request()->routeIs('categories.*') ? 'active' : '' }}" 
                       href="{{ route('categories.index') }}">
                        <i class="fas fa-tags me-2"></i>
                        <span>Catégories</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-premium {{ request()->routeIs('invoices.*') ? 'active' : '' }}" 
                       href="{{ route('invoices.index') }}">
                        <i class="fas fa-file-invoice me-2"></i>
                        <span>Factures</span>
                    </a>
                </li>
            </ul>
            
            <ul class="navbar-nav">
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link nav-link-premium position-relative" href="#" id="notificationDropdown" 
                       role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill notification-badge" 
                              style="background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%); color: #F3E0DC; display: none;">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end premium-card" 
                         style="width: 350px; max-height: 400px; overflow-y: auto;">
                        <div class="dropdown-header d-flex justify-content-between align-items-center p-3">
                            <h6 class="mb-0" style="color: #F3E0DC;">Notifications</h6>
                            <button class="btn btn-sm btn-premium mark-all-read">
                                <i class="fas fa-check-double"></i>
                            </button>
                        </div>
                        <div class="notification-list">
                            <!-- Les notifications seront chargées ici -->
                        </div>
                        <div class="dropdown-footer text-center p-3">
                            <a href="{{ route('notifications.index') }}" class="btn-premium btn-sm">
                                Voir toutes les notifications
                            </a>
                        </div>
                    </div>
                </li>
                
                <!-- Utilisateur -->
                <li class="nav-item dropdown">
                    <a class="nav-link nav-link-premium dropdown-toggle" href="#" id="userDropdown" 
                       role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>
                        <span>{{ Auth::user()->name ?? 'Compte' }}</span>
                    </a>
                    <ul class="dropdown-menu premium-card">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" style="color: #BC4639;">
                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
/* Styles spécifiques pour le toggle button */
.navbar-toggler {
    border: 2px solid #4285f4 !important;
    border-radius: 10px !important;
    padding: 8px 12px !important;
    background: transparent !important;
    transition: all 0.3s ease !important;
}

.navbar-toggler:hover {
    background: rgba(66, 133, 244, 0.1) !important;
    transform: scale(1.05);
}

.navbar-toggler:focus {
    box-shadow: 0 0 0 0.2rem rgba(66, 133, 244, 0.25) !important;
    outline: none !important;
}

.navbar-toggler-icon {
    background-image: none !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: auto !important;
    height: auto !important;
}

.navbar-toggler-icon i {
    color: #4285f4 !important;
    font-size: 24px !important;
    transition: all 0.3s ease !important;
}

.navbar-toggler:hover .navbar-toggler-icon i {
    color: #D4A59A !important;
}

/* Animation du toggle */
.navbar-toggler[aria-expanded="true"] .navbar-toggler-icon i {
    transform: rotate(90deg);
}

/* Style pour mobile */
@media (max-width: 991.98px) {
    .navbar-premium {
        padding: 10px 15px;
    }
    
    .navbar-collapse {
        background: rgba(92, 32, 24, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 20px;
        margin-top: 15px;
        border: 1px solid rgba(212, 165, 154, 0.3);
    }
    
    .nav-link-premium {
        text-align: center;
        margin: 5px 0 !important;
    }
}
</style>

<script>
// Script pour les notifications
$(document).ready(function() {
    function loadNotifications() {
        $.get('/notifications/latest', function(data) {
            let notificationList = $('.notification-list');
            let badge = $('.notification-badge');
            
            if (data.unread_count > 0) {
                badge.text(data.unread_count).show();
            } else {
                badge.hide();
            }
            
            if (data.notifications && data.notifications.length > 0) {
                let html = '';
                data.notifications.forEach(notification => {
                    let notifData = notification.data;
                    let time = new Date(notification.created_at).toLocaleString();
                    let icon = notifData.icon || 'bell';
                    html += `
                        <a href="/notifications/${notification.id}" class="dropdown-item ${notification.read_at ? '' : 'bg-dark'}">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-${icon}" style="color: #4285f4;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <small style="color: #D4A59A;" class="d-block">${time}</small>
                                    <span style="color: #F3E0DC;">${notifData.message || 'Nouvelle notification'}</span>
                                </div>
                            </div>
                        </a>
                    `;
                });
                notificationList.html(html);
            } else {
                notificationList.html(`
                    <div class="text-center p-4">
                        <i class="fas fa-bell-slash fa-3x" style="color: #4285f4;"></i>
                        <p style="color: #D4A59A;" class="mb-0">Aucune notification</p>
                    </div>
                `);
            }
        });
    }
    
    loadNotifications();
    setInterval(loadNotifications, 30000);
    
    $('.mark-all-read').click(function() {
        $.post('/notifications/mark-all-read', {
            _token: '{{ csrf_token() }}'
        }, loadNotifications);
    });
});
</script>