<div style="min-height:100dvh; width:100%; background:#080810; color:#e8e8f0; position:relative; overflow:hidden; display:flex; flex-direction:column; font-family:system-ui, sans-serif;">
    <x-petabit.bg/>

    <div style="flex:1; display:flex; flex-direction:column; position:relative; z-index:10;">
        <x-petabit.dots :step="3"/>

        <div style="padding:0 24px; flex:1; display:flex; flex-direction:column;">
            <button wire:nb-navigate="/" nb-feedback
                style="position:absolute; top:44px; left:8px; background:none; border:none; color:rgba(255,255,255,0.4); cursor:pointer;">
                <x-nativeblade-icon name="caret-left" size="22"/>
            </button>

            <h1 style="font-family:'Cinzel',serif; font-size:27px; color:#fff; margin-bottom:6px;">{{ $isNew ? __('messages.verify.title_new') : __('messages.verify.title_existing') }}</h1>
            <p style="color:rgba(255,255,255,0.38); font-size:14px; margin-bottom:8px;">{{ __('messages.verify.sent_to') }}</p>
            <p style="color:rgba(255,255,255,0.75); font-size:14px; font-weight:600; margin-bottom:32px;">{{ $email ?: 'seu@email.com' }}</p>

            {{-- OTP boxes over a transparent input — digits render client-side (Alpine), so
                 typing is instant; the value syncs to the server only on submit (deferred). --}}
            <div x-data="{ code: $wire.entangle('code') }" style="position:relative; display:flex; gap:10px; justify-content:center; margin-bottom:14px;">
                <template x-for="i in 4" :key="i">
                    <div style="width:62px; height:72px; border-radius:14px; background:rgba(255,255,255,0.05); border:1.5px solid; display:flex; align-items:center; justify-content:center; font-size:28px; font-weight:700; color:#e8e8f0; font-family:monospace; transition:border-color 0.2s;"
                        :style="(code[i-1] ?? '') !== '' ? 'border-color:rgba(251,191,36,0.5)' : 'border-color:rgba(255,255,255,0.1)'">
                        <span x-text="code[i-1] ?? ''"></span>
                    </div>
                </template>
                <input type="tel" maxlength="4" inputmode="numeric" autocomplete="one-time-code" x-model="code"
                    @input="code = code.replace(/\D/g, '').slice(0, 4); if (code.length === 4) $wire.confirm()"
                    style="position:absolute; inset:0; opacity:0; font-size:1px; cursor:default;">
            </div>
            <p style="text-align:center; font-size:12px; color:rgba(255,255,255,0.25); margin-bottom:32px;">{{ __('messages.verify.hint') }}</p>

            <button style="background:none; border:none; color:rgba(255,255,255,0.4); font-size:13px; cursor:pointer; margin-bottom:auto;">{{ __('messages.verify.resend') }}</button>

            @if ($error)
                <x-nativeblade-animate in="shakeX" class="block">
                    <p style="color:#ef4444; font-size:12px; text-align:center; margin-bottom:14px;">{{ $error }}</p>
                </x-nativeblade-animate>
            @endif

            <div style="margin-bottom:40px;">
                <x-petabit.btn wire:click="confirm">{{ $isNew ? __('messages.verify.submit_new') : __('messages.verify.submit_existing') }}</x-petabit.btn>
            </div>
        </div>
    </div>
</div>
