@props([
    'size' => 100,
    'color' => '#e8e8f0',
])

{{-- The base (unaligned) Petabit blob. --}}
<svg viewBox="0 0 100 100" style="width:{{ $size }}px; height:{{ $size }}px;">
    <path d="M 20,52 C 20,24 80,24 80,52 C 80,80 20,80 20,52 Z" fill="{{ $color }}"/>
    <circle cx="36" cy="46" r="5" fill="#080810"/><circle cx="37.5" cy="44.5" r="2" fill="#fff"/>
    <circle cx="64" cy="46" r="5" fill="#080810"/><circle cx="65.5" cy="44.5" r="2" fill="#fff"/>
    <path d="M 44,62 Q 50,68 56,62" fill="none" stroke="#080810" stroke-width="2.5" stroke-linecap="round"/>
</svg>
