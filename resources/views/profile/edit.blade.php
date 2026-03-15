<!-- resources/views/profile/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-bold mb-4">Mon Profil</h2>
                
                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success mb-4">
                        Profil mis à jour avec succès.
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Mettre à jour
                    </button>
                </form>

                <hr class="my-4">

                <h3 class="text-xl font-bold mb-4">Supprimer le compte</h3>
                <p class="mb-3 text-danger">
                    Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées.
                </p>

                <form method="POST" action="{{ route('profile.destroy') }}" 
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?');">
                    @csrf
                    @method('delete')

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-danger">
                        Supprimer le compte
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection