<?php

namespace App\Livewire\Onboarding;

use App\Http\Clients\PetabitApiClient;
use App\Native\State\AuthState;
use App\Native\State\OnboardingState;
use App\Native\State\PetState;
use Illuminate\Http\Client\RequestException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Flash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NativeBlade\Facades\NativeBlade;

#[Layout('components.layouts.app')]
class VerifyCode extends Component
{
    private const CODE_LENGTH = 4;

    public string $code = '';
    public string $email = '';
    public bool $isNew = true;

    #[Flash]
    public string $error = '';

    public function mount(): void
    {
        $this->email = OnboardingState::email();
        $this->isNew = OnboardingState::isNew();
    }

    public function updatedCode(string $value): void
    {
        $this->code = mb_substr(preg_replace('/\D/', '', $value), 0, self::CODE_LENGTH);
    }

    #[Computed]
    public function codeOk(): bool
    {
        return mb_strlen($this->code) === self::CODE_LENGTH;
    }

    public function confirm(PetabitApiClient $api)
    {
        if (! $this->codeOk()) {
            return;
        }

        try {
            $result = $api->verify(
                $this->email,
                $this->code,
                $this->isNew ? OnboardingState::nickname() : null,
            );
        } catch (RequestException $e) {
            $this->error = $e->response?->status() === 422
                ? __('messages.errors.invalid_code')
                : __('messages.errors.generic');

            return NativeBlade::impact('heavy')->toResponse();
        } catch (\Throwable $e) {
            $this->error = __('messages.errors.network');

            return;
        }

        AuthState::set($result['token'], $result['user']);
        PetState::set($result['pet']);

        // New users continue onboarding (welcome → setup); returning users go home.
        return NativeBlade::navigate($this->isNew ? '/welcome' : '/home', replace: true)->toResponse();
    }

    public function render()
    {
        return view('livewire.onboarding.verify-code');
    }
}
