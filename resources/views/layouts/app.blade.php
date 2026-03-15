<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Stoki - @yield('title', 'Dashboard')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #5C2018 0%, #BC4639 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Overlay avec motif géométrique */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(212, 165, 154, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 80% 70%, rgba(212, 165, 154, 0.1) 0%, transparent 20%),
                repeating-linear-gradient(45deg, rgba(243, 224, 220, 0.03) 0px, rgba(243, 224, 220, 0.03) 2px, transparent 2px, transparent 10px);
            z-index: -1;
            pointer-events: none;
        }

        /* Effet de grain subtil */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMDAiIGhlaWdodD0iMzAwIj48ZmlsdGVyIGlkPSJmIj48ZmVUdXJidWxlbmNlIHR5cGU9ImZyYWN0YWxOb2lzZSIgYmFzZUZyZXF1ZW5jeT0iLjc0IiBudW1PY3RhdmVzPSIzIiAvPjwvZmlsdGVyPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbHRlcj0idXJsKCNmKSIgb3BhY2l0eT0iMC4wNSIgLz48L3N2Zz4=');
            opacity: 0.1;
            z-index: -1;
            pointer-events: none;
        }

        /* Particules animées */
        .particle {
            position: fixed;
            width: 3px;
            height: 3px;
            background: rgba(212, 165, 154, 0.3);
            border-radius: 50%;
            pointer-events: none;
            z-index: -1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) translateX(0); }
            25% { transform: translateY(-30px) translateX(15px); }
            50% { transform: translateY(-50px) translateX(-15px); }
            75% { transform: translateY(-20px) translateX(20px); }
        }

        /* Navbar avec vos couleurs */
        .navbar-premium {
            background: rgba(92, 32, 24, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(212, 165, 154, 0.3);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
            padding: 15px 0;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            color: #F3E0DC !important;
            letter-spacing: 1px;
            position: relative;
        }

        .navbar-brand::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #D4A59A, #4285f4, transparent);
        }

        .nav-link-premium {
            color: #F3E0DC !important;
            font-weight: 500;
            padding: 8px 20px !important;
            margin: 0 5px;
            border-radius: 50px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link-premium::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(66, 133, 244, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
            z-index: -1;
        }

        .nav-link-premium:hover::before {
            width: 200px;
            height: 200px;
        }

        .nav-link-premium:hover {
            color: #D4A59A !important;
            transform: translateY(-2px);
        }

        .nav-link-premium.active {
            background: rgba(66, 133, 244, 0.2);
            color: #4285f4 !important;
            border: 1px solid rgba(66, 133, 244, 0.3);
        }

        /* Cards premium avec vos couleurs */
        .premium-card {
            background: rgba(243, 224, 220, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(212, 165, 154, 0.2);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .premium-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(66, 133, 244, 0.1), transparent);
            transition: left 0.7s ease;
        }

        .premium-card:hover::before {
            left: 100%;
        }

        .premium-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(66, 133, 244, 0.2);
            border-color: rgba(66, 133, 244, 0.3);
        }

        .card-header-premium {
            background: rgba(92, 32, 24, 0.8);
            border-bottom: 1px solid rgba(212, 165, 154, 0.3);
            padding: 20px;
            color: #F3E0DC;
            font-weight: 600;
        }

        /* Stat cards */
        .stat-card-premium {
            background: rgba(243, 224, 220, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(212, 165, 154, 0.2);
            border-radius: 20px;
            padding: 25px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card-premium::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(66, 133, 244, 0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        .stat-card-premium:hover {
            transform: translateY(-5px);
            border-color: rgba(66, 133, 244, 0.3);
            box-shadow: 0 20px 40px rgba(66, 133, 244, 0.2);
        }

        .stat-icon-premium {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #F3E0DC;
            margin-bottom: 15px;
            box-shadow: 0 10px 20px rgba(66, 133, 244, 0.3);
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #F3E0DC;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #D4A59A;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Product cards */
        .product-card-premium {
            background: rgba(243, 224, 220, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(212, 165, 154, 0.2);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
        }

        .product-card-premium:hover {
            transform: translateY(-5px);
            border-color: rgba(66, 133, 244, 0.3);
            box-shadow: 0 20px 40px rgba(66, 133, 244, 0.2);
        }

        .product-image-premium {
            height: 200px;
            background: linear-gradient(135deg, #5C2018 0%, #BC4639 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .product-image-premium img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card-premium:hover .product-image-premium img {
            transform: scale(1.1);
        }

        .product-badge-premium {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            z-index: 1;
            background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);
            color: #F3E0DC;
            box-shadow: 0 5px 15px rgba(66, 133, 244, 0.3);
        }

        .product-info-premium {
            padding: 20px;
        }

        .product-title-premium {
            color: #F3E0DC;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .product-sku-premium {
            color: #D4A59A;
            font-size: 12px;
            margin-bottom: 15px;
        }

        .product-price-premium {
            color: #4285f4;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        /* Boutons avec vos couleurs */
        .btn-premium {
            background: transparent;
            border: 1px solid rgba(66, 133, 244, 0.5);
            color: #D4A59A;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-premium::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
            z-index: -1;
        }

        .btn-premium:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-premium:hover {
            color: #F3E0DC;
            border-color: transparent;
            transform: translateY(-2px);
        }

        .btn-premium-primary {
            background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);
            color: #F3E0DC;
            border: none;
        }

        .btn-premium-primary:hover {
            box-shadow: 0 10px 20px rgba(66, 133, 244, 0.3);
        }

        /* Tableaux */
        .table-premium {
            color: #F3E0DC;
        }

        .table-premium thead th {
            border-bottom: 2px solid #D4A59A;
            color: #D4A59A;
            font-weight: 600;
        }

        .table-premium tbody tr:hover {
            background: rgba(66, 133, 244, 0.1);
        }

        /* Footer */
        .footer-premium {
            background: rgba(92, 32, 24, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-top: 1px solid rgba(212, 165, 154, 0.3);
            padding: 30px 0;
            margin-top: 50px;
            color: #F3E0DC;
        }

        /* Alertes */
        .alert-premium {
            background: rgba(243, 224, 220, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(66, 133, 244, 0.3);
            border-radius: 15px;
            color: #F3E0DC;
        }

        /* Badges */
        .badge-primary {
            background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);
            color: #F3E0DC;
        }

        .badge-secondary {
            background: #D4A59A;
            color: #5C2018;
        }

        .badge-success {
            background: #4285f4;
            color: #F3E0DC;
        }

        .badge-warning {
            background: #BC4639;
            color: #F3E0DC;
        }

        .badge-danger {
            background: #5C2018;
            color: #F3E0DC;
        }

        /* Pagination avec vos couleurs */
        .pagination-custom {
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: 50px;
            margin: 20px 0;
            flex-wrap: wrap;
            justify-content: center;
            gap: 5px;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 10px 18px;
            background: rgba(243, 224, 220, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(212, 165, 154, 0.3);
            color: #D4A59A;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .page-link:hover {
            background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);
            color: #F3E0DC;
            border-color: transparent;
            transform: translateY(-2px);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);
            color: #F3E0DC;
            border-color: transparent;
            font-weight: 700;
        }

        .page-item.disabled .page-link {
            background: rgba(243, 224, 220, 0.05);
            color: #D4A59A;
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #5C2018;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #4285f4 0%, #BC4639 100%);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #BC4639 0%, #4285f4 100%);
        }

        /* Textes */
        h1, h2, h3, h4, h5, h6 {
            color: #F3E0DC;
        }

        .text-gradient {
            background: linear-gradient(135deg, #4285f4 0%, #D4A59A 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-white-50 {
            color: #D4A59A !important;
        }

        /* Formulaires */
        .form-control {
            background: rgba(243, 224, 220, 0.1) !important;
            border: 1px solid rgba(212, 165, 154, 0.3) !important;
            color: #F3E0DC !important;
            border-radius: 10px;
            padding: 12px;
        }

        .form-control:focus {
            border-color: #4285f4 !important;
            box-shadow: 0 0 0 0.2rem rgba(66, 133, 244, 0.25) !important;
        }

        .form-control::placeholder {
            color: #D4A59A !important;
            opacity: 0.7;
        }

        /* Animations */
        [data-aos] {
            pointer-events: none;
        }

        [data-aos].aos-animate {
            pointer-events: auto;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Particules animées -->
    <div id="particles"></div>

    <!-- Navigation -->
    @include('layouts.navigation')

    <main class="py-4">
        <div class="container-fluid px-4">
            <!-- Messages d'alerte -->
            @if(session('success'))
                <div class="alert alert-premium alert-dismissible fade show" role="alert" data-aos="fade-down">
                    <i class="fas fa-check-circle me-2" style="color: #4285f4;"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-premium alert-dismissible fade show" role="alert" data-aos="fade-down">
                    <i class="fas fa-exclamation-circle me-2" style="color: #BC4639;"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-premium alert-dismissible fade show" role="alert" data-aos="fade-down">
                    <i class="fas fa-exclamation-triangle me-2" style="color: #BC4639;"></i>
                    <strong>Erreur(s) :</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Contenu principal -->
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-premium">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="text-gradient mb-3">
                        <i class="fas fa-cubes me-2"></i>Stoki
                    </h5>
                    <p style="color: #D4A59A;">Gestion de stock intelligente</p>
                </div>
                <div class="col-md-4">
                    <h5 style="color: #F3E0DC;" class="mb-3">Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('dashboard') }}" style="color: #D4A59A; text-decoration: none;">Dashboard</a></li>
                        <li><a href="{{ route('products.index') }}" style="color: #D4A59A; text-decoration: none;">Produits</a></li>
                        <li><a href="{{ route('invoices.index') }}" style="color: #D4A59A; text-decoration: none;">Factures</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 style="color: #F3E0DC;" class="mb-3">Contact</h5>
                    <p style="color: #D4A59A;">
                        <i class="fas fa-envelope me-2"></i>contact@stoki.com<br>
                        <i class="fas fa-phone me-2"></i>+212 5XX XX XX XX
                    </p>
                </div>
            </div>
            <hr style="border-color: rgba(212, 165, 154, 0.3);">
            <p style="color: #D4A59A;" class="mb-0">
                &copy; {{ date('Y') }} Stoki. Tous droits réservés.
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialisation AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Particules animées
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 50;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                const posX = Math.random() * window.innerWidth;
                const posY = Math.random() * window.innerHeight;
                const duration = 15 + Math.random() * 20;
                const delay = Math.random() * 5;
                
                particle.style.cssText = `
                    left: ${posX}px;
                    top: ${posY}px;
                    animation: float ${duration}s infinite ease-in-out;
                    animation-delay: ${delay}s;
                `;
                
                particlesContainer.appendChild(particle);
            }
        }

        createParticles();

        // Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
    
    @stack('scripts')
</body>
</html>