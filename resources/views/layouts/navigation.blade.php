<!-- resources/views/layouts/navigation.blade.php -->
<!-- 
    Navigation Legacy (Bootstrap) — Mise à jour pour utiliser le système de thème.
    Ce fichier est gardé pour la compatibilité avec les pages qui l'utilisent directement.
    La navigation principale est maintenant dans components/layout/navbar.blade.php
-->
<nav class="navbar navbar-expand-lg sticky-top" style="background: var(--navbar-bg); border-bottom: 1px solid var(--border-color); backdrop-filter: blur(10px);">
    <div class="container-fluid">
        <!-- Logo / Brand -->
        <a class="navbar-brand" href="{{ route('dashboard') }}" style="color: #ffffff; font-weight: 800; text-decoration: none;">
            <i class="fas fa-cubes me-2" style="color: #4f46e5;"></i>
            STOKI<span style="color: #4f46e5;">ERP</span>
        </a>
        
        <!-- Bouton toggle pour mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"
                style="border-color: var(--accent);">
            <span class="navbar-toggler-icon">
                <i class="fas fa-bars" style="color: var(--accent);"></i>
            </span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Menu principal - gauche -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                       href="{{ route('dashboard') }}" style="color: var(--text-secondary); transition: all 0.2s ease;">
                        <i class="fas fa-home me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" 
                       href="{{ route('products.index') }}" style="color: var(--text-secondary); transition: all 0.2s ease;">
                        <i class="fas fa-box me-2"></i>
                        <span>Produits</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}" 
                       href="{{ route('stock-movements.index') }}" style="color: var(--text-secondary); transition: all 0.2s ease;">
                        <i class="fas fa-history me-2"></i>
                        <span>Historique Stock</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" 
                       href="{{ route('categories.index') }}" style="color: var(--text-secondary); transition: all 0.2s ease;">
                        <i class="fas fa-tags me-2"></i>
                        <span>Catégories</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}" 
                       href="{{ route('invoices.index') }}" style="color: var(--text-secondary); transition: all 0.2s ease;">
                        <i class="fas fa-file-invoice me-2"></i>
                        <span>Factures</span>
                    </a>
                </li>
                
            </ul>
            
            <!-- Menu secondaire - droite -->
            <ul class="navbar-nav">
                <!-- Theme Toggle -->
                <li class="nav-item d-flex align-items-center me-2">
                    <button onclick="toggleTheme()" class="theme-toggle-btn" title="Basculer le thème" type="button">
                        <i class="fas fa-moon theme-icon"></i>
                    </button>
                </li>

                <!-- Notifications -->
                <li class="nav-item d-flex align-items-center me-2">
                    <x-notification-bell />
                </li>
                
                <!-- Utilisateur -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" 
                       role="button" data-bs-toggle="dropdown" aria-expanded="false"
                       style="color: var(--text-secondary);">
                        <i class="fas fa-user-circle me-2"></i>
                        <span class="d-none d-lg-inline">{{ Auth::user()->name ?? 'Compte' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 200px; background: var(--bg-surface); border-color: var(--border-color);">
                        <li>
                            <span class="dropdown-item-text" style="color: var(--text-muted);">
                                <i class="fas fa-user me-2"></i>
                                {{ Auth::user()->email ?? 'Email' }}
                            </span>
                        </li>
                        <li><hr class="dropdown-divider" style="border-color: var(--border-color);"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}" style="color: var(--text-primary);">
                                <i class="fas fa-tachometer-alt me-2" style="color: var(--accent);"></i>
                                Dashboard
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('stock-movements.index') }}" style="color: var(--text-primary);">
                                <i class="fas fa-exchange-alt me-2" style="color: var(--accent);"></i>
                                Mouvements de stock
                            </a>
                        </li>
                        <li><hr class="dropdown-divider" style="border-color: var(--border-color);"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" style="color: var(--color-danger);">
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
/* Styles spécifiques pour le toggle button mobile */
.navbar-toggler {
    border: 2px solid var(--accent) !important;
    border-radius: 10px !important;
    padding: 8px 12px !important;
    background: transparent !important;
    transition: all 0.3s ease !important;
}

.navbar-toggler:hover {
    background: var(--accent-light) !important;
    transform: scale(1.05);
}

.navbar-toggler:focus {
    box-shadow: 0 0 0 0.2rem var(--accent-ring) !important;
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
    color: var(--accent) !important;
    font-size: 24px !important;
    transition: all 0.3s ease !important;
}

/* Style pour mobile */
@media (max-width: 991.98px) {
    .navbar-collapse {
        background: var(--bg-surface);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 20px;
        margin-top: 15px;
        border: 1px solid var(--border-color);
        max-height: 80vh;
        overflow-y: auto;
    }
    
    .nav-link {
        text-align: center;
        margin: 5px 0 !important;
        padding: 10px !important;
    }
    
    .dropdown-menu {
        position: absolute !important;
        left: auto !important;
        right: 0 !important;
    }
}

/* Style pour les dropdowns */
.dropdown-menu {
    background-color: var(--bg-surface) !important;
    backdrop-filter: blur(10px);
    border: 1px solid var(--border-color) !important;
    border-radius: 15px !important;
    padding: 8px !important;
    box-shadow: var(--shadow-xl) !important;
}

.dropdown-item {
    color: var(--text-primary) !important;
    transition: all 0.2s ease;
    border-radius: 10px !important;
    padding: 10px 15px !important;
}

.dropdown-item:hover {
    background: var(--accent-light) !important;
    transform: translateX(5px);
}

.nav-link.active {
    color: var(--accent) !important;
    font-weight: 700;
}

.nav-link:hover {
    color: var(--text-primary) !important;
}
</style>

<script>
// ========== INITIALISATION DES TOOLTIPS ET TOGGLE ==========
document.addEventListener('DOMContentLoaded', function() {
    // Tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Animation du toggle button mobile
    var toggler = document.querySelector('.navbar-toggler');
    if (toggler) {
        toggler.addEventListener('click', function() {
            var icon = this.querySelector('i');
            if (icon.classList.contains('fa-bars')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }

    // Empêcher la propagation des clics sur les dropdowns
    document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
});
</script>