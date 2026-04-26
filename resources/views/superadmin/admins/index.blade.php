@extends('layouts.app')

@section('title', 'Gestion des Administrateurs')

@section('content')
<div class="space-y-8 animate-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">Gestion des Administrateurs</h1>
            <p class="text-text-muted mt-1 font-medium italic opacity-80">Approuvez, gérez et modifiez les comptes administrateurs.</p>
        </div>
        <a href="{{ route('superadmin.admins.create') }}">
            <x-ui.button icon="fas fa-plus">Ajouter un Admin</x-ui.button>
        </a>
    </div>

    {{-- Barre de Statistiques --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-ui.card class="flex items-center gap-4 p-4 border-l-4 border-brand-primary">
            <div class="w-10 h-10 rounded-full bg-brand-primary/10 flex items-center justify-center text-brand-primary">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="text-[10px] uppercase font-bold text-text-muted">Total Admins</div>
                <div class="text-xl font-black text-text-main">{{ $stats['total'] }}</div>
            </div>
        </x-ui.card>
        <x-ui.card class="flex items-center gap-4 p-4 border-l-4 border-emerald-500">
            <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div class="text-[10px] uppercase font-bold text-text-muted">Actifs</div>
                <div class="text-xl font-black text-text-main">{{ $stats['active'] }}</div>
            </div>
        </x-ui.card>
        <x-ui.card class="flex items-center gap-4 p-4 border-l-4 border-amber-500">
            <div class="w-10 h-10 rounded-full bg-amber-500/10 flex items-center justify-center text-amber-500">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <div class="text-[10px] uppercase font-bold text-text-muted">En attente</div>
                <div class="text-xl font-black text-text-main">{{ $stats['pending'] }}</div>
            </div>
        </x-ui.card>
        <x-ui.card class="flex items-center gap-4 p-4 border-l-4 border-rose-500">
            <div class="w-10 h-10 rounded-full bg-rose-500/10 flex items-center justify-center text-rose-500">
                <i class="fas fa-times-circle"></i>
            </div>
            <div>
                <div class="text-[10px] uppercase font-bold text-text-muted">Rejetés</div>
                <div class="text-xl font-black text-text-main">{{ $stats['rejected'] }}</div>
            </div>
        </x-ui.card>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm flex items-center gap-3">
            <i class="fas fa-check-circle text-emerald-500"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="p-4 rounded-xl bg-blue-50 border border-blue-100 text-blue-800 text-sm flex items-center gap-3">
            <i class="fas fa-info-circle text-blue-500"></i>
            {{ session('info') }}
        </div>
    @endif

    {{-- Demandes en attente --}}
    <div class="space-y-4">
        <h2 class="text-lg font-bold text-text-main flex items-center gap-2">
            <i class="fas fa-clock text-amber-500"></i>
            Demandes en attente
            <span class="bg-amber-100 text-amber-700 text-[10px] px-2 py-0.5 rounded-full">{{ $pendingAdmins->count() }}</span>
        </h2>
        
        <x-ui.card class="p-0 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-app-subtle border-b border-border-subtle">
                    <tr>
                        <th class="px-6 py-4 text-[10px] uppercase tracking-wider font-bold text-text-muted">Nom</th>
                        <th class="px-6 py-4 text-[10px] uppercase tracking-wider font-bold text-text-muted">Email</th>
                        <th class="px-6 py-4 text-[10px] uppercase tracking-wider font-bold text-text-muted">Date demande</th>
                        <th class="px-6 py-4 text-[10px] uppercase tracking-wider font-bold text-text-muted text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-subtle">
                    @forelse($pendingAdmins as $admin)
                        <tr class="hover:bg-app-subtle/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-text-main">{{ $admin->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-text-muted">{{ $admin->email }}</td>
                            <td class="px-6 py-4 text-sm text-text-muted">{{ $admin->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('superadmin.admins.approve', $admin) }}" method="POST">
                                        @csrf
                                        <x-ui.button type="submit" size="xs" variant="success">Approuver</x-ui.button>
                                    </form>
                                    <form action="{{ route('superadmin.admins.reject', $admin) }}" method="POST">
                                        @csrf
                                        <x-ui.button type="submit" size="xs" variant="danger">Rejeter</x-ui.button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-text-muted italic text-sm">Aucune demande en attente.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.card>
    </div>

    {{-- Administrateurs Actifs --}}
    <div class="space-y-4">
        <h2 class="text-lg font-bold text-text-main flex items-center gap-2">
            <i class="fas fa-user-shield text-brand-primary"></i>
            Administrateurs Actifs
            <span class="bg-brand-primary/10 text-brand-primary text-[10px] px-2 py-0.5 rounded-full">{{ $activeAdmins->count() }}</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($activeAdmins as $admin)
                <x-ui.card class="relative group">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-full bg-brand-primary/10 flex items-center justify-center text-brand-primary font-bold text-xl">
                            {{ substr($admin->name, 0, 1) }}
                        </div>
                        <div class="flex gap-1 transition-opacity">
                            <a href="{{ route('superadmin.admins.edit', $admin) }}" class="p-2 bg-app-subtle rounded-lg text-text-muted hover:text-brand-primary transition-colors" title="Modifier">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <form action="{{ route('superadmin.admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Supprimer ce compte définitivement ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-app-subtle rounded-lg text-text-muted hover:text-rose-500 transition-colors" title="Supprimer">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-text-main">{{ $admin->name }}</h3>
                        <p class="text-sm text-text-muted">{{ $admin->email }}</p>
                        <div class="mt-4 pt-4 border-t border-border-subtle flex justify-between items-center">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-text-muted">Inscrit le</span>
                            <span class="text-xs text-text-main font-medium">{{ $admin->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </x-ui.card>
            @endforeach
        </div>
    </div>
</div>
@endsection
