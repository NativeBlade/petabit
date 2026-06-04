<?php

namespace App\Livewire\Reflection;

use App\Enums\Alignment;
use App\Native\State\PetState;
use App\Native\State\ReflectionState;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Result extends Component
{
    public ?array $pet = null;
    public array $changes = [];
    public bool $reborn = false;
    public int $alignment = 0;
    public string $stage = 'Birth';

    public function mount(): void
    {
        $this->pet = PetState::get();
        $this->changes = ReflectionState::changes();
        $this->reborn = ReflectionState::reborn();
        $this->alignment = PetState::alignment();
        $this->stage = PetState::stage();
    }

    /** Map the server's numeric alignment to the 5-band enum used by the visuals. */
    public function alignmentEnum(): Alignment
    {
        return match (true) {
            $this->alignment <= -60 => Alignment::Evil,
            $this->alignment <= -34 => Alignment::EvilNeutral,
            $this->alignment >= 60  => Alignment::Good,
            $this->alignment >= 34  => Alignment::GoodNeutral,
            default                 => Alignment::Neutral,
        };
    }

    public function render()
    {
        return view('livewire.reflection.result');
    }
}
