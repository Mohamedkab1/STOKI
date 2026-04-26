<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Stoki ERP - Application de gestion de stock professionnelle">
    <title>@yield('title', 'Stoki') - Gestion de Stock</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- CSS & JS (Vite / Tailwind) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Bootstrap 5 (legacy) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- CSS Personnalisé (Ordre important) -->
    <link rel="stylesheet" href="{{ asset('css/tailwind-overrides.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">

    @stack('styles')
    
    <!-- Détection immédiate du thème : ajoute .dark et data-theme AVANT le rendu -->
    <script src="{{ asset('js/theme.js') }}"></script>
</head>
<body class="app-body" x-data>

    <!-- Overlay Mobile -->
    <div id="sidebar-overlay" class="sidebar-overlay"></div>
    
    <!-- Sidebar -->
    <x-layout.sidebar />

    <!-- Main Content Area -->
    <div class="app-main">
        
        <!-- Top Navbar -->
        <x-layout.navbar />

        <!-- Page Content -->
        <main class="app-content">
            
            <div class="animate-in">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer style="background: var(--bg-page); border-top: 1px solid var(--border-color);" class="py-10 px-6 text-center mt-auto">
            <p style="color: var(--text-muted);" class="text-xs">
                &copy; {{ date('Y') }} <span style="color: var(--text-primary);" class="font-bold">Stoki </span>. Tous droits réservés.
            </p>
        </footer>
    </div>

    <!-- Conteneur pour les notifications toast Bootstrap 5 -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999; margin-top: 60px;">
        @php
            $alerts = ['success' => 'check-circle', 'error' => 'times-circle', 'warning' => 'exclamation-triangle', 'info' => 'info-circle'];
            $bgClasses = ['success' => 'success text-white', 'error' => 'danger text-white', 'warning' => 'warning text-dark', 'info' => 'info text-dark'];
        @endphp
        
        @foreach($alerts as $type => $icon)
            @if(session($type))
                <div class="toast align-items-center text-bg-{{ explode(' ', $bgClasses[$type])[0] }} border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="{{ $type === 'error' ? '5000' : '3500' }}">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center gap-2 {{ str_contains($bgClasses[$type], 'text-white') ? 'text-white' : 'text-dark' }}">
                            <i class="fas fa-{{ $icon }} fs-5"></i>
                            <span class="fw-medium font-inter">{{ session($type) }}</span>
                        </div>
                        <button type="button" class="btn-close {{ str_contains($bgClasses[$type], 'text-white') ? 'btn-close-white' : '' }} me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Setup Toast Auto-Show -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toastElList = [].slice.call(document.querySelectorAll('.toast'))
            var toastList = toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl, { autohide: true })
            });
            toastList.forEach(toast => toast.show());
        });
    </script>
    
    <!-- Base Logic -->
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>

    @stack('scripts')
</body>
</html>