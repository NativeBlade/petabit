<div style="min-height:100dvh; width:100%; background:#080810; color:#e8e8f0; position:relative; overflow:hidden; display:flex; flex-direction:column; font-family:system-ui, sans-serif;">
    <x-petabit.bg/>

    <div style="flex:1; display:flex; flex-direction:column; position:relative; z-index:10;">
        <x-petabit.dots :step="1"/>

        <div style="padding:0 24px; flex:1; display:flex; flex-direction:column;">
            <h1 style="font-family:'Cinzel',serif; font-size:27px; color:#fff; margin-bottom:6px;">{{ __('messages.nickname.title') }}</h1>
            <p style="color:rgba(255,255,255,0.38); font-size:14px; margin-bottom:32px;">{{ __('messages.nickname.subtitle') }}</p>

            <div style="position:relative;">
                <input type="text" wire:model.blur="nickname" placeholder="{{ __('messages.nickname.placeholder') }}"
                    style="width:100%; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.1); border-radius:14px; padding:15px; padding-right:{{ strlen($nickname) > 0 ? '118px' : '15px' }}; color:#e8e8f0; font-size:16px; outline:none; box-sizing:border-box; font-family:system-ui;">

                @if (strlen($nickname) > 0)
                    @php $ok = $this->nickOk && ! $taken; @endphp
                    <div style="position:absolute; right:14px; top:50%; transform:translateY(-50%); display:flex; align-items:center; gap:4px;">
                        @if ($ok)
                            <x-nativeblade-icon name="check" size="13" style="color:#10b981;"/>
                            <span style="color:#10b981; font-size:12px; font-weight:600;">{{ __('messages.nickname.available') }}</span>
                        @else
                            <x-nativeblade-icon name="x" size="13" style="color:#ef4444;"/>
                            <span style="color:#ef4444; font-size:12px; font-weight:600;">{{ __('messages.nickname.taken') }}</span>
                        @endif
                    </div>
                @endif
            </div>

            @if ($error)
                <x-nativeblade-animate in="shakeX" class="block">
                    <p style="color:#ef4444; font-size:12px; margin-top:12px;">{{ $error }}</p>
                </x-nativeblade-animate>
            @endif

            <div style="margin-top:auto; margin-bottom:40px;">
                <x-petabit.btn wire:click="continue">{{ __('messages.nickname.continue') }}</x-petabit.btn>
            </div>
        </div>
    </div>
</div>
