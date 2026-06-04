<?php

namespace App\Livewire\Reflection;

use App\Native\State\PetState;
use App\Native\State\ReflectionState;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Analyzing extends Component
{
    /** Reveal pacing: a beat before the first change, then one per change. */
    private const LEAD_MS = 1000;
    private const PER_CHANGE_MS = 750;
    private const TAIL_MS = 1100;

    public array $changes = [];
    public bool $reborn = false;

    public function mount(): void
    {
        $this->changes = ReflectionState::changes();
        $this->reborn = ReflectionState::reborn();
    }

    public function render()
    {
        $count = max(1, count($this->changes));

        return view('livewire.reflection.analyzing', [
            'leadMs' => self::LEAD_MS,
            'perChangeMs' => self::PER_CHANGE_MS,
            // total time on screen before auto-advancing to the result
            'totalMs' => self::LEAD_MS + $count * self::PER_CHANGE_MS + self::TAIL_MS,
            'petGenome' => PetState::get()['genome'] ?? null,
        ]);
    }
}
