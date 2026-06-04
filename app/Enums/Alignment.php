<?php

namespace App\Enums;

enum Alignment: string
{
    case Good        = 'good';
    case GoodNeutral = 'good-neutral';
    case Neutral     = 'neutral';
    case EvilNeutral = 'evil-neutral';
    case Evil        = 'evil';

    /** Hex color used across pet aura, badges and accents. */
    public function color(): string
    {
        return match ($this) {
            self::Good        => '#fbbf24',
            self::GoodNeutral => '#34d399',
            self::Neutral     => '#94a3b8',
            self::EvilNeutral => '#a855f7',
            self::Evil        => '#ef4444',
        };
    }

    /** Human-facing, localized label. */
    public function label(): string
    {
        return __('data.alignment.'.$this->value);
    }

    public function isGood(): bool
    {
        return $this === self::Good || $this === self::GoodNeutral;
    }

    public function isEvil(): bool
    {
        return $this === self::Evil || $this === self::EvilNeutral;
    }
}
