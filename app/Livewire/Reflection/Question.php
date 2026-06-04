<?php

namespace App\Livewire\Reflection;

use App\Http\Clients\PetabitApiClient;
use App\Native\State\PetState;
use App\Native\State\ReflectionState;
use Livewire\Attributes\Flash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NativeBlade\Facades\NativeBlade;

#[Layout('components.layouts.app')]
class Question extends Component
{
    private const MAX_LENGTH = 280;

    public string $answer = '';
    public string $question = '';

    #[Flash]
    public string $error = '';

    public function mount(PetabitApiClient $api): void
    {
        try {
            $this->question = $api->question();
        } catch (\Throwable $e) {
            // Fall back to the canonical prompt if the server is unreachable.
            $this->question = __('messages.question.title');
        }
    }

    public function submit(PetabitApiClient $api)
    {
        $answer = trim($this->answer);

        if ($answer === '') {
            return;
        }

        try {
            $result = $api->submitAnswer($answer);
        } catch (\Throwable $e) {
            $this->error = __('messages.errors.network');

            return NativeBlade::impact('heavy')->toResponse();
        }

        PetState::set($result['pet']);
        ReflectionState::set($result);

        return NativeBlade::navigate('/analyzing')->toResponse();
    }

    public function render()
    {
        return view('livewire.reflection.question', [
            'maxLength'  => self::MAX_LENGTH,
            'petGenome'  => PetState::get()['genome'] ?? null,
        ]);
    }
}
