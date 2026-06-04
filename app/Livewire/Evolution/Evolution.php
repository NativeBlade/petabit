<?php

namespace App\Livewire\Evolution;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Evolution extends Component
{
    public function render()
    {
        return view('livewire.evolution.evolution');
    }
}
