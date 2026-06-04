<div style="min-height:100dvh; width:100%; background:#080810; color:#e8e8f0; position:relative; overflow:hidden; display:flex; flex-direction:column; font-family:system-ui, sans-serif;">
    <x-petabit.bg/>

    <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:24px; padding:0 32px; position:relative; z-index:10; text-align:center;">
        <div class="pb-animate-float-pet">
            @if ($petGenome)
                <x-petabit.pet :genome="$petGenome" :size="95"/>
            @else
                <x-petabit.base-pet :size="95" color="rgba(232,232,240,0.7)"/>
            @endif
        </div>

        <div style="display:flex; flex-direction:column; align-items:center; gap:10px;">
            <div style="display:flex; gap:7px;">
                @for ($i = 0; $i < 3; $i++)
                    <div class="pb-animate-dot-pulse" style="width:8px; height:8px; border-radius:50%; background:rgba(255,255,255,0.38); animation-delay:{{ $i * 0.25 }}s;"></div>
                @endfor
            </div>
            <p style="font-size:12px; color:rgba(255,255,255,0.28); letter-spacing:0.08em;">{{ __('messages.analyzing.reading') }}</p>
        </div>

        {{-- Gradual reveal of the mutations the server returned --}}
        <div style="display:flex; flex-direction:column; gap:8px; width:100%; max-width:320px;">
            @forelse ($changes as $i => $change)
                <div nb-animation="slideFadeInUp" nb-animation-delay="{{ $leadMs + $i * $perChangeMs }}ms"
                    style="opacity:0; animation-fill-mode:forwards; padding:11px 14px; border-radius:12px; background:rgba(251,191,36,0.06); border:1px solid rgba(251,191,36,0.22); display:flex; align-items:center; gap:9px;">
                    <span style="color:#fbbf24; font-size:14px;">✦</span>
                    <span style="font-size:13px; color:#fde68a; font-weight:600;">{{ \App\Support\GenomeLabel::change($change) }}</span>
                </div>
            @empty
                <div nb-animation="fadeIn" nb-animation-delay="{{ $leadMs }}ms" style="opacity:0; animation-fill-mode:forwards;">
                    <p style="font-size:13px; color:rgba(255,255,255,0.4); font-style:italic;">{{ __('messages.analyzing.no_change') }}</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Auto-advance to the result once the reveal finishes (NB router, no reload). --}}
    <button id="pb-auto-advance" wire:nb-navigate.replace="/result" style="display:none;" aria-hidden="true"></button>
    @script
        <script>
            setTimeout(() => document.getElementById('pb-auto-advance')?.click(), @js($totalMs));
        </script>
    @endscript
</div>
