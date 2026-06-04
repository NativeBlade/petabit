@props([
    'disabled' => false,
    'color' => null,   // optional accent background; null = solid white
    'target' => null,  // wire:loading target; auto-detected from wire:click/submit if omitted
])

@php
    $isDisabled = filter_var($disabled, FILTER_VALIDATE_BOOLEAN);

    $resolvedTarget = $target;
    if (! $resolvedTarget) {
        foreach ($attributes->getAttributes() as $key => $value) {
            if (str_starts_with($key, 'wire:click') || str_starts_with($key, 'wire:submit')) {
                $resolvedTarget = is_string($value) ? $value : null;
                break;
            }
        }
    }

    if ($isDisabled) {
        $bg = 'rgba(255,255,255,0.04)';
        $fg = 'rgba(255,255,255,0.22)';
        $border = '1px solid rgba(255,255,255,0.07)';
        $cursor = 'not-allowed';
    } else {
        $bg = $color ?: '#fff';
        $fg = $color ? '#fff' : '#080810';
        $border = 'none';
        $cursor = 'pointer';
    }
@endphp

@once
    <style>@keyframes pb-spin{to{transform:rotate(360deg)}}</style>
@endonce

<button {{ $attributes }} @disabled($isDisabled) nb-feedback
    @if ($resolvedTarget) wire:loading.attr="disabled" wire:target="{{ $resolvedTarget }}" @endif
    style="position:relative; width:100%; padding:15px; border-radius:14px; font-weight:700; font-size:13px; letter-spacing:0.12em; text-transform:uppercase; display:flex; align-items:center; justify-content:center; gap:8px; background:{{ $bg }}; color:{{ $fg }}; border:{{ $border }}; cursor:{{ $cursor }};">
    @if ($resolvedTarget)
        <span wire:loading.remove wire:target="{{ $resolvedTarget }}" style="display:contents">{{ $slot }}</span>
        <span wire:loading wire:target="{{ $resolvedTarget }}"
            style="width:16px; height:16px; border:2px solid currentColor; border-right-color:transparent; border-radius:50%; animation:pb-spin .6s linear infinite;"></span>
    @else
        {{ $slot }}
    @endif
</button>
