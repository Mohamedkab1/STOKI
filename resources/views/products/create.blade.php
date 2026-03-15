<!-- resources/views/products/create.blade.php -->
@extends('layouts.app')

@section('title', 'Nouveau Produit')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-down">
    <div>
        <h1 class="text-gradient mb-2">
            <i class="fas fa-plus-circle me-2"></i>
            Nouveau Produit
        </h1>
        <p class="text-white-50">Ajoutez un nouveau produit à votre inventaire</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn-premium">
        <i class="fas fa-arrow-left me-2"></i> Retour
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="premium-card" data-aos="fade-up">
            <div class="card-header-premium">
                <h5 class="mb-0">
                    <i class="fas fa-box me-2"></i>
                    Informations du produit
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- Nom -->
                        <div class="col-md-6">
                            <label class="text-white-50 mb-2">Nom du produit</label>
                            <input type="text" name="name" class="form-control bg-dark text-white border-0" 
                                   value="{{ old('name') }}" required>
                        </div>
                        
                        <!-- SKU -->
                        <div class="col-md-6">
                            <label class="text-white-50 mb-2">SKU</label>
                            <input type="text" name="sku" class="form-control bg-dark text-white border-0" 
                                   value="{{ old('sku') }}" required>
                        </div>
                        
                        <!-- Description -->
                        <div class="col-12">
                            <label class="text-white-50 mb-2">Description</label>
                            <textarea name="description" class="form-control bg-dark text-white border-0" 
                                      rows="3">{{ old('description') }}</textarea>
                        </div>
                        
                        <!-- Image -->
                        <div class="col-12">
                            <label class="text-white-50 mb-2">Image du produit</label>
                            <div class="preview-container mb-3 text-center">
                                <img id="imagePreview" src="#" alt="Aperçu" 
                                     style="max-height: 200px; display: none; border-radius: 10px; border: 2px solid #4285f4;">
                            </div>
                            <div class="input-group">
                                <input type="file" name="image" id="imageInput" 
                                       class="form-control bg-dark text-white border-0" 
                                       accept="image/jpeg,image/png,image/jpg,image/gif">
                                <button type="button" class="btn-premium" onclick="document.getElementById('imageInput').value=''; document.getElementById('imagePreview').style.display='none';">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <small class="text-white-50">Formats acceptés: JPEG, PNG, JPG, GIF (Max: 2 Mo)</small>
                        </div>
                        
                        <!-- Prix -->
                        <div class="col-md-4">
                            <label class="text-white-50 mb-2">Prix (€)</label>
                            <input type="number" step="0.01" name="price" class="form-control bg-dark text-white border-0" 
                                   value="{{ old('price') }}" required>
                        </div>
                        
                        <!-- Quantité initiale -->
                        <div class="col-md-4">
                            <label class="text-white-50 mb-2">Quantité initiale</label>
                            <input type="number" name="quantity" class="form-control bg-dark text-white border-0" 
                                   value="{{ old('quantity', 0) }}" required>
                        </div>
                        
                        <!-- Stock minimum -->
                        <div class="col-md-4">
                            <label class="text-white-50 mb-2">Stock minimum</label>
                            <input type="number" name="min_stock" class="form-control bg-dark text-white border-0" 
                                   value="{{ old('min_stock', 5) }}" required>
                        </div>
                        
                        <!-- Catégorie -->
                        <div class="col-md-6">
                            <label class="text-white-50 mb-2">Catégorie</label>
                            <select name="category_id" class="form-control bg-dark text-white border-0" required>
                                <option value="">Sélectionner</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Bouton -->
                        <div class="col-12 text-center">
                            <button type="submit" class="btn-premium btn-premium-primary px-5 py-3">
                                <i class="fas fa-save me-2"></i>
                                Créer le produit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Prévisualisation de l'image
document.getElementById('imageInput').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    const file = e.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});
</script>
@endpush
@endsection