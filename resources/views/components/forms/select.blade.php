{{-- 
    Composant Select Personnalisé (x-forms.select)
    Dropdown avec flèche custom et même style que les inputs
    
    Props:
    - error: état d'erreur (boolean)
    - icon: icône optionnelle à gauche
--}}

@props([
    'disabled' => false,
    'error' => false,
])

<select 
    {{ $disabled ? 'disabled' : '' }} 
    {!! $attributes->merge(['class' => '
        stoki-select
        ' . ($error ? 'stoki-input--error' : '') . '
    ']) !!}
>
    {{ $slot }}
</select>
