<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

/**
 *
 */
class YandexController extends Controller
{
    /**
     * @return RedirectResponse
     * Страница подтверждения прав
     */
    public function redirectToYandex(): RedirectResponse
    {
        $parameters = [
            'force_confirm' => 'yes',
        ];

        return Socialite::driver('yandex')->with($parameters)->redirect();
    }

    /**
     * Получение кода авторизации
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function catchCode(Request $request): View|RedirectResponse
    {
        $code = $request->get('code');

        if (!$code) {
            return redirect()->route('login')->withErrors(['oauth' => 'Не удалось получить код авторизации от Яндекса']);
        }

        return view('auth.yandex-post', ['code' => $code]);
    }

    /**
     * Отправка запроса с кодом авторизации
     * @param Request $request
     * @return RedirectResponse
     * Данные пользователя
     */
    public function handleCallback(Request $request): RedirectResponse
    {
        $code = $request->input('code');

        if (!$code) {
            return redirect()->route('login')->withErrors(['oauth' => 'Код авторизации не найден']);
        }

        try {
            $yandexUser = Socialite::driver('yandex')->userFromToken(
                Socialite::driver('yandex')->getAccessTokenResponse($code)['access_token']
            );
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['oauth' => 'Ошибка авторизации через Яндекс']);
        }

        $user = User::query()->firstOrCreate(
            ['email' => $yandexUser->getEmail()],
            [
                'name' => $yandexUser->getName() ?? $yandexUser->getNickname(),
                'password' => Hash::make(Str::random(24)),
            ]
        );

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
