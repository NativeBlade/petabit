<?php

namespace App\Livewire\Reflection;

use App\Http\Clients\PetabitApiClient;
use App\Native\State\LaunchState;
use App\Native\State\PetState;
use App\Native\State\QuestionState;
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

    /** The question prefetched by the app launch; when missing, loadQuestion() fetches it. */
    public function mount(): void
    {
        $this->question = QuestionState::get();
    }

    /** Called from the view's wire:init only when the launch couldn't prefetch it. */
    public function loadQuestion(PetabitApiClient $api): void
    {
        if ($this->question !== '') {
            return;
        }

        try {
            $this->question = $api->question();
            QuestionState::set($this->question);
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
        QuestionState::clear();
        LaunchState::clearRoute();

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
