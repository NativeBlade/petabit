<?php

namespace App\Native\State;

use NativeBlade\Facades\NativeBlade;

/**
 * The result of the last reflection (classification + gradual change log), used
 * by the Analyzing screen (reveal) and the Result screen (summary). Kept in
 * 'session' scope — it only matters for the current reveal, not across restarts.
 */
class ReflectionState
{
    private const KEY = 'reflection.last';
    private const SCOPE = 'session';

    /** @param array{classification:array,reborn:bool,changes:array,pet:array} $result */
    public static function set(array $result): void
    {
        NativeBlade::setState(self::KEY, [
            'classification' => $result['classification'] ?? null,
            'reborn' => $result['reborn'] ?? false,
            'changes' => $result['changes'] ?? [],
        ], self::SCOPE);
    }

    /** @return array<int, string> */
    public static function changes(): array
    {
        return NativeBlade::getState(self::KEY)['changes'] ?? [];
    }

    public static function reborn(): bool
    {
        return (bool) (NativeBlade::getState(self::KEY)['reborn'] ?? false);
    }

    public static function classification(): ?array
    {
        return NativeBlade::getState(self::KEY)['classification'] ?? null;
    }

    public static function clear(): void
    {
        NativeBlade::forget(self::KEY);
    }
}
