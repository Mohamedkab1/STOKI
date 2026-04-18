<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stoki - Inscription Professionnelle</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-app text-main antialiased min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-[500px] h-[500px] bg-brand-primary/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-sky-500/5 rounded-full blur-3xl"></div>

    <div class="w-full max-w-lg relative z-10 animate-in">
        <!-- Logo Area -->
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-brand-primary rounded-2xl flex items-center justify-center mx-auto shadow-xl shadow-brand-primary/20 mb-4">
                <i class="fas fa-cubes text-2xl text-white"></i>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-text-main">STOKI<span class="text-brand-primary">ERP</span></h1>
        </div>

        <x-ui.card>
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-text-main">Créer un compte</h2>
                <p class="text-xs text-text-muted mt-1 uppercase tracking-widest font-semibold text-center">Rejoignez notre plateforme de gestion</p>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-800 text-xs">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <i class="fas fa-exclamation-circle text-rose-500"></i>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                
                <div>
                    <x-forms.label for="name" :required="true">Nom complet</x-forms.label>
                    <x-forms.input 
                        type="text" 
                        name="name" 
                        id="name" 
                        placeholder="Ex: Jean Dupont" 
                        value="{{ old('name') }}" 
                        icon="fas fa-user font-bold"
                        required 
                        autofocus 
                    />
                </div>

                <div>
                    <x-forms.label for="email" :required="true">Adresse Email</x-forms.label>
                    <x-forms.input 
                        type="email" 
                        name="email" 
                        id="email" 
                        placeholder="nom@entreprise.com" 
                        value="{{ old('email') }}" 
                        icon="fas fa-envelope font-bold"
                        required 
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-forms.label for="password" :required="true">Mot de passe</x-forms.label>
                        <x-forms.input 
                            type="password" 
                            name="password" 
                            id="password" 
                            placeholder="••••••••" 
                            icon="fas fa-lock font-bold"
                            required 
                        />
                    </div>
                    <div>
                        <x-forms.label for="password_confirmation" :required="true">Confirmer</x-forms.label>
                        <x-forms.input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            placeholder="••••••••" 
                            icon="fas fa-check font-bold"
                            required 
                        />
                    </div>
                </div>

                <div class="pt-4">
                    <x-ui.button type="submit" class="w-full py-3" icon="fas fa-user-plus shadow-sm">
                        Créer mon compte
                    </x-ui.button>
                </div>
            </form>

            <div class="text-center mt-6 pt-6 border-t border-border-subtle">
                <p class="text-xs text-text-muted font-semibold leading-relaxed">
                    Vous avez déjà un compte ? 
                    <a href="{{ route('login') }}" class="text-brand-primary hover:underline font-bold">Se connecter ici</a>
                </p>
            </div>
        </x-ui.card>

        <div class="mt-8 text-center">
            <p class="text-[10px] text-text-muted font-bold uppercase tracking-[0.2em]">
                &copy; {{ date('Y') }} Stoki Management System • V.3.0
            </p>
        </div>
    </div>

    <script>
        // Immediate Theme Detection
        (function() {
            const savedTheme = localStorage.getItem('stoki-theme');
            const theme = savedTheme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</body>
</html>