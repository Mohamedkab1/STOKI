@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'tag' => 'button'
])

@php
    $baseClasses = "inline-flex items-center justify-center font-bold rounded-xl transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-offset-0 active:scale-[0.98] gap-2.5";
    
    $variants = [
        'primary' => 'bg-brand-primary text-white hover:bg-brand-primary-hover shadow-premium-sm shadow-brand-primary/20 focus:ring-brand-primary/10 border border-brand-primary/20',
        'secondary' => 'bg-text-main text-card hover:opacity-90 shadow-premium-sm focus:ring-text-main/10',
        'outline' => 'bg-transparent border border-border-subtle text-text-main hover:bg-sidebar-active hover:border-brand-primary/30 focus:ring-brand-primary/5',
        'ghost' => 'bg-transparent text-text-muted hover:bg-sidebar-active hover:text-text-main focus:ring-brand-primary/5',
        'danger' => 'bg-rose-500 text-white hover:bg-rose-600 shadow-premium-sm shadow-rose-500/20 focus:ring-rose-500/10 border border-rose-600/10',
        'success' => 'bg-emerald-500 text-white hover:bg-emerald-600 shadow-premium-sm shadow-emerald-500/20 focus:ring-emerald-500/10 border border-emerald-600/10',
    ];
    
    $sizes = [
        'xs' => 'px-2.5 py-1.5 text-[10px] uppercase tracking-wider',
        'sm' => 'px-3.5 py-2 text-[11px] uppercase tracking-tight',
        'md' => 'px-5 py-3 text-xs uppercase tracking-tight',
        'lg' => 'px-8 py-4 text-sm uppercase tracking-widest',
        'icon' => 'p-3',
    ];
    
    $classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <i class="{{ $icon }} {{ $size === 'icon' ? 'text-sm' : 'text-xs opacity-70 group-hover:opacity-100' }} transition-all"></i>
    @endif
    
    <span class="truncate">{{ $slot }}</span>
</{{ $tag }}>
