@props(['active' => false, 'icon' => null])

<a {{ $attributes }} @class([
    'flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 group relative',
    'text-brand-primary bg-sidebar-active' => $active,
    'text-text-muted hover:text-text-main hover:bg-sidebar-active' => !$active,
])>
    @if($active)
        <div class="absolute inset-y-2 left-0 w-1 bg-brand-primary rounded-r-full shadow-lg shadow-brand-primary/40"></div>
    @endif
    
    @if($icon)
        <i class="{{ $icon }} {{ $active ? 'text-brand-primary scale-110' : 'text-text-muted opacity-60 group-hover:opacity-100 group-hover:scale-110' }} transition-all duration-300"></i>
    @endif
    
    <span class="tracking-tight">{{ $slot }}</span>

    @if(!$active)
        <div class="absolute inset-0 bg-brand-primary/0 group-active:bg-brand-primary/5 rounded-xl transition-colors"></div>
    @endif
</a>
