<?php

namespace App\Livewire\Home;

use App\Enums\HomeTab;
use App\Http\Clients\PetabitApiClient;
use App\Native\State\AuthState;
use App\Native\State\HabitsState;
use App\Native\State\OnboardingState;
use App\Native\State\PetState;
use App\Native\State\ReminderState;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use NativeBlade\Facades\NativeBlade;
use NativeBlade\Plugins\Notification;
use NativeBlade\Plugins\Scan;

#[Layout('components.layouts.app')]
class Home extends Component
{
    public HomeTab $tab = HomeTab::Petabit;

    /** Habit ids checked off for today (local UI state). */
    public array $done = [];

    // Lifecycle gate (set on app-open sync).
    public bool $evolutionDue = false;
    public bool $petDead = false;
    public bool $reborn = false;

    // Merge (Mesclar) UI state.
    public string $mergeQr = '';    // inline SVG of the pairing QR while offering
    public string $mergeToken = ''; // the readable code shown under the QR (type it instead of scanning)
    public string $mergeCode = '';  // manual token entry (fallback for the scanner)
    public string $mergeMsg = '';   // inline error message
    public string $mergeOk = '';    // inline success message (a merge was queued)

    /**
     * App-open sync: heal/damage from past days, rebirth after cooldown, decide
     * whether an evolution is due, and load the routine + today's ticked habits.
     */
    public function mount(PetabitApiClient $api): void
    {
        if (! AuthState::isAuthenticated()) {
            return;
        }

        try {
            $summary = $api->sync();
            PetState::set($summary['pet']);
            $this->evolutionDue = (bool) ($summary['evolution_due'] ?? false);
            $this->petDead = (bool) ($summary['pet']['dead'] ?? false);
            $this->reborn = (bool) ($summary['reborn'] ?? false);
            ReminderState::setLines($summary['reminder_lines'] ?? []);

            HabitsState::set($api->habits());
            // Restore today's checks (persisted server-side).
            $this->done = collect(HabitsState::all())
                ->filter(fn ($h) => $h['done_today'] ?? false)
                ->pluck('id')->all();
        } catch (\Throwable $e) {
            // Offline: fall back to the cached pet + routine.
        }
    }

    public function switchTab(string $value): void
    {
        $this->tab = HomeTab::from($value);
    }

    /** Tick / untick a habit for today — persisted so it survives reopens. */
    public function toggleDay(int $id, PetabitApiClient $api): void
    {
        $done = ! in_array($id, $this->done, true);
        $this->done = $done
            ? [...$this->done, $id]
            : array_values(array_diff($this->done, [$id]));

        try {
            $result = $api->checkHabit($id, $done);
            if (! empty($result['pet'])) {
                PetState::set($result['pet']);
            }
            if (isset($result['habits'])) {
                HabitsState::set($result['habits']);
            }
        } catch (\Throwable $e) {
            // Keep the optimistic local state when offline.
        }

        // Completion changed → re-evaluate today's reminders (the JS re-feeds tz/now).
        $this->dispatch('pb-resync-reminders');
    }

    /* ---- local habit reminders (scheduled client-side) ---- */

    /** Local hour-of-day for the single daily reminder (≈ late afternoon). */
    private const REMINDER_HOUR = 17;

    /**
     * (Re)schedule the next 7 days of local habit reminders. Called from the
     * view with the device timezone + current epoch (the WASM clock is UTC and
     * doesn't know the device tz). Each upcoming day that has an active habit
     * gets one reminder at 17:00 local; today's is dropped once every habit due
     * today is done. Notifications no longer wanted are cancelled. Idempotent —
     * safe to call on every open and after each toggle.
     */
    public function syncReminders(string $tz, int $nowMs)
    {
        if (! AuthState::isAuthenticated() || ! NativeBlade::isMobile()) {
            return null;
        }

        try {
            $now = Carbon::createFromTimestampMs($nowMs)->setTimezone($tz);
        } catch (\Throwable $e) {
            return null; // unparseable timezone — skip rather than crash
        }

        $active = HabitsState::active();
        $hasHabit = fn (int $iso): bool => (bool) array_filter(
            $active,
            fn ($h) => in_array($iso, $h['days'] ?? [], true),
        );

        $todayDone = $this->allHabitsDoneOn($now->dayOfWeekIso);

        $desired = []; // id => Carbon $when
        for ($d = 0; $d < 7; $d++) {
            $day = $now->copy()->addDays($d);
            if (! $hasHabit($day->dayOfWeekIso)) {
                continue;
            }
            $when = $day->copy()->setTime(self::REMINDER_HOUR, 0);
            if ($when->lte($now) || ($d === 0 && $todayDone)) {
                continue; // past, or today already cleared
            }
            $desired['pb-rem-'.$day->format('Y-m-d')] = $when;
        }

        $cancel = array_diff(ReminderState::scheduled(), array_keys($desired));

        // Draw the body from the pet's alignment-toned AI pool (a stable line per
        // day so re-syncs don't reshuffle), falling back to the static copy.
        $lines = ReminderState::lines();
        $bodyFor = fn (string $id): string => $lines
            ? $lines[crc32($id) % count($lines)]
            : __('messages.reminders.body');

        $response = NativeBlade::response();
        foreach ($cancel as $id) {
            $response = $response->cancelNotification($id);
        }
        foreach ($desired as $id => $when) {
            $response = $response->notification(fn (Notification $n) => $n
                ->id($id)
                ->channel('petabit')
                ->title(__('messages.reminders.title'))
                ->body($bodyFor($id))
                ->at($when));
        }

        ReminderState::setScheduled(array_keys($desired));

        return $response->toResponse();
    }

    /** Every active habit scheduled for the given ISO weekday is ticked today. */
    private function allHabitsDoneOn(int $iso): bool
    {
        foreach (HabitsState::active() as $h) {
            if (in_array($iso, $h['days'] ?? [], true) && ! in_array($h['id'], $this->done, true)) {
                return false;
            }
        }

        return true;
    }

    /* ---- merge (Mesclar) ---- */

    /** Generate a pairing QR for the other player to scan (or type the code). */
    public function generateMerge(PetabitApiClient $api): void
    {
        $this->reset('mergeMsg', 'mergeOk', 'mergeQr', 'mergeToken');

        try {
            $res = $api->mergeOffer();
        } catch (\Throwable $e) {
            $this->mergeMsg = __('messages.errors.network');

            return;
        }

        if (! empty($res['error'])) {
            $this->mergeMsg = $this->mergeError($res['error']);

            return;
        }

        $this->mergeQr = (string) ($res['qr_svg'] ?? '');
        $this->mergeToken = (string) ($res['token'] ?? '');
    }

    /** Open the native camera to scan the other player's QR (mobile). */
    public function scanMerge()
    {
        $this->reset('mergeMsg', 'mergeOk');

        return NativeBlade::scan(fn (Scan $s) => $s->id('merge')->formats(['QR_CODE']))->toResponse();
    }

    /** Copy the pairing code to the clipboard (with a haptic tap). */
    public function copyMergeCode()
    {
        if ($this->mergeToken === '') {
            return null;
        }

        return NativeBlade::clipboardWrite($this->mergeToken)->impact('light')->toResponse();
    }

    /** The scanned QR content comes back here. */
    #[On('nb:scan')]
    public function onScan($result, $id = null): void
    {
        if ($id !== 'merge') {
            return;
        }
        $code = is_array($result) ? ($result['content'] ?? '') : (string) $result;
        if ($code !== '') {
            $this->acceptMerge($code);
        }
    }

    /** Manual code entry (desktop fallback when the camera isn't available). */
    public function submitMergeCode(): void
    {
        $code = trim($this->mergeCode);
        if ($code !== '') {
            $this->acceptMerge($code);
        }
    }

    private function acceptMerge(string $token): void
    {
        $this->reset('mergeMsg', 'mergeOk', 'mergeCode', 'mergeQr', 'mergeToken');

        try {
            $res = app(PetabitApiClient::class)->mergeAccept($token);
        } catch (\Throwable $e) {
            $this->mergeMsg = __('messages.errors.network');

            return;
        }

        if (! empty($res['error'])) {
            $this->mergeMsg = $this->mergeError($res['error']);

            return;
        }

        // Nobody dies — the inheritance is queued for the next rebirth.
        PetState::set($res['pet']);
        $inherited = $res['inherited'] ?? [];
        $this->mergeOk = __('messages.home.merge.queued', [
            'name' => $res['partner'] ?? '',
            'part' => \App\Support\GenomeLabel::sectionName($inherited['section'] ?? ''),
        ]);
    }

    /** Map a server error code to a localized message (falls back to a generic one). */
    private function mergeError(string $code): string
    {
        $key = 'messages.home.merge.err.'.$code;

        return __($key) !== $key ? __($key) : __('messages.home.merge.err.generic');
    }

    #[Computed]
    public function activeHabits(): array
    {
        return HabitsState::active();
    }

    public function render()
    {
        $pet = PetState::get() ?? [];

        return view('livewire.home.home', [
            'nickname'    => AuthState::nickname() ?: OnboardingState::nickname(),
            'petGenome'   => $pet['genome'] ?? null,
            'hp'          => (int) ($pet['hp'] ?? 100),
            'hpMax'       => (int) ($pet['hp_max'] ?? 100),
            'petTraits'   => $pet['traits'] ?? [],
            'streak'      => (int) ($pet['streak'] ?? 0),
            'stageDay'    => (int) ($pet['stage_day'] ?? 1),
            'stageDays'   => (int) ($pet['stage_days'] ?? 1),
            'daysToNext'  => (int) ($pet['days_to_next'] ?? 0),
            'phaseNumber' => (int) ($pet['phase_number'] ?? 1),
            'stageName'   => $pet['stage'] ?? 'Birth',
            'generation'  => (int) ($pet['generation'] ?? 1),
            'alignment'   => (int) ($pet['alignment'] ?? 0),
            // Mesclar tab (real data now).
            'canMerge'    => PetState::canMerge(),
            'merges'      => PetState::merges(),
            'pendingMerges' => PetState::pendingMerges(),
        ]);
    }
}
