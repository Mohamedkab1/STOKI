{{-- 
    Composant Message d'Erreur de Formulaire (x-forms.error)
    Affiche le message d'erreur de validation sous un champ
    
    Props:
    - field: nom du champ (pour $errors->first())
--}}

@props(['field'])

@error($field)
    <div class="form-error">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ $message }}</span>
    </div>
@enderror
