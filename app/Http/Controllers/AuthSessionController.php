<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class AuthSessionController extends Controller
{

    public function yandex(): RedirectResponse
    {
        return Socialite::driver('yandex')->redirect();
    }

    public function yandexRedirect(): object
    {
        $user = Socialite::driver('yandex')->user();

        $user = User::query()->firstOrCreate([
            'email' => $user->email
        ], [
            'name' => $user->user['display_name'],
            'password' => Hash::make(Str::random(24)),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
