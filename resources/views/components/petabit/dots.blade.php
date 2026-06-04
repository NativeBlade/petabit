@props(['step' => 1, 'total' => 3])

{{-- Onboarding progress dots (filled up to $step). --}}
<div style="display:flex; gap:5px; padding:46px 24px 20px;">
    @for ($i = 0; $i < (int) $total; $i++)
        <div style="height:4px; flex:1; border-radius:2px; transition:background 0.3s; background:{{ $i < (int) $step ? '#fbbf24' : 'rgba(255,255,255,0.08)' }};"></div>
    @endfor
</div>
