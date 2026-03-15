<!-- resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Stoki</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #5C2018 0%, #BC4639 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(212, 165, 154, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 80% 70%, rgba(212, 165, 154, 0.1) 0%, transparent 20%),
                repeating-linear-gradient(45deg, rgba(243, 224, 220, 0.03) 0px, rgba(243, 224, 220, 0.03) 2px, transparent 2px, transparent 10px);
            z-index: 1;
        }

        .auth-card {
            background: rgba(243, 224, 220, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(212, 165, 154, 0.3);
            border-radius: 30px;
            padding: 50px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 500px;
            position: relative;
            z-index: 2;
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .auth-header i {
            font-size: 70px;
            background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }

        .auth-header h2 {
            color: #F3E0DC;
            font-weight: 700;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .auth-header p {
            color: #D4A59A;
            font-size: 16px;
        }

        .form-label {
            color: #F3E0DC;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control {
            background: rgba(243, 224, 220, 0.1);
            border: 1px solid rgba(212, 165, 154, 0.3);
            border-radius: 50px;
            padding: 14px 20px;
            color: #F3E0DC;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4285f4;
            box-shadow: 0 0 0 0.2rem rgba(66, 133, 244, 0.25);
            background: rgba(243, 224, 220, 0.15);
        }

        .form-control::placeholder {
            color: #D4A59A;
            opacity: 0.7;
        }

        .btn-auth {
            background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);
            color: #F3E0DC;
            border: none;
            padding: 14px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }

        .btn-auth::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-auth:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(66, 133, 244, 0.4);
        }

        .auth-footer {
            text-align: center;
            margin-top: 30px;
        }

        .auth-footer p {
            color: #D4A59A;
        }

        .auth-footer a {
            color: #4285f4;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .auth-footer a:hover {
            color: #BC4639;
            text-decoration: underline;
        }

        .alert {
            background: rgba(92, 32, 24, 0.3);
            border: 1px solid #BC4639;
            border-radius: 50px;
            color: #F3E0DC;
            padding: 12px 20px;
            margin-bottom: 20px;
        }

        .alert i {
            color: #BC4639;
        }

        small {
            color: #D4A59A;
            font-size: 12px;
        }

        /* Particules animées */
        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(212, 165, 154, 0.3);
            border-radius: 50%;
            z-index: 1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) translateX(0); }
            25% { transform: translateY(-30px) translateX(15px); }
            50% { transform: translateY(-50px) translateX(-15px); }
            75% { transform: translateY(-20px) translateX(20px); }
        }
    </style>
</head>
<body>
    <div id="particles"></div>
    
    <div class="auth-card">
        <div class="auth-header">
            <i class="fas fa-cubes"></i>
            <h2>Stoki</h2>
            <p>Créez votre compte</p>
        </div>
        
        @if($errors->any())
            <div class="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-4">
                <label class="form-label">Nom complet</label>
                <input type="text" name="name" class="form-control" 
                       value="{{ old('name') }}" placeholder="Jean Dupont" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" 
                       value="{{ old('email') }}" placeholder="exemple@email.com" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" 
                       placeholder="********" required>
                <small>Minimum 8 caractères</small>
            </div>
            <div class="mb-4">
                <label class="form-label">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" class="form-control" 
                       placeholder="********" required>
            </div>
            <button type="submit" class="btn-auth">
                <i class="fas fa-user-plus me-2"></i> S'inscrire
            </button>
        </form>
        
        <div class="auth-footer">
            <p class="mb-0">Déjà un compte ? 
                <a href="{{ route('login') }}">Se connecter</a>
            </p>
        </div>
    </div>

    <script>
        // Particules animées
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 30;

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
    </script>
</body>
</html>