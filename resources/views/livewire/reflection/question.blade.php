<div style="min-height:100dvh; width:100%; background:#080810; color:#e8e8f0; position:relative; overflow:hidden; display:flex; flex-direction:column; font-family:system-ui, sans-serif;">
    <x-petabit.bg/>

    <div style="flex:1; display:flex; flex-direction:column; position:relative; z-index:10; padding:0 24px 32px;">
        <div style="padding-top:48px; padding-bottom:16px; text-align:center;">
            <span style="display:inline-block; padding:4px 14px; border-radius:999px; border:1px solid rgba(245,158,11,0.3); background:rgba(245,158,11,0.07); color:rgba(253,230,138,0.8); font-size:10px; letter-spacing:0.18em; text-transform:uppercase;">{{ __('messages.question.badge') }}</span>
        </div>

        <div class="pb-animate-float-pet" style="display:flex; justify-content:center; margin-bottom:16px;">
            @if ($petGenome)
                <x-petabit.pet :genome="$petGenome" :size="85"/>
            @else
                <x-petabit.base-pet :size="85"/>
            @endif
        </div>

        <h2 style="font-family:'Cinzel',serif; font-size:21px; text-align:center; color:rgba(255,255,255,0.88); margin-bottom:8px;">{{ $question ?: __('messages.question.title') }}</h2>
        <p style="font-size:13px; color:rgba(255,255,255,0.3); text-align:center; margin-bottom:18px; line-height:1.55;">{{ __('messages.question.subtitle') }}</p>

        <div style="position:relative; flex:1; display:flex; flex-direction:column; margin-bottom:12px;">
            <textarea wire:model.live="answer" placeholder="{{ __('messages.question.placeholder') }}" maxlength="{{ $maxLength }}"
                style="flex:1; min-height:130px; max-height:190px; width:100%; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.1); border-radius:14px; padding:15px; color:#e8e8f0; font-size:15px; resize:none; outline:none; line-height:1.6; box-sizing:border-box; font-family:system-ui;"></textarea>
            <span style="position:absolute; bottom:12px; right:14px; font-size:11px; color:rgba(255,255,255,0.18);">{{ strlen($answer) }}/{{ $maxLength }}</span>
        </div>

        @if ($error)
            <x-nativeblade-animate in="shakeX" class="block">
                <p style="color:#ef4444; font-size:12px; text-align:center; margin-bottom:10px;">{{ $error }}</p>
            </x-nativeblade-animate>
        @endif

        @php $canSubmit = trim($answer) !== ''; @endphp
        <button wire:click="submit" wire:loading.attr="disabled" wire:target="submit" @disabled(! $canSubmit) nb-feedback
            style="width:100%; padding:15px; border-radius:14px; font-weight:700; font-size:13px; letter-spacing:0.1em; text-transform:uppercase; display:flex; align-items:center; justify-content:center; gap:8px; background:{{ $canSubmit ? 'rgba(255,255,255,0.09)' : 'rgba(255,255,255,0.02)' }}; border:1px solid {{ $canSubmit ? 'rgba(255,255,255,0.18)' : 'rgba(255,255,255,0.06)' }}; color:{{ $canSubmit ? '#fff' : 'rgba(255,255,255,0.18)' }}; cursor:{{ $canSubmit ? 'pointer' : 'not-allowed' }};">
            <span wire:loading.remove wire:target="submit" style="display:contents"><x-nativeblade-icon name="paper-plane-right" size="14"/> {{ __('messages.question.submit') }}</span>
            <x-petabit.spinner target="submit"/>
        </button>
    </div>
</div>
