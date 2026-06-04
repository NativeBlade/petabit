@props([
    'w' => 124,
    'h' => 154,
    'aura' => 162,
    'id' => 'eggGrd',
    'stop' => '#c8c8d8', // bottom gradient stop
    'seam' => 'rgba(255,255,255,0.45)',
])

{{-- Unhatched Petabit egg with floating + glowing aura. --}}
<div style="position:relative; display:flex; align-items:center; justify-content:center;">
    <div class="pb-animate-pulse-egg" style="position:absolute; width:{{ $aura }}px; height:{{ $aura }}px; border-radius:50%; background:radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);"></div>
    <svg viewBox="0 0 100 120" class="pb-animate-float-pet" style="width:{{ $w }}px; height:{{ $h }}px; position:relative; z-index:1;">
        <defs>
            <radialGradient id="{{ $id }}" cx="40%" cy="30%" r="60%">
                <stop offset="0%" stop-color="#f0f0f8"/>
                <stop offset="100%" stop-color="{{ $stop }}"/>
            </radialGradient>
        </defs>
        <path d="M 50,8 C 22,8 12,58 12,82 C 12,108 30,115 50,115 C 70,115 88,108 88,82 C 88,58 78,8 50,8 Z" fill="url(#{{ $id }})"/>
        <path d="M 50,8 C 22,8 12,58 12,82" fill="none" stroke="{{ $seam }}" stroke-width="1"/>
    </svg>
</div>
