<div style="min-height:100dvh; width:100%; background:#080810; color:#e8e8f0; position:relative; overflow:hidden; display:flex; flex-direction:column; font-family:system-ui, sans-serif;">
    <x-petabit.bg/>

    <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:space-between; padding:48px 24px 40px; position:relative; z-index:10; text-align:center; overflow:hidden;">
        <div class="pb-animate-glow-burst" style="position:absolute; inset:0; background:radial-gradient(circle at 50% 38%, rgba(251,191,36,0.18) 0%, #080810 65%);"></div>

        <div style="position:relative; z-index:1; width:100%; display:flex; flex-direction:column; align-items:center; gap:18px;">
            <div style="padding:5px 16px; border-radius:999px; background:rgba(251,191,36,0.1); border:1px solid rgba(251,191,36,0.28); font-size:11px; font-weight:700; letter-spacing:0.14em; color:#fbbf24; text-transform:uppercase;">
                {{ __('messages.evolution.phase_from') }} <span style="color:rgba(255,255,255,0.28); margin:0 5px;">→</span> {{ __('messages.evolution.phase_to') }}
            </div>

            <div class="pb-animate-float-pet" style="position:relative; display:flex; align-items:center; justify-content:center;">
                <div style="position:absolute; width:200px; height:200px; border-radius:50%; background:rgba(251,191,36,0.14); filter:blur(26px);"></div>
                <svg viewBox="0 0 120 120" style="width:176px; height:176px; position:relative; z-index:1; filter:drop-shadow(0 0 22px rgba(251,191,36,0.42));">
                    <path d="M 40,30 Q 30,13 24,17" fill="none" stroke="#e8e8f0" stroke-width="3" stroke-linecap="round"/>
                    <circle cx="24" cy="17" r="3.5" fill="#fbbf24"/>
                    <path d="M 80,30 Q 90,13 96,17" fill="none" stroke="#e8e8f0" stroke-width="3" stroke-linecap="round"/>
                    <circle cx="96" cy="17" r="3.5" fill="#fbbf24"/>
                    <path d="M 24,75 C 20,38 100,38 96,75 C 92,102 28,102 24,75 Z" fill="#e8e8f0"/>
                    <circle cx="42" cy="60" r="5.5" fill="#080810"/><circle cx="43.5" cy="58.5" r="2" fill="#fff"/>
                    <circle cx="78" cy="60" r="5.5" fill="#080810"/><circle cx="79.5" cy="58.5" r="2" fill="#fff"/>
                    <path d="M 52,72 Q 60,80 68,72" fill="none" stroke="#080810" stroke-width="3" stroke-linecap="round"/>
                </svg>
            </div>

            <h1 style="font-family:'Cinzel',serif; font-size:34px; color:#fff;">{{ __('messages.evolution.title') }}</h1>
            <p style="color:rgba(255,255,255,0.5); line-height:1.65; max-width:290px;">{{ __('messages.evolution.body') }}</p>

            <div style="background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08); border-radius:13px; padding:13px 18px; width:100%;">
                <p style="font-size:11px; text-transform:uppercase; letter-spacing:0.1em; color:rgba(255,255,255,0.35); margin-bottom:5px;">{{ __('messages.evolution.summary_label') }}</p>
                <p style="font-size:13px; font-weight:600; color:#fbbf24;">{{ __('messages.evolution.summary') }}</p>
            </div>
        </div>

        <div style="width:100%; position:relative; z-index:1; margin-top:16px;">
            <x-petabit.btn wire:nb-navigate="/keep-habits">{{ __('messages.evolution.continue') }}</x-petabit.btn>
        </div>
    </div>
</div>
