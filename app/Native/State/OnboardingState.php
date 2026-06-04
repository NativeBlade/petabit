<?php

namespace App\Native\State;

use App\Enums\Alignment;
use App\Support\HabitCatalog;
use NativeBlade\Facades\NativeBlade;

/**
 * Cross-page state for the Petabit onboarding + daily flow. SQLite-backed via
 * NativeBlade::setState, so it survives full-page navigations between screens
 * (Livewire component props do not). This is the mock store — no real backend.
 */
class OnboardingState
{
    private const KEY = 'petabit.onboarding';

    private const DEFAULTS = [
        'nickname'  => '',
        'email'     => '',
        'isNew'     => true,
        'alignment' => null,
        'answer'    => '',
        'habits'    => null, // lazily seeded from HabitCatalog
    ];

    /** Next id handed out to custom habits. */
    private const CUSTOM_HABIT_ID_BASE = 200;

    private static function all(): array
    {
        $state = NativeBlade::getState(self::KEY) ?? [];
        $state = array_merge(self::DEFAULTS, $state);

        if ($state['habits'] === null) {
            $state['habits'] = HabitCatalog::seed();
        }

        return $state;
    }

    private static function put(array $state): void
    {
        NativeBlade::setState(self::KEY, $state);
    }

    private static function set(string $field, mixed $value): void
    {
        $state = self::all();
        $state[$field] = $value;
        self::put($state);
    }

    public static function nickname(): string
    {
        return self::all()['nickname'];
    }

    public static function setNickname(string $value): void
    {
        self::set('nickname', $value);
    }

    public static function email(): string
    {
        return self::all()['email'];
    }

    public static function setEmail(string $value): void
    {
        self::set('email', $value);
    }

    public static function isNew(): bool
    {
        return (bool) self::all()['isNew'];
    }

    public static function setIsNew(bool $value): void
    {
        self::set('isNew', $value);
    }

    public static function answer(): string
    {
        return self::all()['answer'];
    }

    public static function setAnswer(string $value): void
    {
        self::set('answer', $value);
    }

    public static function alignment(): ?Alignment
    {
        $value = self::all()['alignment'];

        return $value ? Alignment::tryFrom($value) : null;
    }

    public static function setAlignment(Alignment $alignment): void
    {
        self::set('alignment', $alignment->value);
    }

    /** @return array<int, array> */
    public static function habits(): array
    {
        return self::all()['habits'];
    }

    /** @return array<int, array> */
    public static function activeHabits(): array
    {
        return array_values(array_filter(self::habits(), fn ($h) => $h['active']));
    }

    public static function toggleHabit(int $id): void
    {
        $habits = array_map(function ($h) use ($id) {
            if ($h['id'] === $id) {
                $h['active'] = ! $h['active'];
            }

            return $h;
        }, self::habits());

        self::set('habits', $habits);
    }

    /** Toggle a weekday (ISO 1..7) in a habit's schedule. */
    public static function toggleWeekday(int $id, int $iso): void
    {
        $habits = array_map(function ($h) use ($id, $iso) {
            if ($h['id'] === $id) {
                $days = $h['days'] ?? [1, 2, 3, 4, 5, 6, 7];
                if (in_array($iso, $days, true)) {
                    $days = array_values(array_diff($days, [$iso]));
                } else {
                    $days[] = $iso;
                    sort($days);
                }
                $h['days'] = $days;
            }

            return $h;
        }, self::habits());

        self::set('habits', $habits);
    }

    public static function addHabit(string $name, string $icon): void
    {
        $habits = self::habits();
        $nextId = self::CUSTOM_HABIT_ID_BASE + count($habits);

        $habits[] = [
            'id'     => $nextId,
            'key'    => null, // custom habit: display the user's raw name, not a translation key
            'name'   => $name,
            'icon'   => $icon,
            'active' => true,
            'days'   => [1, 2, 3, 4, 5, 6, 7],
        ];

        self::set('habits', $habits);
    }

    /**
     * Seed the editable habit list from the server routine (for editing an
     * existing routine): catalog habits matched by key become active with the
     * server's days; custom server habits are appended.
     *
     * @param  array<int, array>  $server
     */
    public static function loadRoutine(array $server): void
    {
        $catalog = array_map(function ($h) use ($server) {
            $match = null;
            foreach ($server as $s) {
                if (($s['key'] ?? null) !== null && $s['key'] === $h['key']) {
                    $match = $s;
                    break;
                }
            }
            $h['active'] = $match !== null;
            if ($match) {
                $h['days'] = $match['days'] ?? $h['days'];
            }

            return $h;
        }, HabitCatalog::seed());

        $id = self::CUSTOM_HABIT_ID_BASE;
        foreach ($server as $s) {
            if (($s['key'] ?? null) === null) {
                $catalog[] = [
                    'id' => $id++,
                    'key' => null,
                    'name' => $s['name'] ?? '',
                    'icon' => $s['icon'] ?? '🎯',
                    'active' => true,
                    'days' => $s['days'] ?? [1, 2, 3, 4, 5, 6, 7],
                ];
            }
        }

        self::put(array_merge(self::all(), ['habits' => $catalog]));
    }

    public static function reset(): void
    {
        NativeBlade::forget(self::KEY);
    }
}
