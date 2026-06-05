<?php

namespace App\Native\State;

use NativeBlade\Facades\NativeBlade;

/**
 * The device's IANA timezone (e.g. "Europe/Madrid"), captured from JS on load
 * and sent to the server as the `x-tz` header. The server uses it to roll the
 * pet's "day" at the user's local midnight instead of UTC. Persisted across
 * restarts, so only the very first launch (a day-old newborn) falls back to UTC.
 */
class TimezoneState
{
    private const KEY = 'device.timezone';

    public static function current(): ?string
    {
        $tz = NativeBlade::getState(self::KEY);

        return is_string($tz) && $tz !== '' ? $tz : null;
    }

    public static function set(string $tz): void
    {
        if ($tz !== '') {
            NativeBlade::setState(self::KEY, $tz);
        }
    }
}
