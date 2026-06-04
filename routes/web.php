<?php

use App\Livewire\Evolution\Evolution;
use App\Livewire\Evolution\KeepHabits;
use App\Livewire\Home\Home;
use App\Livewire\Onboarding\AuthChoice;
use App\Livewire\Onboarding\Email;
use App\Livewire\Onboarding\HabitSetup;
use App\Livewire\Onboarding\Nickname;
use App\Livewire\Onboarding\VerifyCode;
use App\Livewire\Onboarding\Welcome;
use App\Livewire\Reflection\Analyzing;
use App\Livewire\Reflection\Question;
use App\Livewire\Reflection\Result;
use Illuminate\Support\Facades\Route;

// Login / signup — already-authenticated users are bounced to /home.
Route::middleware('nb.guest')->group(function () {
    Route::get('/', AuthChoice::class);
    Route::get('/nickname', Nickname::class);
    Route::get('/email', Email::class);
    Route::get('/verify', VerifyCode::class);
});

// Everything after login — guests are bounced to / (login).
Route::middleware('nb.auth')->group(function () {
    Route::get('/welcome', Welcome::class);
    Route::get('/setup', HabitSetup::class);

    Route::get('/home', Home::class);

    Route::get('/question', Question::class);
    Route::get('/analyzing', Analyzing::class);
    Route::get('/result', Result::class);

    Route::get('/evolution', Evolution::class);
    Route::get('/keep-habits', KeepHabits::class);
});
