@extends('layouts.app')

@section('title', 'Créer un Administrateur')

@section('content')
<div class="max-w-2xl mx-auto space-y-8 animate-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">Nouvel Administrateur</h1>
            <p class="text-text-muted mt-1 font-medium italic opacity-80">Créer un compte administrateur manuellement.</p>
        </div>
        <a href="{{ route('superadmin.admins.index') }}" class="text-sm font-bold text-text-muted hover:text-text-main transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <x-ui.card>
        <form action="{{ route('superadmin.admins.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <x-forms.label for="name" :required="true">Nom complet</x-forms.label>
                <x-forms.input 
                    type="text" 
                    name="name" 
                    id="name" 
                    placeholder="Ex: Marc Lavoine" 
                    value="{{ old('name') }}" 
                    required 
                />
            </div>

            <div>
                <x-forms.label for="email" :required="true">Adresse Email</x-forms.label>
                <x-forms.input 
                    type="email" 
                    name="email" 
                    id="email" 
                    placeholder="admin@stoki.com" 
                    value="{{ old('email') }}" 
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
                        required 
                    />
                </div>
            </div>

            <div class="pt-4">
                <x-ui.button type="submit" class="w-full">Créer le compte</x-ui.button>
            </div>
        </form>
    </x-ui.card>
</div>
@endsection
