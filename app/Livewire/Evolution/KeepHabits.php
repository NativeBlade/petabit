<?php

namespace App\Livewire\Evolution;

use App\Native\State\HabitsState;
use App\Native\State\LaunchState;
use App\Native\State\PetState;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class KeepHabits extends Component
{
    /** The routine was synced by the app launch (AppLaunch); this screen only reads it. */
    public function mount(): void
    {
        LaunchState::clearRoute(); // a rebirth launch lands here — handled
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
