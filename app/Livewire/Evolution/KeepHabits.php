<?php

namespace App\Livewire\Evolution;

use App\Http\Clients\PetabitApiClient;
use App\Native\State\AuthState;
use App\Native\State\HabitsState;
use App\Native\State\PetState;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class KeepHabits extends Component
{
    public function mount(PetabitApiClient $api): void
    {
        if (! AuthState::isAuthenticated()) {
            return;
        }

        try {
            HabitsState::set($api->habits());
        } catch (\Throwable $e) {
            // Offline: use the cached routine.
        }
    }

    #[Computed]
    public function activeHabits(): array
    {
        return HabitsState::active();
    }

    public function render()
    {
        return view('livewire.evolution.keep-habits', [
            'petGenome' => PetState::get()['genome'] ?? null,
        ]);
    }
}
