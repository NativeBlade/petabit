<?php

namespace App\Support;

/**
 * The built-in habit catalog: the suggested habits offered during setup and the
 * icons available when creating a custom one. These are real, shipped data (not
 * placeholders): each habit carries a stable `key` so the label re-localizes on
 * every render via `data.habits.<key>`. Custom habits the user creates have no
 * key and fall back to their raw name.
 */
class HabitCatalog
{
    /** Icons available when creating a custom habit. */
    public const ICONS = ['🎯', '💪', '🎮', '🎸', '🌊', '🎵', '🏊', '🧠', '❤️', '☀️', '🌙', '⭐', '🔥', '🍎', '🎲'];

    /** Default schedules (ISO weekdays 1=Mon..7=Sun) — the user adjusts them. */
    private const ALL_WEEK = [1, 2, 3, 4, 5, 6, 7];
    private const WEEKDAYS = [1, 2, 3, 4, 5];
    private const MON_WED_FRI = [1, 3, 5];

    /** Seed list of habits offered during setup (key resolved at render time). */
    public static function seed(): array
    {
        return [
            ['id' => 1,  'key' => 'water',       'icon' => '💧', 'active' => false, 'days' => self::ALL_WEEK],
            ['id' => 2,  'key' => 'study',       'icon' => '📚', 'active' => false, 'days' => self::WEEKDAYS],
            ['id' => 3,  'key' => 'train',       'icon' => '🏃', 'active' => false, 'days' => self::MON_WED_FRI],
            ['id' => 4,  'key' => 'sleep',       'icon' => '😴', 'active' => false, 'days' => self::ALL_WEEK],
            ['id' => 5,  'key' => 'meditate',    'icon' => '🧘', 'active' => false, 'days' => self::ALL_WEEK],
            ['id' => 6,  'key' => 'eat_well',    'icon' => '🥗', 'active' => false, 'days' => self::ALL_WEEK],
            ['id' => 7,  'key' => 'read',        'icon' => '📖', 'active' => false, 'days' => self::WEEKDAYS],
            ['id' => 8,  'key' => 'write',       'icon' => '✍️', 'active' => false, 'days' => self::MON_WED_FRI],
            ['id' => 9,  'key' => 'walk',        'icon' => '🚶', 'active' => false, 'days' => self::WEEKDAYS],
            ['id' => 10, 'key' => 'stretch',     'icon' => '🤸', 'active' => false, 'days' => self::ALL_WEEK],
            ['id' => 11, 'key' => 'art',         'icon' => '🎨', 'active' => false, 'days' => self::MON_WED_FRI],
            ['id' => 12, 'key' => 'supplements', 'icon' => '💊', 'active' => false, 'days' => self::ALL_WEEK],
            ['id' => 13, 'key' => 'tidy',        'icon' => '🧹', 'active' => false, 'days' => self::MON_WED_FRI],
            ['id' => 14, 'key' => 'finances',    'icon' => '💰', 'active' => false, 'days' => self::MON_WED_FRI],
            ['id' => 15, 'key' => 'no_screen',   'icon' => '📵', 'active' => false, 'days' => self::ALL_WEEK],
            ['id' => 16, 'key' => 'outdoors',    'icon' => '🌿', 'active' => false, 'days' => self::WEEKDAYS],
        ];
    }

    /** Localized label: catalog habits (with a key) translate; custom habits show their raw name. */
    public static function label(array $habit): string
    {
        return ! empty($habit['key']) ? __('data.habits.'.$habit['key']) : ($habit['name'] ?? '');
    }
}
