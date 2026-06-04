<?php

namespace App\Livewire\Onboarding;

use App\Native\State\LocaleState;
use App\Native\State\OnboardingState;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NativeBlade\Facades\NativeBlade;

#[Layout('components.layouts.app')]
class AuthChoice extends Component
{
    public function createAccount()
    {
        OnboardingState::setIsNew(true);

        return NativeBlade::navigate('/nickname')->toResponse();
    }

    public function existingAccount()
    {
        OnboardingState::setIsNew(false);

        return NativeBlade::navigate('/email')->toResponse();
    }

    /**
     * Switch language. LocaleState::set persists to NativeBlade::setState and
     * calls app()->setLocale, so this same render already reflects the choice.
     */
    public function changeLocale(string $locale)
    {
        LocaleState::set($locale);

        return NativeBlade::selection()->toResponse();
    }

    public function render()
    {
        return view('livewire.onboarding.auth-choice', [
            'currentLocale' => LocaleState::current(),
            'locales'       => LocaleState::supported(),
        ]);
    }
}
