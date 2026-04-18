@props(['active' => null])

<aside id="sidebar" class="app-sidebar custom-scrollbar">
    <!-- Brand -->
    <div class="h-20 flex items-center justify-between px-6 border-b border-border-subtle">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-brand-primary rounded-xl flex items-center justify-center shadow-lg shadow-brand-primary/10">
                <i class="fas fa-cubes text-xl text-white"></i>
            </div>
            <span class="text-xl font-black tracking-tight text-text-main">STOKI<span class="text-brand-primary">ERP</span></span>
        </div>
        <!-- Bouton fermé optionnel s'il est désiré sur mobile, géré par le JS -->
        <button id="sidebar-close" class="hamburger-btn" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100%-160px)]">
        <div class="px-4 py-2 text-[10px] font-bold text-text-muted uppercase tracking-widest opacity-60">Général</div>
        
        <x-layout.nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="fas fa-chart-line">
            Tableau de Bord
        </x-layout.nav-link>

        <div class="px-4 py-2 mt-4 text-[10px] font-bold text-text-muted uppercase tracking-widest opacity-60">Inventaire</div>
        
        <x-layout.nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.*')" icon="fas fa-box">
            Produits
        </x-layout.nav-link>

        <x-layout.nav-link href="{{ route('categories.index') }}" :active="request()->routeIs('categories.*')" icon="fas fa-tags">
            Catégories
        </x-layout.nav-link>

        <div class="px-4 py-2 mt-4 text-[10px] font-bold text-text-muted uppercase tracking-widest opacity-60">Opérations</div>

        <x-layout.nav-link href="{{ route('invoices.index') }}" icon="fas fa-file-invoice-dollar" :active="request()->routeIs('invoices.*')">
            Factures
        </x-layout.nav-link>

        <div class="px-4 py-2 mt-4 text-[10px] font-bold text-text-muted uppercase tracking-widest opacity-60">Système</div>

        <x-layout.nav-link href="{{ route('notifications.all') }}" icon="fas fa-bell" :active="request()->routeIs('notifications.*')">
            Notifications
            @php $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
            @if($unreadCount > 0)
                <span class="ml-auto bg-brand-primary text-white text-[9px] font-black px-2 py-0.5 rounded-full shadow-sm">{{ $unreadCount }}</span>
            @endif
        </x-layout.nav-link>

        <x-layout.nav-link href="{{ route('profile') }}" icon="fas fa-user-gear" :active="request()->routeIs('profile*')">
            Paramètres
        </x-layout.nav-link>
    </nav>

    <!-- App Info / Logout -->
    <div class="absolute bottom-0 left-0 w-full p-6 border-t border-border-subtle bg-card/50 backdrop-blur-sm">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center gap-3 text-text-muted hover:text-rose-500 transition-colors w-full text-sm font-bold">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </button>
        </form>
    </div>
</aside>
