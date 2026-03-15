<!-- resources/views/categories/index.blade.php -->
@extends('layouts.app')

@section('title', 'Catégories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-down">
    <div>
        <h1 class="text-gradient mb-2">
            <i class="fas fa-tags me-2"></i>
            Catégories
        </h1>
        <p class="text-white-50">Gérez vos catégories de produits</p>
    </div>
    <a href="{{ route('categories.create') }}" class="btn-premium">
        <i class="fas fa-plus me-2"></i> Nouvelle Catégorie
    </a>
</div>

<div class="row g-4">
    @forelse($categories as $category)
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
            <div class="premium-card">
                <div class="card-header-premium">
                    <h5 class="mb-0">
                        <i class="fas fa-folder me-2"></i>
                        {{ $category->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-white-50 mb-3">
                        {{ $category->description ?? 'Aucune description' }}
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge" style="background: linear-gradient(135deg, #00f3ff 0%, #00c8ff 100%);">
                            <i class="fas fa-box me-1"></i>
                            {{ $category->products_count ?? $category->products->count() }} produits
                        </span>
                        <div>
                            <a href="{{ route('categories.edit', $category) }}" 
                               class="btn-premium btn-sm me-2"
                               data-bs-toggle="tooltip" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-premium btn-sm"
                                        onclick="return confirm('Supprimer cette catégorie ?')"
                                        data-bs-toggle="tooltip" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="premium-card text-center p-5" data-aos="fade-up">
                <i class="fas fa-folder-open fa-4x text-gradient mb-3"></i>
                <h4 class="text-white">Aucune catégorie</h4>
                <p class="text-white-50 mb-4">Commencez par créer votre première catégorie</p>
                <a href="{{ route('categories.create') }}" class="btn-premium">
                    <i class="fas fa-plus me-2"></i> Créer une catégorie
                </a>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-5">
    {{ $categories->links() }}
</div>
@endsection