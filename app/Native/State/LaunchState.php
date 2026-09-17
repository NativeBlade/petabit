<?php

namespace App\Native\State;

use NativeBlade\Facades\NativeBlade;

/**
 * What the app launch (AppLaunch, run on boot / right after login) decided:
 * the screen to open instead of home (evolution → /question, rebirth →
 * /keep-habits) and whether a life just restarted (for analytics). The next
 * launch recomputes both from scratch.
 *
 * The route is read without side effects: NativeBlade's warmup renders "/"
 * once before the real first navigation, so the destination screen clears it
 * (clearRoute) once it has handled it.
 */
class LaunchState
{
    private const ROUTE_KEY = 'launch.route';
    private const REBORN_KEY = 'launch.reborn';

    public static function set(?string $route, bool $reborn): void
    {
        self::clear();

        if ($route !== null) {
            NativeBlade::setState(self::ROUTE_KEY, $route);
        }
        if ($reborn) {
            NativeBlade::setState(self::REBORN_KEY, true);
        }
    }

    /** The launch route, or null to open home. */
    public static function route(): ?string
    {
        $route = NativeBlade::getState(self::ROUTE_KEY);

        return is_string($route) && $route !== '' ? $route : null;
    }

    /** The launch route was handled (reflection answered / routine confirmed). */
    public static function clearRoute(): void
    {
        NativeBlade::forget(self::ROUTE_KEY);
    }

    /** Whether this launch reborn the pet (and forget it). */
    public static function pullReborn(): bool
    {
        $reborn = (bool) NativeBlade::getState(self::REBORN_KEY);
        NativeBlade::forget(self::REBORN_KEY);

        return $reborn;
    }

    public static function clear(): void
    {
        NativeBlade::forget(self::ROUTE_KEY);
        NativeBlade::forget(self::REBORN_KEY);
    }
}
