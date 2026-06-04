@props(['step' => 1, 'total' => 3, 'back' => null])

{{-- Onboarding progress dots (filled up to $step). Pass `back` to render a
     back arrow on the same row, vertically centered with the bars. The svg is
     forced to display:block (inline svgs drop below a thin bar's baseline) and
     spacing uses margin, not flex gap, so the row never collapses. --}}
<div style="display:flex; align-items:center; padding:46px 24px 20px;">
    @if ($back)
        <button wire:nb-navigate="{{ $back }}" nb-feedback aria-label="Back"
            style="flex:0 0 auto; display:flex; align-items:center; justify-content:center; width:28px; height:28px; margin:0 10px 0 -8px; padding:0; line-height:0; background:none; border:none; color:rgba(255,255,255,0.45); cursor:pointer;">
            <x-nativeblade-icon name="caret-left" size="22" style="display:block;" />
        </button>
    @endif
    <div style="display:flex; gap:5px; flex:1; min-width:0;">
        @for ($i = 0; $i < (int) $total; $i++)
            <div style="height:4px; flex:1; border-radius:2px; transition:background 0.3s; background:{{ $i < (int) $step ? '#fbbf24' : 'rgba(255,255,255,0.08)' }};"></div>
        @endfor
    </div>
</div>
