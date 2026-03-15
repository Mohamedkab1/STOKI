<!-- resources/views/categories/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Modifier ' . $category->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-down">
    <div>
        <h1 class="text-gradient mb-2">
            <i class="fas fa-edit me-2"></i>
            Modifier la catégorie
        </h1>
        <p class="text-white-50">{{ $category->name }}</p>
    </div>
    <a href="{{ route('categories.index') }}" class="btn-premium">
        <i class="fas fa-arrow-left me-2"></i> Retour
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="premium-card" data-aos="fade-up">
            <div class="card-header-premium">
                <h5 class="mb-0">
                    <i class="fas fa-tag me-2"></i>
                    Informations de la catégorie
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="text-white-50 mb-2">Nom de la catégorie</label>
                        <input type="text" name="name" class="form-control bg-dark text-white border-0" 
                               value="{{ old('name', $category->name) }}" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="text-white-50 mb-2">Description</label>
                        <textarea name="description" class="form-control bg-dark text-white border-0" 
                                  rows="3">{{ old('description', $category->description) }}</textarea>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn-premium btn-premium-primary px-5 py-3">
                            <i class="fas fa-save me-2"></i>
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection