@props([
    'alignment' => null, // App\Enums\Alignment instance
    'size' => 140,
])

@php
    use App\Enums\Alignment;

    $color   = $alignment?->color() ?? '#e8e8f0';
    $isGood  = $alignment?->isGood() ?? false;
    $isEvil  = $alignment?->isEvil() ?? false;
    $neutral = $alignment === Alignment::Neutral;
    $h       = round($size * 0.92, 2);
@endphp

{{-- Petabit with alignment-specific accessories (halo+wings / horns / orb). --}}
<svg viewBox="0 0 130 120" style="width:{{ $size }}px; height:{{ $h }}px;">
    @if ($isGood)
        <path d="M 26,58 C 6,40 2,64 14,72" fill="none" stroke="{{ $color }}" stroke-width="3" stroke-linecap="round"/>
        <path d="M 104,58 C 124,40 128,64 116,72" fill="none" stroke="{{ $color }}" stroke-width="3" stroke-linecap="round"/>
        <circle cx="65" cy="14" r="5" fill="none" stroke="{{ $color }}" stroke-width="2"/>
    @endif
    @if ($isEvil)
        <path d="M 48,32 L 42,10 L 58,30 Z" fill="{{ $color }}"/>
        <path d="M 82,32 L 88,10 L 72,30 Z" fill="{{ $color }}"/>
    @endif
    @if ($neutral)
        <ellipse cx="65" cy="24" rx="5" ry="4.5" fill="{{ $color }}" opacity="0.9"/>
    @endif

    <path d="M 28,62 C 28,30 102,30 102,62 C 102,92 28,92 28,62 Z" fill="{{ $color }}"/>
    <circle cx="48" cy="55" r="5.5" fill="#080810"/><circle cx="49.5" cy="53.5" r="2" fill="#fff"/>
    <circle cx="82" cy="55" r="5.5" fill="#080810"/><circle cx="83.5" cy="53.5" r="2" fill="#fff"/>

    @if ($isGood)
        <path d="M 55,70 Q 65,78 75,70" fill="none" stroke="#080810" stroke-width="2.5" stroke-linecap="round"/>
    @elseif ($isEvil)
        <path d="M 55,74 Q 65,67 75,74" fill="none" stroke="#080810" stroke-width="2.5" stroke-linecap="round"/>
    @else
        <path d="M 55,72 L 75,72" fill="none" stroke="#080810" stroke-width="2.5" stroke-linecap="round"/>
    @endif
</svg>
