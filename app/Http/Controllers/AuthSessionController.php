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

    public function yandex(): RedirectResponse // перенаправляем юзера на яндекс Auth
    {
        return Socialite::driver('yandex')->redirect();
    }

    public function yandexRedirect(): object // принимаем возвращаемые данные и работаем с ними
    {
        $user = Socialite::driver('yandex')->user();

        $user = User::query()->firstOrCreate([ // используем firstOrCreate для проверки есть ли такие пользователи в нашей БД
            'email' => $user->email
        ], [
            'name' => $user->user['display_name'], // display_name - переменаая хранящая полное ФИО пользователя
            // остальные переменные можете посмотреть использовав $dd('$user')
            'password' => Hash::make(Str::random(24)),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
