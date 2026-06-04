<?php

namespace App\Enums;

enum HomeTab: string
{
    case Petabit = 'petabit';
    case Genoma  = 'genoma';
    case Mesclar = 'mesclar';

    public function label(): string
    {
        return __('data.tab.'.$this->value);
    }
}
