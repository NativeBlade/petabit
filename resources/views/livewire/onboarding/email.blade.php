<div style="min-height:100dvh; width:100%; background:#080810; color:#e8e8f0; position:relative; overflow:hidden; display:flex; flex-direction:column; font-family:system-ui, sans-serif;">
    <x-petabit.bg/>

    <div style="flex:1; display:flex; flex-direction:column; position:relative; z-index:10;">
        <x-petabit.dots :step="2"/>

        <div style="padding:0 24px; flex:1; display:flex; flex-direction:column;">
            <button wire:nb-navigate="/nickname" nb-feedback
                style="position:absolute; top:44px; left:8px; background:none; border:none; color:rgba(255,255,255,0.4); cursor:pointer;">
                <x-nativeblade-icon name="caret-left" size="22"/>
            </button>

            <h1 style="font-family:'Cinzel',serif; font-size:27px; color:#fff; margin-bottom:6px;">{{ __('messages.email.title') }}</h1>
            <p style="color:rgba(255,255,255,0.38); font-size:14px; margin-bottom:28px;">{{ __('messages.email.subtitle') }}</p>

            <input type="email" wire:model.blur="email" placeholder="{{ __('messages.email.placeholder') }}"
                style="width:100%; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.1); border-radius:14px; padding:15px; color:#e8e8f0; font-size:16px; outline:none; box-sizing:border-box; font-family:system-ui;">
            <p style="font-size:11px; color:rgba(255,255,255,0.24); font-style:italic; margin-top:10px;">{{ __('messages.email.no_spam') }}</p>

            @if ($error)
                <x-nativeblade-animate in="shakeX" class="block">
                    <p style="color:#ef4444; font-size:12px; margin-top:12px;">{{ $error }}</p>
                </x-nativeblade-animate>
            @endif

            <div style="margin-top:auto; margin-bottom:40px; display:flex; flex-direction:column; gap:10px;">
                <x-petabit.btn wire:click="continue">{{ __('messages.email.continue') }}</x-petabit.btn>
            </div>
        </div>
    </div>
</div>
