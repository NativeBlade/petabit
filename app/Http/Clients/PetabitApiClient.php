<?php

namespace App\Http\Clients;

use App\Native\State\AuthState;
use App\Native\State\LocaleState;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * Wraps the petabit-server API: base URL, bearer token, timeout in one place.
 * Runs over NativeBlade's native HTTP bridge (Plugin::HTTP), so it bypasses
 * WebView CORS. Components depend on this client, never on Http:: directly.
 */
class PetabitApiClient
{
    private const TIMEOUT_SECONDS = 20;

    private function http(): PendingRequest
    {
        $request = Http::baseUrl(config('petabit.api_url').'/api')
            ->acceptJson()
            ->withHeaders(['x-lang' => LocaleState::current()])
            ->timeout(self::TIMEOUT_SECONDS);

        if ($token = AuthState::token()) {
            $request = $request->withToken($token);
        }

        return $request;
    }

    /* ---- auth ---- */

    public function nicknameAvailable(string $handle): bool
    {
        $response = $this->http()->get('/auth/nickname', ['handle' => $handle]);

        return $response->successful() && (bool) $response->json('available');
    }

    /** @return array{sent:bool,code?:string} */
    public function register(string $nickname, string $email): array
    {
        return $this->http()->post('/auth/register', compact('nickname', 'email'))->throw()->json();
    }

    /** @return array{sent:bool,code?:string} */
    public function login(string $email): array
    {
        return $this->http()->post('/auth/login', compact('email'))->throw()->json();
    }

    /** @return array{token:string,user:array,pet:array} */
    public function verify(string $email, string $code, ?string $nickname = null): array
    {
        return $this->http()->post('/auth/verify', array_filter([
            'email' => $email,
            'code' => $code,
            'nickname' => $nickname,
        ]))->throw()->json();
    }

    /* ---- pet / reflection ---- */

    /** @return array the pet payload ({genome, alignment, stage, ...}) */
    public function pet(): array
    {
        return $this->http()->get('/pet')->throw()->json('pet');
    }

    /**
     * App-open sync: applies past days' HP heal/damage (from the server's
     * completed-day records), reborns after the cooldown, and tells us if an
     * evolution is due.
     *
     * @return array{pet:array,evolution_due:bool,reborn:bool,hp_change:int}
     */
    public function sync(): array
    {
        return $this->http()->post('/pet/sync', [])->throw()->json();
    }

    /* ---- habits ---- */

    /** @return array<int, array> the user's routine */
    public function habits(): array
    {
        return $this->http()->get('/habits')->throw()->json('habits');
    }

    /** Replace the routine. @return array<int, array> */
    public function saveHabits(array $habits): array
    {
        return $this->http()->put('/habits', ['habits' => $habits])->throw()->json('habits');
    }

    /**
     * Tick / untick a single habit for today (persists across app reopens).
     *
     * @return array{habits:array,pet:?array}
     */
    public function checkHabit(int $habitId, bool $done): array
    {
        return $this->http()->post('/habits/check', ['habit_id' => $habitId, 'done' => $done])->throw()->json();
    }

    public function question(): string
    {
        return (string) $this->http()->get('/reflection/question')->throw()->json('question');
    }

    /** @return array{classification:array,reborn:bool,changes:array,pet:array} */
    public function submitAnswer(string $answer): array
    {
        return $this->http()->post('/reflection/answer', compact('answer'))->throw()->json();
    }

    /* ---- merge (Mesclar) ---- */

    /**
     * Create a QR pairing offer for this pet. On success returns the payload to
     * encode as a QR; on a rule failure returns {error:'not_eligible'|...}.
     *
     * @return array{token?:string,qr?:string,expires_at?:string,error?:string}
     */
    public function mergeOffer(): array
    {
        return $this->http()->post('/merge/offer', [])->json() ?? [];
    }

    /**
     * Accept a scanned token → mutual merge (both pets die-by-merge and inherit).
     *
     * @return array{partner?:string,inherited?:array,pet?:array,error?:string}
     */
    public function mergeAccept(string $token): array
    {
        return $this->http()->post('/merge/accept', ['token' => $token])->json() ?? [];
    }

    /* ---- account ---- */

    /** Request account deletion (soft — purged after a grace period unless you log back in). */
    public function deleteAccount(): array
    {
        return $this->http()->delete('/account')->throw()->json();
    }
}
