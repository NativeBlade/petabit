@props([
    'genome' => null,
    'size' => 160,
    'aura' => true,
    'dead' => false,   // dims the pet (death state)
])

{{-- Rich genome pet. The genome JSON rides in data-genome; window.PetabitRenderer.mountAll()
     (bootstrapped in the layout) draws the SVG into .pet-holder and the aura into .pet-aura.
     wire:ignore keeps the JS-drawn content across Livewire morphs; wire:key (by genome) forces
     a fresh redraw only when the genome actually changes. --}}
@php
    $genomeJson = $genome ? json_encode($genome) : '';
    $isDead = filter_var($dead, FILTER_VALIDATE_BOOLEAN);
@endphp
<div data-petabit-pet wire:ignore wire:key="pet-{{ md5($genomeJson) }}" data-genome="{{ $genomeJson }}"
    style="position:relative; width:{{ $size }}px; height:{{ $size }}px;{{ $isDead ? ' opacity:0.45; filter:grayscale(0.7);' : '' }}">
    @if ($aura)
        <div class="pet-halo" style="position:absolute; inset:0; border-radius:20px; pointer-events:none; display:none;"></div>
        <div class="pet-aura" style="position:absolute; inset:0; pointer-events:none;"></div>
    @endif
    <div class="pet-holder" style="position:relative; z-index:2; width:100%; height:100%;"></div>
</div>
