@props([
    'disabled' => false,
    'error' => false,
    'icon' => null,
])

<div class="relative group">
    @if($icon)
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-text-muted opacity-50 group-focus-within:text-brand-primary group-focus-within:opacity-100 transition-all duration-300">
            <i class="{{ $icon }} text-xs"></i>
        </div>
    @endif

    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => '
        w-full rounded-xl border text-sm font-bold transition-all duration-300
        ' . ($icon ? 'pl-11' : 'pl-5') . '
        pr-5 py-3 bg-card
        focus:ring-2 focus:ring-offset-0 focus:outline-none
        ' . ($error 
            ? 'border-rose-300 text-rose-500 placeholder-rose-200 focus:ring-rose-500/10 focus:border-rose-500' 
            : 'border-border-subtle text-text-main placeholder-text-muted/40 focus:ring-brand-primary/10 focus:border-brand-primary h-12'
        ) . '
        disabled:bg-sidebar-active/50 disabled:text-text-muted/50 disabled:cursor-not-allowed
    ']) !!}>
</div>
