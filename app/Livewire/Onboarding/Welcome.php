<?php

namespace App\Livewire\Onboarding;

use App\Native\State\OnboardingState;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Welcome extends Component
{
    public string $nickname = '';

    public function mount(): void
    {
        $this->nickname = OnboardingState::nickname();
    }

    public function render()
    {
        return view('livewire.onboarding.welcome');
    }
}
