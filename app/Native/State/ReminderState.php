<?php

namespace App\Native\State;

use NativeBlade\Facades\NativeBlade;

/**
 * Remembers which local habit-reminder notifications are currently scheduled
 * (by id), so the next sync can cancel the ones that are no longer wanted and
 * only (re)issue the rest. Persists across restarts, like the OS-held schedule.
 */
class ReminderState
{
    private const KEY = 'reminders.scheduled';

    private const LINES_KEY = 'reminders.lines';

    /** @return array<int, string> */
    public static function scheduled(): array
    {
        return NativeBlade::getState(self::KEY) ?? [];
    }

    /** @param array<int, string> $ids */
    public static function setScheduled(array $ids): void
    {
        NativeBlade::setState(self::KEY, array_values($ids));
    }

    /** AI reminder lines for the pet's current band/locale (from the last sync). @return array<int, string> */
    public static function lines(): array
    {
        return NativeBlade::getState(self::LINES_KEY) ?? [];
    }

    /** @param array<int, string> $lines */
    public static function setLines(array $lines): void
    {
        NativeBlade::setState(self::LINES_KEY, array_values($lines));
    }
}
