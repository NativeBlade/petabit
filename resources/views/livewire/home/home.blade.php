@use('App\Enums\HomeTab')
@use('App\Enums\GeneKind')

@php
    $nick3 = strtoupper(substr($nickname, 0, 3)) ?: '???';
@endphp

<div style="height:100dvh; width:100%; background:#060608; color:#e8e8f0; position:relative; overflow:hidden; display:flex; flex-direction:column; font-family:system-ui, sans-serif;">
    {{-- Feed the device timezone to PHP (the WASM side is UTC and can't know it):
         persist it for the x-tz header, and schedule local habit reminders on open
         and whenever completion changes. --}}
    <div x-data x-init="
        const tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
        try { $wire.setDeviceTz(tz); } catch (e) {}
        const sync = () => { try { $wire.syncReminders(tz, Date.now()); } catch (e) {} };
        sync();
        $wire.on('pb-resync-reminders', sync);
    " style="display:none;"></div>

    <div style="flex:1; min-height:0; display:flex; flex-direction:column; position:relative; z-index:10;">

        {{-- ─ TAB: PETABIT ─ --}}
        @if ($tab === HomeTab::Petabit)
            <div style="padding:50px 22px 10px; display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <p style="font-size:13px; color:rgba(255,255,255,0.5); margin-bottom:3px;">{{ __('messages.home.greeting', ['name' => $nickname ?: __('messages.welcome.traveler')]) }}</p>
                    <h1 style="font-family:'Cinzel',serif; font-size:19px; color:rgba(255,255,255,0.9);">{{ __('messages.home.day', ['n' => $stageDay, 'total' => $stageDays]) }}</h1>
                </div>
                <div style="background:rgba(251,191,36,0.09); border:1px solid rgba(251,191,36,0.2); padding:5px 11px; border-radius:999px; display:flex; align-items:center; gap:5px;">
                    <span style="font-size:12px; font-weight:700; color:#fbbf24;">{{ __('messages.home.streak', ['count' => $streak]) }}</span>
                    <x-nativeblade-icon name="flame" size="13" style="color:#f59e0b;"/>
                </div>
            </div>

            <div style="display:flex; flex-direction:column; align-items:center; padding:6px 0 10px; position:relative;">
                <div style="position:absolute; width:200px; height:200px; border-radius:50%; background:rgba(251,191,36,0.04); filter:blur(28px); pointer-events:none;"></div>
                <div style="position:relative; width:185px; height:185px; display:flex; align-items:center; justify-content:center;">
                    <div class="pb-animate-float-pet" style="filter:drop-shadow(0 0 14px rgba(251,191,36,0.22));">
                        @if ($petGenome)
                            <x-petabit.pet :genome="$petGenome" :size="172"/>
                        @else
                            <svg viewBox="0 0 100 100" style="width:130px; height:130px;">
                                <path d="M 25,65 C 20,30 80,30 75,65 C 70,85 30,85 25,65 Z" fill="#e8e8f0"/>
                                <circle cx="38" cy="50" r="4.5" fill="#080810"/><circle cx="39.5" cy="48.5" r="1.5" fill="#fff"/>
                                <circle cx="62" cy="50" r="4.5" fill="#080810"/><circle cx="63.5" cy="48.5" r="1.5" fill="#fff"/>
                                <path d="M 46,58 Q 50,63 54,58" fill="none" stroke="#080810" stroke-width="2.5" stroke-linecap="round"/>
                            </svg>
                        @endif
                    </div>
                </div>
                @php
                    $hpPct = $hpMax > 0 ? round($hp / $hpMax * 100) : 0;
                    $hpColor = $hpPct <= 25 ? '#ef4444' : ($hpPct <= 50 ? '#f59e0b' : '#10b981');
                @endphp
                <div style="width:68%; margin-top:30px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:5px;">
                        <span style="font-size:9px; letter-spacing:0.12em; text-transform:uppercase; color:rgba(255,255,255,0.35);">{{ __('messages.home.hp') }}</span>
                        <span style="font-size:9px; font-weight:700; color:{{ $hpColor }};">{{ $hp }}/{{ $hpMax }}</span>
                    </div>
                    <div style="height:4px; background:rgba(255,255,255,0.07); border-radius:2px; overflow:hidden;">
                        <div style="height:100%; width:{{ $hpPct }}%; background:{{ $hpColor }}; border-radius:2px; box-shadow:0 0 7px {{ $hpColor }}; transition:width 0.5s ease;"></div>
                    </div>
                </div>
                <span style="margin-top:8px; padding:3px 12px; border-radius:999px; border:1px solid rgba(255,255,255,0.08); background:rgba(255,255,255,0.03); font-size:10px; letter-spacing:0.12em; text-transform:uppercase; color:rgba(255,255,255,0.38);">{{ __('messages.home.phase_badge', ['n' => $phaseNumber, 'stage' => __('data.stage.'.$stageName), 'days' => $daysToNext]) }}</span>
            </div>

            <div style="flex:1; min-height:0; background:rgba(0,0,0,0.42); border-radius:26px 26px 0 0; border-top:1px solid rgba(255,255,255,0.05); padding:18px 20px 140px; backdrop-filter:blur(12px); overflow-y:auto;">
                <h2 style="font-size:10px; font-weight:700; color:rgba(255,255,255,0.32); text-transform:uppercase; letter-spacing:0.14em; margin-bottom:12px;">{{ __('messages.home.today') }}</h2>
                @php $todayIso = now()->dayOfWeekIso; @endphp
                <div style="display:flex; flex-direction:column; gap:8px;">
                    @foreach ($this->activeHabits as $h)
                        @continue(! in_array($todayIso, $h['days'] ?? [1, 2, 3, 4, 5, 6, 7], true))
                        @php $checked = in_array($h['id'], $done, true); @endphp
                        <button wire:click="toggleDay({{ $h['id'] }})" wire:loading.attr="disabled" wire:target="toggleDay({{ $h['id'] }})" nb-feedback
                            style="display:flex; align-items:center; padding:13px 15px; border-radius:15px; cursor:pointer; transition:all 0.2s; text-align:left; border:1px solid {{ $checked ? 'rgba(16,185,129,0.28)' : 'rgba(255,255,255,0.07)' }}; background:{{ $checked ? 'rgba(16,185,129,0.07)' : 'rgba(255,255,255,0.025)' }};">
                            <div style="width:23px; height:23px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:13px; flex-shrink:0; transition:all 0.2s; border:1.5px solid {{ $checked ? '#10b981' : 'rgba(255,255,255,0.18)' }}; background:{{ $checked ? '#10b981' : 'transparent' }};">
                                @if ($checked)
                                    <span wire:loading.remove wire:target="toggleDay({{ $h['id'] }})" style="display:contents"><x-nativeblade-icon name="check" size="12" style="color:#080810;"/></span>
                                @endif
                                <x-petabit.spinner target="toggleDay({{ $h['id'] }})" :size="12" color="rgba(255,255,255,0.9)"/>
                            </div>
                            <span style="font-size:19px; margin-right:11px; opacity:{{ $checked ? '1' : '0.5' }};">{{ $h['icon'] }}</span>
                            <span style="font-weight:600; font-size:14px; color:{{ $checked ? '#e8e8f0' : 'rgba(255,255,255,0.55)' }};">{{ \App\Support\HabitCatalog::label($h) }}</span>
                        </button>
                    @endforeach
                </div>

                @php
                    $dueToday = array_values(array_filter($this->activeHabits, fn ($h) => in_array($todayIso, $h['days'] ?? [1, 2, 3, 4, 5, 6, 7], true)));
                    $doneCount = count(array_filter($dueToday, fn ($h) => in_array($h['id'], $done, true)));
                    $allDone = count($dueToday) > 0 && $doneCount === count($dueToday);
                @endphp
                @if (count($dueToday))
                    <div style="margin-top:16px; width:100%; padding:13px; border-radius:14px; text-align:center; font-weight:700; font-size:12px; letter-spacing:0.08em; text-transform:uppercase; transition:all 0.3s;
                        @if ($allDone) background:rgba(16,185,129,0.12); border:1px solid rgba(16,185,129,0.3); color:#34d399;
                        @else background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.07); color:rgba(255,255,255,0.4);
                        @endif">
                        {{ $allDone ? __('messages.home.day_done') : __('messages.home.progress', ['done' => $doneCount, 'total' => count($dueToday)]) }}
                    </div>
                @endif
            </div>
        @endif

        {{-- ─ TAB: GENOMA ─ --}}
        @if ($tab === HomeTab::Genoma)
            <div style="flex:1; min-height:0; display:flex; flex-direction:column; overflow-y:auto; padding-bottom:80px;">
                <div style="padding:50px 22px 16px;">
                    <h1 style="font-family:'Cinzel',serif; font-size:22px; color:#fff; margin-bottom:4px;">{{ __('messages.home.genome.title') }}</h1>
                    <p style="color:rgba(255,255,255,0.35); font-size:13px;">{{ __('messages.home.genome.subtitle') }}</p>
                </div>
                <div style="margin:0 20px 14px; padding:11px 14px; border-radius:12px; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08); display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:10px; color:rgba(255,255,255,0.3); text-transform:uppercase; letter-spacing:0.1em;">{{ __('messages.home.genome.seq_id') }}</span>
                    <span style="font-family:monospace; font-size:13px; color:rgba(255,255,255,0.7); font-weight:700; letter-spacing:0.12em;">{{ $petGenome['seed'] ?? '—' }}</span>
                </div>
                @php
                    $rarityColor = ['common' => '#9aa0aa', 'uncommon' => '#5e8fc4', 'rare' => '#a98fe0', 'legendary' => '#e0b54a', 'base' => '#64748b'];
                    // Which gene sections were inherited from a merge (latest provenance per section).
                    $mergeBySection = [];
                    foreach ($merges as $mm) { if (! empty($mm['section'])) $mergeBySection[$mm['section']] = $mm; }
                @endphp
                <div style="padding:0 20px; display:flex; flex-direction:column; gap:8px;">
                    @foreach ($petTraits as $i => $seg)
                        @php
                            $rar = $seg['rarity'] ?? 'base';
                            $c = $rarityColor[$rar] ?? '#64748b';
                            $dominant = in_array($rar, ['rare', 'legendary'], true);
                            $inh = $mergeBySection[$seg['part']] ?? null;
                            $ns = ($seg['part'] === 'pattern') ? 'data.pattern.' : 'data.gene_value.';
                            $gvKey = $ns.($seg['key'] ?? \Illuminate\Support\Str::slug($seg['value'], '_'));
                            $gvText = \Illuminate\Support\Facades\Lang::has($gvKey) ? __($gvKey) : ($seg['value'] ?? '');
                            if (! empty($seg['count'])) $gvText = $seg['count'].'× '.$gvText;
                        @endphp
                        <div @if ($inh) x-data="{ open: false }" @click="open = ! open" @endif
                            style="padding:13px 15px; border-radius:14px; background:rgba(255,255,255,0.03); border:1px solid {{ $inh ? 'rgba(168,85,247,0.3)' : ($dominant ? $c.'30' : 'rgba(255,255,255,0.07)') }};{{ $inh ? ' cursor:pointer;' : '' }}">
                            <div style="display:flex; align-items:center; gap:12px;">
                                @if ($dominant)
                                    <x-nativeblade-icon name="star-fill" size="13" style="color:{{ $c }}; flex-shrink:0;"/>
                                @else
                                    <div style="width:13px; flex-shrink:0;"></div>
                                @endif
                                <div style="flex:1;">
                                    <div style="display:flex; align-items:center; gap:7px; margin-bottom:3px;">
                                        <span style="font-size:13px; font-weight:700; color:rgba(255,255,255,0.82);">{{ __('data.gene_part.'.$seg['part']) }}</span>
                                        <span style="font-size:9px; padding:2px 7px; border-radius:999px; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; background:{{ $c.'18' }}; color:{{ $c }}; border:1px solid {{ $c.'35' }};">{{ __('data.rarity.'.$rar) }}</span>
                                        @if ($inh)
                                            <x-nativeblade-icon name="shuffle" size="11" style="color:#a855f7; flex-shrink:0;"/>
                                        @endif
                                    </div>
                                    <span style="font-size:12px; color:rgba(255,255,255,0.48);">{{ $gvText }}</span>
                                </div>
                                <span style="font-family:monospace; font-size:11px; color:rgba(255,255,255,0.25); font-weight:600;">#{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            @if ($inh)
                                <p x-show="open" style="display:none; font-size:11px; color:rgba(168,85,247,0.85); margin-top:7px; padding-left:25px;">{{ __('messages.home.merge.from_partner', ['name' => $inh['partner'] ?? '—']) }}</p>
                            @endif
                        </div>
                    @endforeach
                    <div style="padding:10px 14px; border-radius:12px; background:transparent; border:1px dashed rgba(255,255,255,0.08); text-align:center;">
                        <p style="font-size:11px; color:rgba(255,255,255,0.24); letter-spacing:0.06em;">{{ __('messages.home.genome.new_genes') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- ─ TAB: MESCLAR ─ --}}
        @if ($tab === HomeTab::Mesclar)
            <div style="flex:1; min-height:0; display:flex; flex-direction:column; overflow-y:auto; padding:50px 20px 90px; gap:18px;">
                <div style="text-align:center;">
                    <h1 style="font-family:'Cinzel',serif; font-size:22px; color:rgba(255,255,255,0.9);">{{ __('messages.home.merge.title') }}</h1>
                    <p style="color:rgba(255,255,255,0.4); font-size:13px; line-height:1.6; max-width:300px; margin:8px auto 0;">{{ __('messages.home.merge.subtitle') }}</p>
                </div>

                @if ($mergeMsg)
                    <div style="padding:10px 14px; border-radius:11px; background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.25); text-align:center;">
                        <p style="font-size:12px; color:#fca5a5;">{{ $mergeMsg }}</p>
                    </div>
                @endif

                @if ($mergeOk)
                    <div style="padding:11px 14px; border-radius:11px; background:rgba(168,85,247,0.1); border:1px solid rgba(168,85,247,0.3); text-align:center;">
                        <p style="font-size:12px; color:#d8b4fe; line-height:1.5;">{{ $mergeOk }}</p>
                    </div>
                @endif

                @if (! $canMerge)
                    @php $alreadyMerged = count($pendingMerges) > 0; @endphp
                    @if ($alreadyMerged)
                        {{-- DONE: already merged this life — only a rebirth unlocks another --}}
                        <div style="display:flex; flex-direction:column; align-items:center; text-align:center; gap:12px; padding:14px 0 4px;">
                            <div style="width:52px; height:52px; border-radius:50%; background:rgba(168,85,247,0.12); border:1px solid rgba(168,85,247,0.35); display:flex; align-items:center; justify-content:center;"><x-nativeblade-icon name="shuffle" size="22" style="color:#a855f7;"/></div>
                            <h2 style="font-family:'Cinzel',serif; font-size:19px; color:rgba(255,255,255,0.8);">{{ __('messages.home.merge.done_title') }}</h2>
                            <p style="color:rgba(255,255,255,0.4); font-size:13px; line-height:1.6; max-width:280px; margin:0 auto;">{{ __('messages.home.merge.done_body') }}</p>
                        </div>
                    @else
                        {{-- LOCKED: the pet is not Adult yet (or not alive) --}}
                        <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; gap:16px; padding:10px 0;">
                            <div style="position:relative; display:flex; align-items:center; justify-content:center;">
                                <div style="opacity:0.22; transform:scale(0.9) translateX(18px); filter:blur(1px);"><x-petabit.base-pet :size="80"/></div>
                                <div style="position:relative; z-index:10; width:48px; height:48px; border-radius:50%; background:rgba(8,8,16,0.92); border:1px solid rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center;"><x-nativeblade-icon name="lock" size="18" style="color:rgba(255,255,255,0.28);"/></div>
                                <div style="opacity:0.22; transform:scale(0.9) translateX(-18px); filter:blur(1px);"><x-petabit.base-pet :size="80"/></div>
                            </div>
                            <div>
                                <span style="display:inline-block; padding:4px 14px; border-radius:999px; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.09); font-size:10px; color:rgba(255,255,255,0.38); text-transform:uppercase; letter-spacing:0.15em; margin-bottom:12px;">{{ __('messages.home.merge.locked_badge') }}</span>
                                <h2 style="font-family:'Cinzel',serif; font-size:19px; color:rgba(255,255,255,0.7); margin-bottom:8px;">{{ __('messages.home.merge.locked_title') }}</h2>
                                <p style="color:rgba(255,255,255,0.35); font-size:13px; line-height:1.7; max-width:280px; margin:0 auto;">{!! __('messages.home.merge.locked_body') !!}</p>
                            </div>
                        </div>
                    @endif
                @elseif ($mergeQr)
                    {{-- OFFERING: show the QR for the partner to scan --}}
                    <div style="display:flex; flex-direction:column; align-items:center; gap:14px;">
                        <p style="font-size:14px; font-weight:700; color:rgba(255,255,255,0.82);">{{ __('messages.home.merge.qr_title') }}</p>
                        <div style="background:#fff; border-radius:16px; padding:14px; line-height:0;">{!! $mergeQr !!}</div>
                        <p style="font-size:12px; color:rgba(255,255,255,0.4); line-height:1.6; max-width:280px; text-align:center;">{{ __('messages.home.merge.qr_body') }}</p>

                        {{-- The same code as text, for whoever would rather type it than scan --}}
                        @if ($mergeToken)
                            <div style="width:100%; max-width:300px; display:flex; flex-direction:column; align-items:center; gap:6px;">
                                <p style="font-size:10px; color:rgba(255,255,255,0.3); text-transform:uppercase; letter-spacing:0.1em;">{{ __('messages.home.merge.code_or_type') }}</p>
                                <div style="width:100%; display:flex; gap:8px; align-items:stretch;">
                                    <div style="flex:1; min-width:0; padding:11px 13px; border-radius:11px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.12); font-family:monospace; font-size:13px; color:#d8b4fe; text-align:center; word-break:break-all; user-select:all; -webkit-user-select:all; display:flex; align-items:center; justify-content:center;">{{ $mergeToken }}</div>
                                    <button wire:click="copyMergeCode" nb-feedback aria-label="{{ __('messages.home.merge.copy') }}"
                                        style="flex-shrink:0; padding:0 14px; border-radius:11px; background:rgba(168,85,247,0.18); border:1px solid rgba(168,85,247,0.35); color:#d8b4fe; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                                        <x-nativeblade-icon name="copy" size="16"/>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <button wire:click="$set('mergeQr', '')" style="font-size:12px; color:rgba(255,255,255,0.45); background:transparent; border:none; cursor:pointer; text-transform:uppercase; letter-spacing:0.08em; font-weight:700;">{{ __('messages.home.merge.cancel') }}</button>
                    </div>
                @else
                    {{-- HUB: generate / scan / manual code --}}
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        <x-petabit.btn wire:click="generateMerge"><x-nativeblade-icon name="qr-code" size="16"/> {{ __('messages.home.merge.generate_qr') }}</x-petabit.btn>
                        <button wire:click="scanMerge" nb-feedback style="width:100%; padding:14px; border-radius:14px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); color:rgba(255,255,255,0.72); font-weight:700; font-size:13px; letter-spacing:0.1em; text-transform:uppercase; display:flex; align-items:center; justify-content:center; gap:9px; cursor:pointer;"><x-nativeblade-icon name="scan" size="16"/> {{ __('messages.home.merge.read_qr') }}</button>

                        <div style="margin-top:8px; display:flex; flex-direction:column; gap:8px;">
                            <p style="font-size:10px; color:rgba(255,255,255,0.3); text-align:center; text-transform:uppercase; letter-spacing:0.1em;">{{ __('messages.home.merge.code_label') }}</p>
                            <form wire:submit="submitMergeCode" style="display:flex; gap:8px;">
                                <input type="text" wire:model="mergeCode" autocapitalize="off" autocomplete="off"
                                    style="flex:1; min-width:0; padding:11px 13px; border-radius:11px; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.1); color:#e8e8f0; font-size:13px;" />
                                <button type="submit" style="padding:11px 16px; border-radius:11px; background:rgba(168,85,247,0.18); border:1px solid rgba(168,85,247,0.35); color:#d8b4fe; font-weight:700; font-size:12px; cursor:pointer; white-space:nowrap;">{{ __('messages.home.merge.code_submit') }}</button>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Queued inheritances — realized on the next rebirth --}}
                @if (count($pendingMerges))
                    <div style="margin-top:6px;">
                        <p style="font-size:10px; font-weight:700; color:rgba(251,191,36,0.6); text-transform:uppercase; letter-spacing:0.12em; margin-bottom:9px;">{{ __('messages.home.merge.pending_title') }}</p>
                        <div style="display:flex; flex-direction:column; gap:7px;">
                            @foreach ($pendingMerges as $p)
                                <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; padding:10px 13px; border-radius:11px; background:rgba(251,191,36,0.05); border:1px solid rgba(251,191,36,0.18);">
                                    <div style="min-width:0;">
                                        <p style="font-size:13px; font-weight:600; color:rgba(255,255,255,0.78); margin-bottom:2px;">{{ \App\Support\GenomeLabel::sectionName($p['section'] ?? '') }}: <span style="color:#fde68a;">{{ \App\Support\GenomeLabel::sectionValue($p['section'] ?? '', $p['value_key'] ?? '') }}</span></p>
                                        <p style="font-size:11px; color:rgba(255,255,255,0.35);">{{ __('messages.home.merge.from_partner', ['name' => $p['partner'] ?? '—']) }}</p>
                                    </div>
                                    <span style="font-size:9px; color:rgba(251,191,36,0.7); font-weight:700; text-transform:uppercase; letter-spacing:0.06em; white-space:nowrap;">{{ __('messages.home.merge.on_rebirth') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Realized inherited traits now live on the Genoma tab (marked on each gene). --}}
            </div>
        @endif

        {{-- ─ TAB: CONTA ─ --}}
        @if ($tab === HomeTab::Conta)
            <div style="flex:1; min-height:0; display:flex; flex-direction:column; overflow-y:auto; padding:50px 20px 90px; gap:16px;">
                <h1 style="font-family:'Cinzel',serif; font-size:22px; color:rgba(255,255,255,0.9); text-align:center;">{{ __('messages.home.account.title') }}</h1>

                @php
                    $infoRow = 'display:flex; justify-content:space-between; align-items:center; gap:12px; padding:13px 15px; border-radius:13px; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.07);';
                    $infoLabel = 'font-size:11px; color:rgba(255,255,255,0.4); text-transform:uppercase; letter-spacing:0.08em; flex-shrink:0;';
                    $secBtn = 'width:100%; padding:13px; border-radius:13px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); color:rgba(255,255,255,0.72); font-weight:700; font-size:13px; display:flex; align-items:center; justify-content:center; gap:9px; cursor:pointer;';
                @endphp

                <div style="display:flex; flex-direction:column; gap:8px;">
                    <div style="{{ $infoRow }}">
                        <span style="{{ $infoLabel }}">{{ __('messages.home.account.username') }}</span>
                        <span style="font-size:14px; color:rgba(255,255,255,0.85); font-weight:600;">{{ $nickname ?: '—' }}</span>
                    </div>
                    <div style="{{ $infoRow }}">
                        <span style="{{ $infoLabel }}">{{ __('messages.home.account.email') }}</span>
                        <span style="font-size:13px; color:rgba(255,255,255,0.75); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $email ?: '—' }}</span>
                    </div>
                    <div style="{{ $infoRow }}">
                        <span style="{{ $infoLabel }}">{{ __('messages.home.account.version') }}</span>
                        <span style="font-size:13px; color:rgba(255,255,255,0.6); font-family:monospace;">{{ $appVersion ?: '—' }}</span>
                    </div>
                </div>

                <div style="display:flex; flex-direction:column; gap:10px;">
                    <button wire:click="openSupport" nb-feedback style="{{ $secBtn }}"><x-nativeblade-icon name="lifebuoy" size="16"/> {{ __('messages.home.account.support') }}</button>
                    <button wire:click="confirmLogout" nb-feedback style="{{ $secBtn }}"><x-nativeblade-icon name="sign-out" size="16"/> {{ __('messages.home.account.logout') }}</button>
                </div>

                {{-- Danger zone: delete account (two-step confirm). x-show sits on the
                     wrappers, never on the flex button, so it doesn't clobber its layout. --}}
                <div x-data="{ confirm: false }" style="margin-top:auto;">
                    <div x-show="! confirm">
                        <button @click="confirm = true" type="button" nb-feedback
                            style="width:100%; padding:13px; border-radius:13px; background:transparent; border:1px solid rgba(239,68,68,0.3); color:#fca5a5; font-weight:700; font-size:13px; display:flex; align-items:center; justify-content:center; gap:9px; cursor:pointer;">
                            <x-nativeblade-icon name="trash" size="16"/> {{ __('messages.home.account.delete') }}
                        </button>
                    </div>
                    <div x-show="confirm" style="display:none; padding:14px; border-radius:13px; background:rgba(239,68,68,0.07); border:1px solid rgba(239,68,68,0.25);">
                        <p style="font-size:13px; color:#fca5a5; line-height:1.55; margin-bottom:12px; text-align:center;">{{ __('messages.home.account.delete_warning') }}</p>
                        <div style="display:flex; gap:8px;">
                            <button @click="confirm = false" type="button" style="flex:1; padding:12px; border-radius:11px; background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12); color:rgba(255,255,255,0.7); font-weight:700; font-size:13px; cursor:pointer;">{{ __('messages.home.account.cancel') }}</button>
                            <button wire:click="requestDeletion" type="button" style="flex:1; padding:12px; border-radius:11px; background:rgba(239,68,68,0.85); border:none; color:#fff; font-weight:700; font-size:13px; cursor:pointer;">{{ __('messages.home.account.delete_confirm') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ─ TAB BAR ─ --}}
        <div style="position:absolute; bottom:0; width:100%; background:rgba(6,6,10,0.94); backdrop-filter:blur(20px); border-top:1px solid rgba(255,255,255,0.06); padding-bottom:26px; padding-top:12px; display:flex; justify-content:space-around; z-index:50;">
            @foreach (HomeTab::cases() as $case)
                @php $isActive = $tab === $case; @endphp
                <button wire:click="switchTab('{{ $case->value }}')" wire:loading.attr="disabled" wire:target="switchTab('{{ $case->value }}')" nb-feedback
                    style="display:flex; flex-direction:column; align-items:center; gap:4px; background:transparent; border:none; cursor:pointer; padding:0; color:{{ $isActive ? '#fbbf24' : 'rgba(255,255,255,0.28)' }};">
                    @if ($case === HomeTab::Petabit)
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 4 C6,4 4,10 4,14 C4,19 7,21 12,21 C17,21 20,19 20,14 C20,10 18,4 12,4 Z"/><circle cx="9" cy="13" r="1.5" fill="currentColor" stroke="none"/><circle cx="15" cy="13" r="1.5" fill="currentColor" stroke="none"/></svg>
                    @elseif ($case === HomeTab::Genoma)
                        <x-nativeblade-icon name="dna" size="22"/>
                    @elseif ($case === HomeTab::Mesclar)
                        <x-nativeblade-icon name="shuffle" size="22"/>
                    @else
                        <x-nativeblade-icon name="user" size="22"/>
                    @endif
                    <span style="font-size:10px; font-weight:700; letter-spacing:0.04em;">{{ $case->label() }}</span>
                </button>
            @endforeach
        </div>
    </div>

    {{-- Death / cooldown overlay --}}
    @if ($petDead)
        <div style="position:absolute; inset:0; z-index:80; background:rgba(6,6,10,0.92); backdrop-filter:blur(6px); display:flex; flex-direction:column; align-items:center; justify-content:center; gap:14px; padding:0 32px; text-align:center;">
            <div>
                @if ($petGenome)
                    <x-petabit.pet :genome="$petGenome" :size="120" :dead="true"/>
                @else
                    <div style="opacity:0.45; filter:grayscale(0.7);"><x-petabit.base-pet :size="90"/></div>
                @endif
            </div>
            <h2 style="font-family:'Cinzel',serif; font-size:22px; color:rgba(255,255,255,0.85);">{{ __('messages.home.dead_title') }}</h2>
            <p style="color:rgba(255,255,255,0.45); font-size:14px; line-height:1.6; max-width:280px;">{{ __('messages.home.dead_body') }}</p>
        </div>
    @endif

    {{-- Just reborn → confirm/edit the routine for the new life before the home. --}}
    @if ($reborn && ! $petDead)
        <button id="pb-reborn" wire:nb-navigate.replace="/keep-habits" style="display:none;" aria-hidden="true"></button>
        @script
            <script>
                setTimeout(() => document.getElementById('pb-reborn')?.click(), 350);
            </script>
        @endscript
    {{-- Evolution due → go answer the reflection, then the server evolves the pet. --}}
    @elseif ($evolutionDue && ! $petDead)
        <button id="pb-evolution" wire:nb-navigate.replace="/question" style="display:none;" aria-hidden="true"></button>
        @script
            <script>
                setTimeout(() => document.getElementById('pb-evolution')?.click(), 350);
            </script>
        @endscript
    @endif
</div>
