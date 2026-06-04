@php
    $a = $this->alignmentEnum();
    $petColor = $a->color();
@endphp

<div style="min-height:100dvh; width:100%; background:#080810; color:#e8e8f0; position:relative; overflow:hidden; display:flex; flex-direction:column; font-family:system-ui, sans-serif;">
    <x-petabit.bg/>

    <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:space-between; padding:40px 24px; position:relative; z-index:10; text-align:center;">
        <div style="display:flex; flex-direction:column; align-items:center; gap:16px; width:100%;">
            <div style="position:relative; display:flex; align-items:center; justify-content:center;">
                <div style="position:absolute; width:180px; height:180px; border-radius:50%; background:radial-gradient(circle, {{ $petColor }}1a 0%, transparent 70%); filter:blur(18px);"></div>
                <div class="pb-animate-float-pet" style="position:relative; z-index:1;">
                    @if ($pet && ($pet['genome'] ?? null))
                        <x-petabit.pet :genome="$pet['genome']" :size="172"/>
                    @else
                        <x-petabit.aligned-pet :alignment="$a" :size="148"/>
                    @endif
                </div>
            </div>

            <div style="display:flex; gap:7px; flex-wrap:wrap; justify-content:center;">
                <span style="display:inline-block; padding:5px 14px; border-radius:999px; border:1px solid {{ $petColor }}44; background:{{ $petColor }}12; color:{{ $petColor }}; font-size:11px; letter-spacing:0.12em; text-transform:uppercase; font-weight:700;">
                    {{ __('messages.result.alignment', ['label' => $a->label()]) }}
                </span>
                <span style="display:inline-block; padding:5px 14px; border-radius:999px; border:1px solid rgba(255,255,255,0.12); background:rgba(255,255,255,0.04); color:rgba(255,255,255,0.6); font-size:11px; letter-spacing:0.12em; text-transform:uppercase; font-weight:700;">
                    {{ __('messages.result.stage_label') }}: {{ __('data.stage.'.$stage) }}
                </span>
            </div>

            <h2 style="font-family:'Cinzel',serif; font-size:21px; color:rgba(255,255,255,0.9);">
                {{ $reborn ? __('messages.result.reborn_title') : __('messages.result.title') }}
            </h2>

            {{-- Mutations applied this reflection --}}
            <div style="width:100%; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.06); border-radius:14px; padding:14px 16px;">
                <p style="font-size:10px; font-weight:700; color:rgba(255,255,255,0.35); text-transform:uppercase; letter-spacing:0.12em; margin-bottom:10px;">{{ __('messages.result.changes_label') }}</p>
                @if (count($changes))
                    <div style="display:flex; flex-direction:column; gap:7px;">
                        @foreach ($changes as $change)
                            <div style="display:flex; align-items:center; gap:9px; text-align:left;">
                                <span style="color:{{ $petColor }}; font-size:13px;">✦</span>
                                <span style="font-size:13px; color:rgba(255,255,255,0.82); font-weight:600;">{{ \App\Support\GenomeLabel::change($change) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="font-size:13px; color:rgba(255,255,255,0.4); font-style:italic;">{{ __('messages.result.no_change') }}</p>
                @endif
            </div>
        </div>

        {{-- After every evolution the user reviews/edits the routine before going home. --}}
        <div style="width:100%; margin-top:18px;">
            <x-petabit.btn wire:nb-navigate.replace="/keep-habits" :color="$petColor">{{ __('messages.result.continue') }}</x-petabit.btn>
        </div>
    </div>
</div>
