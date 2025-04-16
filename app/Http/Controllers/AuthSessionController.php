<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

class AuthSessionController extends Controller
{

    public function yandex(): RedirectResponse
    {
        return Socialite::driver('yandex')->redirect();
    }

    public function yandexRedirect(): object
    {
        try {
            $user = Socialite::driver('yandex')->user();

            $user = User::query()->firstOrCreate([
                'email' => $user->getEmail(),
            ], [
                'name' => $user->getName(),
                'password' => Hash::make(Str::random(24)),
            ]);

            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (InvalidStateException $exception){
            return redirect()->route('dashboard');
        }
    }
}
