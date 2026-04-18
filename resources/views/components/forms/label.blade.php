@props([
    'for' => null,
    'required' => false
])

<label {{ $attributes->merge(['class' => 'block text-xs font-semibold text-text-muted mb-1.5 uppercase tracking-wider']) }} @if($for) for="{{ $for }}" @endif>
    {{ $slot }}
    @if($required)
        <span class="text-rose-500">*</span>
    @endif
</label>
