<?php

namespace App\Livewire\Onboarding;

use App\Http\Clients\PetabitApiClient;
use App\Native\State\HabitsState;
use App\Native\State\OnboardingState;
use App\Support\HabitCatalog;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Flash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NativeBlade\Facades\NativeBlade;

#[Layout('components.layouts.app')]
class HabitSetup extends Component
{
    // Custom-habit form (transient, local to this screen)
    public bool $showForm = false;
    public string $cName = '';
    public string $cIcon = '🎯';

    #[Flash]
    public string $error = '';

    /** Editing: start from the routine synced by the app launch (else the seed catalog). */
    public function mount(): void
    {
        if ($routine = HabitsState::all()) {
            OnboardingState::loadRoutine($routine);
        }
    }

    #[Computed]
    public function habits(): array
    {
        return OnboardingState::habits();
    }

    #[Computed]
    public function activeCount(): int
    {
        return count(OnboardingState::activeHabits());
    }

    public function toggle(int $id): void
    {
        OnboardingState::toggleHabit($id);
        unset($this->habits, $this->activeCount);
    }

    public function toggleWeekday(int $id, int $iso): void
    {
        if ($iso < 1 || $iso > 7) {
            return;
        }

        OnboardingState::toggleWeekday($id, $iso);
        unset($this->habits);
    }

    public function openForm(): void
    {
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->reset(['showForm', 'cName', 'cIcon']);
        $this->cIcon = '🎯';
    }

    public function addHabit(): void
    {
        if (trim($this->cName) === '') {
            return;
        }

        OnboardingState::addHabit(trim($this->cName), $this->cIcon);
        $this->closeForm();
        unset($this->habits, $this->activeCount);
    }

    public function confirm(PetabitApiClient $api)
    {
        if ($this->activeCount() === 0) {
            return;
        }

        try {
            // The saved routine (with server ids) becomes the local cache home reads.
            HabitsState::set($api->saveHabits($this->routinePayload()));
        } catch (\Throwable $e) {
            $this->error = __('messages.errors.network');

            return NativeBlade::impact('heavy')->toResponse();
        }

        return NativeBlade::navigate('/home')->toResponse();
    }

    /** Map the local habit list to the API shape (days drive the schedule). */
    private function routinePayload(): array
    {
        return array_map(fn ($h) => [
            'key' => $h['key'] ?? null,
            'name' => $h['name'] ?? null,
            'icon' => $h['icon'] ?? '🎯',
            'days' => ($h['days'] ?? []) ?: [1, 2, 3, 4, 5, 6, 7],
            'active' => (bool) ($h['active'] ?? false),
        ], OnboardingState::activeHabits());
    }

    public function render()
    {
        return view('livewire.onboarding.habit-setup', [
            'icons'    => HabitCatalog::ICONS,
            'weekdays' => [1, 2, 3, 4, 5, 6, 7],
        ]);
    }
}
