@extends('layouts.app')

@section('title', 'Nouvelle Catégorie')

@section('content')
<div class="space-y-8 animate-in">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-text-main">Ajouter une catégorie</h1>
            <p class="text-text-muted mt-1">Créez une nouvelle catégorie pour organiser vos produits.</p>
        </div>
        <x-ui.button href="{{ route('categories.index') }}" tag="a" variant="outline" icon="fas fa-arrow-left">
            Retour
        </x-ui.button>
    </div>

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            
            <x-ui.card title="Détails de la catégorie" subtitle="Informations d'identification">
                <div class="space-y-6">
                    <div>
                        <x-forms.label for="name" :required="true">Nom de la catégorie</x-forms.label>
                        <x-forms.input name="name" id="name" placeholder="Ex: Électronique, Boissons..." value="{{ old('name') }}" required />
                        @error('name') <p class="text-[10px] font-bold text-rose-500 mt-1 uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-forms.label for="description">Description (Optionnel)</x-forms.label>
                        <textarea name="description" id="description" rows="4" class="w-full rounded-lg border border-border-subtle bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-primary/10 focus:border-brand-primary focus:outline-none transition-all placeholder-slate-400" placeholder="Décrivez le type de produits regroupés ici...">{{ old('description') }}</textarea>
                        @error('description') <p class="text-[10px] font-bold text-rose-500 mt-1 uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-border-subtle">
                        <x-ui.button type="reset" variant="outline">
                            Réinitialiser
                        </x-ui.button>
                        <x-ui.button type="submit" icon="fas fa-save shadow-sm">
                            Enregistrer la catégorie
                        </x-ui.button>
                    </div>
                </div>
            </x-ui.card>
        </form>
    </div>
</div>
@endsection