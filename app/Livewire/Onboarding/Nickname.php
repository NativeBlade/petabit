<?php

namespace App\Livewire\Onboarding;

use App\Http\Clients\PetabitApiClient;
use App\Native\State\OnboardingState;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Flash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NativeBlade\Facades\NativeBlade;

#[Layout('components.layouts.app')]
class Nickname extends Component
{
    /** Nicknames containing these are treated as already taken (local pre-check). */
    private const RESERVED = ['admin', 'test'];

    public string $nickname = '';
    public bool $taken = false;

    #[Flash]
    public string $error = '';

    public function mount(): void
    {
        $this->nickname = OnboardingState::nickname();
    }

    public function updatedNickname(): void
    {
        $this->taken = false;
    }

    #[Computed]
    public function nickOk(): bool
    {
        $value = mb_strtolower($this->nickname);

        if (mb_strlen($this->nickname) <= 3) {
            return false;
        }

        foreach (self::RESERVED as $word) {
            if (str_contains($value, $word)) {
                return false;
            }
        }

        return true;
    }

    public function continue(PetabitApiClient $api)
    {
        if (! $this->nickOk()) {
            return NativeBlade::impact('heavy')->toResponse();
        }

        try {
            if (! $api->nicknameAvailable($this->nickname)) {
                $this->taken = true;

                return NativeBlade::impact('heavy')->toResponse();
            }
        } catch (\Throwable $e) {
            $this->error = __('messages.errors.network');

            return;
        }

        OnboardingState::setNickname($this->nickname);

        return NativeBlade::navigate('/email')->toResponse();
    }

    public function render()
    {
        return view('livewire.onboarding.nickname');
    }
}
