<?php

namespace App\Native\State;

use NativeBlade\Facades\NativeBlade;

/**
 * The single source of truth for the user's language choice.
 *
 * Persisted via NativeBlade::setState (survives app restarts) so PHP (__())
 * and the JS shell stay in agreement. Always change the locale through
 * LocaleState::set() — never call app()->setLocale() directly elsewhere.
 */
class LocaleState
{
    private const KEY = 'locale.current';

    /** Supported locales, in display order. English is the default. */
    private const SUPPORTED = ['en', 'pt_BR', 'es'];

    private const DEFAULT = 'en';

    /** Short labels for a compact language switcher. */
    private const LABELS = [
        'en'    => 'EN',
        'pt_BR' => 'PT',
        'es'    => 'ES',
    ];

    public static function set(string $locale): void
    {
        if (! in_array($locale, self::SUPPORTED, true)) {
            return;
        }

        // NativeBlade persists it, applies app()->setLocale, and writes the
        // boot locale file so the splash follows the choice on next launch.
        NativeBlade::setLanguage($locale);
    }

    public static function current(): string
    {
        return NativeBlade::currentLanguage();
    }

    /** @return array<int, string> */
    public static function supported(): array
    {
        return self::SUPPORTED;
    }

    public static function label(string $locale): string
    {
        return self::LABELS[$locale] ?? strtoupper($locale);
    }
}
