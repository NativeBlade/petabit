<div style="min-height:100dvh; width:100%; background:#080810; color:#e8e8f0; position:relative; overflow:hidden; display:flex; flex-direction:column; font-family:system-ui, sans-serif;">
    <x-petabit.bg/>

    <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:space-between; padding:56px 24px 44px; position:relative; z-index:10; text-align:center;">
        <x-nativeblade-image asset="logo.png" alt="Petabit" style="width:120px; height:auto; filter:drop-shadow(0 0 18px rgba(255,255,255,0.15));"/>

        <div style="display:flex; flex-direction:column; align-items:center; gap:22px;">
            <x-petabit.egg :w="118" :h="148" :aura="155" id="eggG2" stop="#bcbccc" seam="rgba(255,255,255,0.4)"/>

            <span style="padding:4px 14px; border-radius:999px; border:1px solid rgba(255,255,255,0.1); background:rgba(255,255,255,0.04); font-size:10px; letter-spacing:0.18em; color:rgba(255,255,255,0.45); text-transform:uppercase;">{{ __('messages.welcome.phase') }}</span>

            <div>
                <h1 style="font-family:'Cinzel',serif; font-size:25px; color:#fff; margin-bottom:8px;">{{ __('messages.welcome.title', ['name' => $nickname ?: __('messages.welcome.traveler')]) }}</h1>
                <p style="color:rgba(255,255,255,0.38); font-size:14px; line-height:1.6;">{!! __('messages.welcome.body') !!}</p>
            </div>
        </div>

        <x-petabit.btn wire:nb-navigate="/setup">{{ __('messages.welcome.cta') }}</x-petabit.btn>
    </div>
</div>
