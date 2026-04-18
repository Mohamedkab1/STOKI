<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stoki - Connexion Professionnelle</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Système de Thème -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <script src="{{ asset('js/theme.js') }}"></script>
</head>
<body class="bg-app text-main antialiased min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-[500px] h-[500px] bg-brand-primary/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-sky-500/5 rounded-full blur-3xl"></div>

    <div class="w-full max-w-md relative z-10 animate-in">
        <!-- Logo Area -->
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-brand-primary rounded-2xl flex items-center justify-center mx-auto shadow-xl shadow-brand-primary/20 mb-4">
                <i class="fas fa-cubes text-3xl text-white"></i>
            </div>
            <h1 class="text-3xl font-black tracking-tight text-text-main">STOKI<span class="text-brand-primary">ERP</span></h1>
            <p class="text-text-muted mt-2 text-sm font-medium">Système de gestion de stock professionnel</p>
        </div>

        <x-ui.card>
            <div class="text-center mb-8">
                <h2 class="text-xl font-bold text-text-main">Bienvenue</h2>
                <p class="text-xs text-text-muted mt-1 uppercase tracking-widest font-semibold">Accès Administrateur</p>
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

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <x-forms.label for="email" :required="true">Adresse Email</x-forms.label>
                    <x-forms.input 
                        type="email" 
                        name="email" 
                        id="email" 
                        placeholder="nom@entreprise.com" 
                        value="{{ old('email') }}" 
                        icon="fas fa-envelope"
                        required 
                        autofocus 
                    />
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <x-forms.label for="password" :required="true" class="mb-0">Mot de passe</x-forms.label>
                        <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-brand-primary hover:underline">Oublié ?</a>
                    </div>
                    <x-forms.input 
                        type="password" 
                        name="password" 
                        id="password" 
                        placeholder="••••••••" 
                        icon="fas fa-lock"
                        required 
                    />
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-border-subtle text-brand-primary focus:ring-brand-primary/20">
                    <label for="remember" class="ml-2 text-xs font-semibold text-text-muted">Rester connecté</label>
                </div>

                <div class="pt-2">
                    <x-ui.button type="submit" class="w-full py-3" icon="fas fa-sign-in-alt">
                        Se connecter
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>

        <div class="mt-10 text-center">
            <p class="text-[10px] text-text-muted font-bold uppercase tracking-[0.2em]">
                &copy; {{ date('Y') }} Stoki Management System • V.3.0 Premium Edition
            </p>
        </div>
    </div>

</body>
</html>