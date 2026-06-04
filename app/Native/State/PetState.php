<?php

namespace App\Native\State;

use NativeBlade\Facades\NativeBlade;

/**
 * The latest pet snapshot received from the API (genome + lifecycle). The rich
 * renderer reads the genome from here; screens read stage/alignment.
 */
class PetState
{
    private const KEY = 'pet.current';

    /** Store the pet payload returned by the API ({genome, alignment, stage, ...}). */
    public static function set(array $pet): void
    {
        NativeBlade::setState(self::KEY, $pet);
    }

    public static function get(): ?array
    {
        return NativeBlade::getState(self::KEY);
    }

    public static function genome(): ?array
    {
        return self::get()['genome'] ?? null;
    }

    public static function stage(): string
    {
        return self::get()['stage'] ?? 'Birth';
    }

    public static function alignment(): int
    {
        return (int) (self::get()['alignment'] ?? 0);
    }

    /** Realized inherited traits (one entry per inherited section, with provenance). */
    public static function merges(): array
    {
        return self::get()['merges'] ?? [];
    }

    /** Inheritances queued by merges, waiting for the next rebirth to be realized. */
    public static function pendingMerges(): array
    {
        return self::get()['pending_merges'] ?? [];
    }

    /** Alive and mature enough (Adult+) to take part in a merge. */
    public static function canMerge(): bool
    {
        return (bool) (self::get()['can_merge'] ?? false);
    }

    public static function clear(): void
    {
        NativeBlade::forget(self::KEY);
    }
}
