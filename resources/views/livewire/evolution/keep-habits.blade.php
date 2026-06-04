<div style="min-height:100dvh; width:100%; background:#080810; color:#e8e8f0; position:relative; overflow:hidden; display:flex; flex-direction:column; font-family:system-ui, sans-serif;">
    <x-petabit.bg/>

    <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:space-between; padding:60px 24px 44px; position:relative; z-index:10; text-align:center;">
        <div style="display:flex; flex-direction:column; align-items:center; gap:20px;">
            <div class="pb-animate-float-pet">
                @if ($petGenome)
                    <x-petabit.pet :genome="$petGenome" :size="90"/>
                @else
                    <x-petabit.base-pet :size="90"/>
                @endif
            </div>
            <h1 style="font-family:'Cinzel',serif; font-size:24px; color:#fff;">{{ __('messages.keep.title') }}</h1>
            <p style="color:rgba(255,255,255,0.38); font-size:14px; line-height:1.65; max-width:290px;">
                {{ __('messages.keep.body') }}
            </p>

            <div style="background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08); border-radius:13px; padding:12px 18px; width:100%;">
                <p style="font-size:12px; color:rgba(255,255,255,0.4); margin-bottom:6px; text-transform:uppercase; letter-spacing:0.08em;">{{ __('messages.keep.active') }}</p>
                <div style="display:flex; flex-wrap:wrap; gap:6px; justify-content:center;">
                    @php $active = $this->activeHabits; @endphp
                    @foreach (array_slice($active, 0, 8) as $h)
                        <span style="padding:3px 10px; border-radius:999px; background:rgba(255,255,255,0.06); font-size:12px; color:rgba(255,255,255,0.6);">{{ $h['icon'] }} {{ \App\Support\HabitCatalog::label($h) }}</span>
                    @endforeach
                    @if (count($active) > 8)
                        <span style="padding:3px 10px; border-radius:999px; background:rgba(255,255,255,0.04); font-size:12px; color:rgba(255,255,255,0.35);">+{{ count($active) - 8 }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div style="width:100%; display:flex; flex-direction:column; gap:10px;">
            <x-petabit.btn wire:nb-navigate="/home">{{ __('messages.keep.keep') }}</x-petabit.btn>
            <button wire:nb-navigate="/setup" nb-feedback
                style="width:100%; padding:13px; border-radius:14px; background:transparent; color:rgba(255,255,255,0.4); font-weight:600; font-size:13px; letter-spacing:0.1em; text-transform:uppercase; border:none; cursor:pointer;">
                {{ __('messages.keep.edit') }}
            </button>
        </div>
    </div>
</div>
