<?php

namespace Tests\Feature;

use App\Native\State\AuthState;
use App\Native\State\HabitsState;
use App\Native\State\LaunchState;
use App\Native\State\PetState;
use App\Native\State\QuestionState;
use App\Services\AppLaunch;
use Illuminate\Support\Facades\Http;
use NativeBlade\Facades\NativeBlade;
use Tests\TestCase;

class AppLaunchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        NativeBlade::fake();
        config(['petabit.api_url' => 'https://api.test']);
        AuthState::set('token', ['id' => 1, 'nickname' => 'jeff']);
    }

    private function fakeApi(array $sync, int $syncStatus = 200): void
    {
        Http::fake([
            'api.test/api/pet/sync' => Http::response($sync, $syncStatus),
            'api.test/api/habits' => Http::response(['habits' => [['id' => 7, 'days' => [1], 'done_today' => true]]]),
            'api.test/api/reflection/question' => Http::response(['question' => 'Por que eu existo?']),
        ]);
    }

    private function sync(array $overrides = []): array
    {
        return $overrides + [
            'pet' => ['stage' => 'Birth', 'dead' => false, 'stage_day' => 15],
            'evolution_due' => false,
            'reborn' => false,
            'reminder_lines' => ['oi'],
        ];
    }

    public function test_evolution_due_routes_to_the_question_with_it_prefetched(): void
    {
        $this->fakeApi($this->sync(['evolution_due' => true]));

        app(AppLaunch::class)->run();

        $this->assertSame('/question', LaunchState::route());
        $this->assertSame('Por que eu existo?', QuestionState::get());
        $this->assertSame(15, PetState::get()['stage_day']);
        $this->assertSame(7, HabitsState::all()[0]['id']);
        Http::assertSent(fn ($r) => $r->hasHeader('Authorization', 'Bearer token'));
    }

    public function test_rebirth_routes_to_keep_habits(): void
    {
        $this->fakeApi($this->sync(['reborn' => true, 'evolution_due' => true]));

        app(AppLaunch::class)->run();

        $this->assertSame('/keep-habits', LaunchState::route());
        $this->assertTrue(LaunchState::pullReborn());
        Http::assertNotSent(fn ($r) => str_contains($r->url(), '/reflection/question'));
    }

    public function test_nothing_due_opens_home(): void
    {
        $this->fakeApi($this->sync());

        app(AppLaunch::class)->run();

        $this->assertNull(LaunchState::route());
        $this->assertSame(7, HabitsState::all()[0]['id']);
    }

    public function test_revoked_token_signs_out(): void
    {
        $this->fakeApi([], 401);

        app(AppLaunch::class)->run();

        $this->assertFalse(AuthState::isAuthenticated());
        $this->assertNull(LaunchState::route());
    }

    public function test_offline_keeps_the_cached_state(): void
    {
        PetState::set(['stage' => 'Birth', 'stage_day' => 14]);
        Http::fake(fn () => throw new \Illuminate\Http\Client\ConnectionException('offline'));

        app(AppLaunch::class)->run();

        $this->assertTrue(AuthState::isAuthenticated());
        $this->assertSame(14, PetState::get()['stage_day']);
        $this->assertNull(LaunchState::route());
    }

    public function test_signed_in_open_follows_the_launch_route_until_handled(): void
    {
        LaunchState::set('/question', false);

        // NativeBlade's warmup renders "/" once before the real first navigation.
        $this->get('/')->assertJsonPath('actions.0.data.path', '/question');
        $this->get('/')->assertJsonPath('actions.0.data.path', '/question');

        LaunchState::clearRoute();

        $this->get('/')->assertJsonPath('actions.0.data.path', '/home');
    }
}
