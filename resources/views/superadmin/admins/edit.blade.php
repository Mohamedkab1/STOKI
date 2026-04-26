@extends('layouts.app')

@section('title', 'Modifier l\'Administrateur')

@section('content')
<div class="max-w-2xl mx-auto space-y-8 animate-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">Modifier : {{ $admin->name }}</h1>
            <p class="text-text-muted mt-1 font-medium italic opacity-80">Mise à jour des informations et du statut du compte.</p>
        </div>
        <a href="{{ route('superadmin.admins.index') }}" class="text-sm font-bold text-text-muted hover:text-text-main transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <x-ui.card>
        <form action="{{ route('superadmin.admins.update', $admin) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <x-forms.label for="name" :required="true">Nom complet</x-forms.label>
                <x-forms.input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $admin->name) }}" 
                    required 
                />
            </div>

            <div>
                <x-forms.label for="email" :required="true">Adresse Email</x-forms.label>
                <x-forms.input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email', $admin->email) }}" 
                    required 
                />
            </div>

            <div>
                <x-forms.label for="status" :required="true">Statut du compte</x-forms.label>
                <x-forms.select name="status" id="status" required>
                    <option value="active" {{ old('status', $admin->status) == 'active' ? 'selected' : '' }}>Actif (Autorisé)</option>
                    <option value="pending" {{ old('status', $admin->status) == 'pending' ? 'selected' : '' }}>En attente (Bloqué)</option>
                    <option value="rejected" {{ old('status', $admin->status) == 'rejected' ? 'selected' : '' }}>Rejeté (Bloqué)</option>
                </x-forms.select>
            </div>

            <div class="pt-4 border-t border-border-subtle">
                <h3 class="text-xs font-bold uppercase tracking-widest text-text-muted mb-4">Changer le mot de passe (optionnel)</h3>
                <div>
                    <x-forms.label for="password">Nouveau mot de passe</x-forms.label>
                    <x-forms.input 
                        type="password" 
                        name="password" 
                        id="password" 
                        placeholder="Laisser vide pour ne pas changer" 
                    />
                </div>
            </div>

            <div class="pt-4 flex gap-4">
                <x-ui.button type="submit" class="flex-1">Enregistrer les modifications</x-ui.button>
            </div>
        </form>
    </x-ui.card>
</div>
@endsection
