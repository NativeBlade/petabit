<?php

namespace App\Native\State;

use NativeBlade\Facades\NativeBlade;

/**
 * Local consent state for Firebase Analytics. Collection ships OFF
 * (collectionEnabledByDefault: false) and only turns on if the user opts in.
 * We remember whether the one-time consent prompt was already shown so it never
 * nags twice; the choice itself is editable later from the Conta tab.
 */
class AnalyticsState
{
    private const ASKED = 'analytics.asked';

    private const ENABLED = 'analytics.enabled';

    /** Whether the one-time consent dialog has been shown already. */
    public static function asked(): bool
    {
        return (bool) NativeBlade::getState(self::ASKED);
    }

    public static function setAsked(bool $asked): void
    {
        NativeBlade::setState(self::ASKED, $asked);
    }

    /** Current consent: true = analytics collection on. */
    public static function enabled(): bool
    {
        return (bool) NativeBlade::getState(self::ENABLED);
    }

    public static function setEnabled(bool $enabled): void
    {
        NativeBlade::setState(self::ENABLED, $enabled);
    }
}
