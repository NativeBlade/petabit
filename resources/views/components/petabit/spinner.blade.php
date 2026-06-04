@props([
    'target' => null,        // wire:loading target (method/expression)
    'size' => 14,
    'color' => 'currentColor',
])

@once
    <style>@keyframes pb-spin{to{transform:rotate(360deg)}}</style>
@endonce

<span {{ $attributes }} wire:loading.inline-flex @if ($target) wire:target="{{ $target }}" @endif
    style="display:none; width:{{ $size }}px; height:{{ $size }}px; border:2px solid {{ $color }}; border-right-color:transparent; border-radius:50%; animation:pb-spin .6s linear infinite;"></span>
