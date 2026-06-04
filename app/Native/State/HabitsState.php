<?php

namespace App\Native\State;

use NativeBlade\Facades\NativeBlade;

/**
 * Cache of the user's routine as returned by the server (the single source of
 * truth). Populated on app-open / screen mount; read by Home, keep-habits and
 * the setup editor. Each habit: {id, key, name, icon, days, active}.
 */
class HabitsState
{
    private const KEY = 'habits.server';

    public static function set(array $habits): void
    {
        NativeBlade::setState(self::KEY, $habits);
    }

    /** @return array<int, array> */
    public static function all(): array
    {
        return NativeBlade::getState(self::KEY) ?? [];
    }

    /** @return array<int, array> active habits only */
    public static function active(): array
    {
        return array_values(array_filter(self::all(), fn ($h) => $h['active'] ?? true));
    }

    public static function clear(): void
    {
        NativeBlade::forget(self::KEY);
    }
}
