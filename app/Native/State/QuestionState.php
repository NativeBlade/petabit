<?php

namespace App\Native\State;

use NativeBlade\Facades\NativeBlade;

/**
 * The pending reflection question, prefetched by the launch when an evolution
 * is due (the server generates it with AI, which takes a few seconds) so the
 * question screen opens already filled. Cleared once the answer is sent.
 */
class QuestionState
{
    private const KEY = 'reflection.question';

    public static function set(string $question): void
    {
        NativeBlade::setState(self::KEY, $question);
    }

    public static function get(): string
    {
        $question = NativeBlade::getState(self::KEY);

        return is_string($question) ? $question : '';
    }

    public static function clear(): void
    {
        NativeBlade::forget(self::KEY);
    }
}
