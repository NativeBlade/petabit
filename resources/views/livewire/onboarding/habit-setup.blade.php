<div style="height:100dvh; width:100%; background:#080810; color:#e8e8f0; position:relative; overflow:hidden; display:flex; flex-direction:column; font-family:system-ui, sans-serif;">
    <x-petabit.bg/>

    <div style="flex:1; min-height:0; display:flex; flex-direction:column; position:relative; z-index:10;">
        {{-- Header --}}
        <div style="flex:none; padding:44px 24px 14px; background:rgba(0,0,0,0.55); backdrop-filter:blur(12px); border-bottom:1px solid rgba(255,255,255,0.06);">
            <h1 style="font-family:'Cinzel',serif; font-size:20px; color:#fff; margin-bottom:4px;">{{ __('messages.setup.title') }}</h1>
            <p style="color:rgba(255,255,255,0.35); font-size:13px;">{{ __('messages.setup.subtitle') }}</p>
        </div>

        {{-- Scrollable list --}}
        <div style="flex:1; overflow-y:auto; padding:14px 20px 120px; display:flex; flex-direction:column; gap:8px;">
            @foreach ($this->habits as $h)
                <div style="padding:13px 15px; border-radius:15px; transition:all 0.2s; border:1px solid {{ $h['active'] ? 'rgba(251,191,36,0.25)' : 'rgba(255,255,255,0.07)' }}; background:{{ $h['active'] ? 'rgba(251,191,36,0.04)' : 'rgba(255,255,255,0.015)' }};">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:{{ $h['active'] ? '10px' : '0' }};">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span style="font-size:21px;">{{ $h['icon'] }}</span>
                            <span style="font-weight:600; font-size:15px; color:{{ $h['active'] ? '#e8e8f0' : 'rgba(255,255,255,0.38)' }};">{{ \App\Support\HabitCatalog::label($h) }}</span>
                        </div>
                        <button wire:click="toggle({{ $h['id'] }})" nb-feedback
                            style="width:42px; height:23px; border-radius:12px; position:relative; border:none; cursor:pointer; flex-shrink:0; transition:background 0.2s; background:{{ $h['active'] ? '#fbbf24' : 'rgba(255,255,255,0.1)' }};">
                            <div style="position:absolute; top:2.5px; width:18px; height:18px; border-radius:50%; background:#fff; transition:left 0.2s; left:{{ $h['active'] ? '21px' : '2.5px' }};"></div>
                        </button>
                    </div>

                    @if ($h['active'])
                        <div style="display:flex; gap:4px; flex-wrap:wrap;">
                            @foreach ($weekdays as $iso)
                                @php $on = in_array($iso, $h['days'] ?? [], true); @endphp
                                <button wire:click="toggleWeekday({{ $h['id'] }}, {{ $iso }})"
                                    style="padding:5px 0; width:38px; text-align:center; border-radius:8px; font-size:10px; font-weight:700; cursor:pointer; transition:all 0.15s; background:{{ $on ? 'rgba(251,191,36,0.16)' : 'rgba(255,255,255,0.04)' }}; color:{{ $on ? '#fde68a' : 'rgba(255,255,255,0.3)' }}; border:1px solid {{ $on ? 'rgba(251,191,36,0.28)' : 'transparent' }};">
                                    {{ __('data.weekday_short.'.$iso) }}
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach

            {{-- Custom habit --}}
            @if (! $showForm)
                <button wire:click="openForm"
                    style="display:flex; align-items:center; justify-content:center; gap:7px; padding:13px; border-radius:15px; border:1px dashed rgba(255,255,255,0.13); background:transparent; color:rgba(255,255,255,0.38); font-size:14px; font-weight:600; cursor:pointer; margin-top:2px;">
                    <x-nativeblade-icon name="plus" size="15"/> {{ __('messages.setup.custom') }}
                </button>
            @else
                <div style="padding:15px; border-radius:15px; border:1px solid rgba(255,255,255,0.12); background:rgba(255,255,255,0.03); display:flex; flex-direction:column; gap:11px;">
                    <p style="font-size:11px; font-weight:700; color:rgba(255,255,255,0.5); text-transform:uppercase; letter-spacing:0.1em; margin:0;">{{ __('messages.setup.new_habit') }}</p>
                    <input wire:model="cName" placeholder="{{ __('messages.setup.name_placeholder') }}"
                        style="width:100%; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:11px 13px; color:#e8e8f0; font-size:15px; outline:none; box-sizing:border-box; font-family:system-ui;">
                    <div>
                        <p style="font-size:10px; color:rgba(255,255,255,0.28); margin-bottom:7px; text-transform:uppercase; letter-spacing:0.08em;">{{ __('messages.setup.icon') }}</p>
                        <div style="display:grid; grid-template-columns:repeat(5, 1fr); gap:7px;">
                            @foreach ($icons as $icon)
                                @php $sel = $cIcon === $icon; @endphp
                                <button wire:click="$set('cIcon', '{{ $icon }}')"
                                    style="padding:8px 0; border-radius:9px; font-size:20px; line-height:1; cursor:pointer; transition:all 0.15s; background:{{ $sel ? 'rgba(251,191,36,0.18)' : 'rgba(255,255,255,0.04)' }}; border:1px solid {{ $sel ? 'rgba(251,191,36,0.38)' : 'rgba(255,255,255,0.06)' }};">
                                    {{ $icon }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <div style="display:flex; gap:8px;">
                        @php $canAdd = trim($cName) !== ''; @endphp
                        <button wire:click="addHabit" wire:loading.attr="disabled" wire:target="addHabit" @disabled(! $canAdd)
                            style="flex:1; padding:10px; border-radius:10px; font-weight:700; font-size:13px; border:none; cursor:{{ $canAdd ? 'pointer' : 'not-allowed' }}; background:{{ $canAdd ? '#fff' : 'rgba(255,255,255,0.05)' }}; color:{{ $canAdd ? '#080810' : 'rgba(255,255,255,0.22)' }};">
                            <span wire:loading.remove wire:target="addHabit" style="display:contents">{{ __('messages.setup.add') }}</span>
                            <x-petabit.spinner target="addHabit" color="#080810"/>
                        </button>
                        <button wire:click="closeForm" wire:loading.attr="disabled" wire:target="closeForm"
                            style="flex:1; padding:10px; border-radius:10px; background:rgba(255,255,255,0.04); color:rgba(255,255,255,0.42); font-weight:600; font-size:13px; border:none; cursor:pointer;">{{ __('messages.setup.cancel') }}</button>
                    </div>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div style="flex:none; padding:10px 20px 28px; background:rgba(0,0,0,0.68); backdrop-filter:blur(16px); border-top:1px solid rgba(255,255,255,0.06);">
            @if ($error)
                <p style="color:#ef4444; font-size:12px; text-align:center; margin-bottom:8px;">{{ $error }}</p>
            @endif
            <p style="font-size:11px; color:rgba(255,255,255,0.3); text-align:center; margin-bottom:9px;">{{ trans_choice('messages.setup.selected', $this->activeCount, ['count' => $this->activeCount]) }}</p>
            <x-petabit.btn wire:click="confirm" :disabled="$this->activeCount === 0">{{ __('messages.setup.confirm') }}</x-petabit.btn>
        </div>
    </div>
</div>
