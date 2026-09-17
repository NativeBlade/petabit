<?php

namespace App\Services;

use App\Exceptions\UnauthenticatedException;
use App\Http\Clients\PetabitApiClient;
use App\Native\State\AuthState;
use App\Native\State\HabitsState;
use App\Native\State\LaunchState;
use App\Native\State\PetState;
use App\Native\State\QuestionState;
use App\Native\State\ReminderState;

/**
 * Everything that must be settled before the first screen shows. Runs from
 * NativeBladeConfig::onBoot (splash still up) and right after a returning user
 * logs in. It syncs the pet (daily HP, rebirth, evolution check) and the
 * routine into local state, and records where the app should open; the route
 * middlewares read that decision, so screens only ever read local state.
 *
 * onBoot replays from the top on every HTTP call, so the calls happen first in
 * a fixed order and every state write waits until all responses are in.
 */
class AppLaunch
{
    public function __construct(private readonly PetabitApiClient $api) {}

    public function run(): void
    {
        LaunchState::clear();

        if (! AuthState::isAuthenticated()) {
            return;
        }

        try {
            ['sync' => $summary, 'habits' => $habits] = $this->api->launch();
        } catch (UnauthenticatedException $e) {
            // Stale/revoked token: drop the session so the app opens on login.
            AuthState::clear();
            PetState::clear();
            HabitsState::clear();

            return;
        } catch (\Throwable $e) {
            return; // Offline: open home on the cached pet + routine.
        }

        $dead = (bool) ($summary['pet']['dead'] ?? false);
        $reborn = (bool) ($summary['reborn'] ?? false) && ! $dead;
        $evolution = (bool) ($summary['evolution_due'] ?? false) && ! $dead && ! $reborn;

        // Prefetch the reflection so the question screen opens ready.
        $question = null;
        if ($evolution) {
            try {
                $question = $this->api->question();
            } catch (\Throwable $e) {
                // The question screen fetches it itself.
            }
        }

        PetState::set($summary['pet']);
        HabitsState::set($habits);
        ReminderState::setLines($summary['reminder_lines'] ?? []);
        if ($question !== null) {
            QuestionState::set($question);
        }

        LaunchState::set(
            $reborn ? '/keep-habits' : ($evolution ? '/question' : null),
            $reborn,
        );
    }
}
