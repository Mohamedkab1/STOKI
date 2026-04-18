@extends('layouts.app')

@section('title', 'Paramètres du Profil')

@section('content')
<div class="space-y-8 animate-in">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-text-main">Paramètres du Profil</h1>
            <p class="text-text-muted mt-1">Gérez la sécurité et les informations de votre compte utilisateur.</p>
        </div>
        <x-ui.button href="{{ route('profile.index') }}" tag="a" variant="outline" icon="fas fa-arrow-left">
            Retour
        </x-ui.button>
    </div>

    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Profile Update -->
        <x-ui.card title="Informations du compte" subtitle="Mettez à jour vos identifiants de connexion.">
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('patch')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-forms.label for="name" :required="true">Nom complet</x-forms.label>
                        <x-forms.input name="name" id="name" value="{{ old('name', $user->name) }}" required />
                        @error('name') <p class="text-[10px] font-bold text-rose-500 mt-1 uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-forms.label for="email" :required="true">Adresse Email</x-forms.label>
                        <x-forms.input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required />
                        @error('email') <p class="text-[10px] font-bold text-rose-500 mt-1 uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <x-ui.button type="submit" icon="fas fa-save shadow-sm">
                        Enregistrer
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>

        <!-- Account Deletion -->
        <x-ui.card title="Zone de danger" subtitle="Actions irréversibles sur votre compte." class="border-rose-100 bg-rose-50/10">
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-800 text-xs mb-6">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-triangle text-rose-600"></i>
                    <p class="font-bold uppercase tracking-tight">Suppression Définitive</p>
                </div>
                <p class="mt-1 opacity-80 leading-relaxed font-medium">Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Veuillez télécharger toutes les données que vous souhaitez conserver.</p>
            </div>

            <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?');" class="space-y-6">
                @csrf
                @method('delete')

                <div class="max-w-md">
                    <x-forms.label for="password" :required="true">Confirmez votre mot de passe pour continuer</x-forms.label>
                    <x-forms.input type="password" name="password" id="password" placeholder="••••••••" icon="fas fa-lock" required />
                    @error('password') <p class="text-[10px] font-bold text-rose-500 mt-1 uppercase">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-start">
                    <x-ui.button type="submit" variant="ghost" class="text-rose-600 hover:bg-rose-100 hover:text-rose-700">
                        <i class="fas fa-trash-alt mr-2 text-[10px]"></i> Supprimer le compte définitivement
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</div>
@endsection