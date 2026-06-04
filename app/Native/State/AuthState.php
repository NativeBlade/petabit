<?php

namespace App\Native\State;

use NativeBlade\Facades\NativeBlade;

/**
 * The signed-in session: Sanctum token + user (nickname/email), persisted via
 * NativeBlade::setState so it survives app restarts. Source of truth for auth.
 */
class AuthState
{
    private const TOKEN_KEY = 'auth.token';
    private const USER_KEY = 'auth.user';

    public static function set(string $token, array $user): void
    {
        NativeBlade::setState(self::TOKEN_KEY, $token);
        NativeBlade::setState(self::USER_KEY, $user);
    }

    public static function token(): ?string
    {
        return NativeBlade::getState(self::TOKEN_KEY);
    }

    /** @return array{id?:int,nickname?:string,email?:string}|null */
    public static function user(): ?array
    {
        return NativeBlade::getState(self::USER_KEY);
    }

    public static function nickname(): string
    {
        return self::user()['nickname'] ?? '';
    }

    public static function isAuthenticated(): bool
    {
        return self::token() !== null;
    }

    public static function clear(): void
    {
        NativeBlade::forget(self::TOKEN_KEY);
        NativeBlade::forget(self::USER_KEY);
    }
}
