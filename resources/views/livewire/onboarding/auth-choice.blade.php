@use('App\Native\State\LocaleState')

<div style="min-height:100dvh; width:100%; background:#080810; color:#e8e8f0; position:relative; overflow:hidden; display:flex; flex-direction:column; font-family:system-ui, sans-serif;">
    <x-petabit.bg/>

    {{-- Language switcher --}}
    <div style="position:absolute; top:16px; right:16px; z-index:20; display:flex; gap:2px; padding:3px; border-radius:999px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); backdrop-filter:blur(8px);">
        @foreach ($locales as $loc)
            @php $isActive = $loc === $currentLocale; @endphp
            <button wire:click="changeLocale('{{ $loc }}')" nb-feedback
                style="padding:5px 11px; border-radius:999px; border:none; cursor:pointer; font-size:11px; font-weight:700; letter-spacing:0.06em; transition:all 0.15s; background:{{ $isActive ? 'rgba(251,191,36,0.16)' : 'transparent' }}; color:{{ $isActive ? '#fde68a' : 'rgba(255,255,255,0.4)' }};">
                {{ LocaleState::label($loc) }}
            </button>
        @endforeach
    </div>

    <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:space-between; padding:72px 28px 52px; position:relative; z-index:10;">
        <div></div>

        <div style="display:flex; flex-direction:column; align-items:center; gap:28px; text-align:center;">
            <div style="display:flex; flex-direction:column; align-items:center; gap:10px;">
                <x-nativeblade-image asset="logo.png" alt="Petabit" style="width:200px; height:auto; filter:drop-shadow(0 0 22px rgba(255,255,255,0.18));"/>
                <p style="font-size:11px; letter-spacing:0.24em; color:rgba(255,255,255,0.3); text-transform:uppercase; margin:0;">{{ __('messages.auth.tagline') }}</p>
            </div>

            <x-petabit.egg :w="124" :h="154" :aura="162" id="eggGrdL" stop="#c8c8d8" seam="rgba(255,255,255,0.45)"/>
        </div>

        <div style="width:100%; display:flex; flex-direction:column; gap:11px;">
            <x-petabit.btn wire:click="createAccount">{{ __('messages.auth.create') }}</x-petabit.btn>
            <button wire:click="existingAccount" wire:loading.attr="disabled" wire:target="existingAccount" nb-feedback
                style="width:100%; padding:15px; border-radius:14px; background:transparent; border:1px solid rgba(255,255,255,0.14); color:rgba(255,255,255,0.55); font-weight:700; font-size:13px; letter-spacing:0.12em; text-transform:uppercase; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                <span wire:loading.remove wire:target="existingAccount" style="display:contents">{{ __('messages.auth.existing') }}</span>
                <x-petabit.spinner target="existingAccount" color="rgba(255,255,255,0.7)"/>
            </button>
        </div>
    </div>
</div>
