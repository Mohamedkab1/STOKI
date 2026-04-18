{{-- 
    Composant Carte Statistique (x-stats-card)
    Affiche un KPI avec icône, valeur, label et tendance
    
    Props:
    - icon: classe Font Awesome (ex: 'fas fa-boxes-stacked')
    - iconColor: variante de couleur (indigo, rose, emerald, sky)
    - label: texte descriptif du KPI
    - value: valeur numérique à afficher
    - badge: texte du badge
    - badgeVariant: variante du badge (neutral, danger, success, info)
    - trend: pourcentage de tendance (ex: '+5.2%')
    - trendDirection: 'up' ou 'down'
    - suffix: suffixe après la valeur (ex: 'MAD', '€')
--}}

@props([
    'icon' => 'fas fa-chart-bar',
    'iconColor' => 'indigo',
    'label' => 'Statistique',
    'value' => '0',
    'badge' => null,
    'badgeVariant' => 'neutral',
    'trend' => null,
    'trendDirection' => 'up',
    'suffix' => null,
    'valueClass' => '',
])

<div {{ $attributes->merge(['class' => 'stat-card']) }}>
    {{-- En-tête avec icône et badge --}}
    <div class="stat-card__header">
        <div class="stat-card__icon stat-card__icon--{{ $iconColor }}">
            <i class="{{ $icon }}"></i>
        </div>
        @if($badge)
            <span class="stat-card__badge stat-card__badge--{{ $badgeVariant }}">{{ $badge }}</span>
        @endif
    </div>

    {{-- Valeur et label --}}
    <div>
        <h3 class="stat-card__value {{ $valueClass }}">
            {{ $value }}
            @if($suffix)
                <span style="font-size: 12px; font-weight: 700; color: var(--text-muted); opacity: 0.6;">{{ $suffix }}</span>
            @endif
        </h3>
        <p class="stat-card__label">{{ $label }}</p>

        {{-- Indicateur de tendance --}}
        @if($trend)
            <span class="stat-card__trend stat-card__trend--{{ $trendDirection }}">
                <i class="fas fa-arrow-{{ $trendDirection }}"></i>
                {{ $trend }}
            </span>
        @endif
    </div>

    {{-- Décor d'arrière-plan --}}
    <div class="stat-card__decor">
        <i class="{{ $icon }}"></i>
    </div>

    {{-- Slot pour contenu additionnel --}}
    {{ $slot }}
</div>
