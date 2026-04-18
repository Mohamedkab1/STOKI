@props([
    'title' => null,
    'subtitle' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'premium-card overflow-hidden transition-colors duration-300']) }}>
    @if($title || isset($header))
        <div class="px-6 py-4 border-b border-border-subtle flex items-center justify-between">
            <div>
                @if($title)
                    <h3 class="text-sm font-black text-text-main uppercase tracking-tight">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-[10px] font-bold text-text-muted mt-1 uppercase tracking-wider opacity-60">{{ $subtitle }}</p>
                @endif
            </div>
            @if(isset($header))
                <div>{{ $header }}</div>
            @endif
        </div>
    @endif

    <div @class([
        'p-6' => $padding,
        'p-0' => !$padding,
    ])>
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-6 py-4 bg-sidebar-active/50 border-t border-border-subtle">
            {{ $footer }}
        </div>
    @endif
</div>
