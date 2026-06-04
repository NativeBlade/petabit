<?php

namespace App\Livewire\Onboarding;

use App\Http\Clients\PetabitApiClient;
use App\Native\State\OnboardingState;
use Illuminate\Http\Client\RequestException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Flash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NativeBlade\Facades\NativeBlade;

#[Layout('components.layouts.app')]
class Email extends Component
{
    public string $email = '';

    #[Flash]
    public string $error = '';

    public function mount(): void
    {
        $this->email = OnboardingState::email();
    }

    #[Computed]
    public function emailOk(): bool
    {
        return str_contains($this->email, '@') && str_contains($this->email, '.');
    }

    public function continue(PetabitApiClient $api)
    {
        if (! $this->emailOk()) {
            $this->error = __('messages.errors.invalid_email');

            return NativeBlade::impact('heavy')->toResponse();
        }

        OnboardingState::setEmail($this->email);
        $isNew = OnboardingState::isNew();

        try {
            if ($isNew) {
                $api->register(OnboardingState::nickname(), $this->email);
            } else {
                $api->login($this->email);
            }
        } catch (RequestException $e) {
            $this->error = $this->messageFor($isNew, $e->response?->status());

            return NativeBlade::impact('heavy')->toResponse();
        } catch (\Throwable $e) {
            $this->error = __('messages.errors.network');

            return;
        }

        return NativeBlade::navigate('/verify')->toResponse();
    }

    private function messageFor(bool $isNew, ?int $status): string
    {
        if ($isNew && $status === 422) {
            return __('messages.errors.email_taken');
        }
        if (! $isNew && $status === 404) {
            return __('messages.errors.no_account');
        }

        return __('messages.errors.generic');
    }

    public function render()
    {
        return view('livewire.onboarding.email');
    }
}
