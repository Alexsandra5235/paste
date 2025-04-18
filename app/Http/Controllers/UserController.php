<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Models\User;
use App\Orchid\Layouts\User\UserListLayout;
use App\Service\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

/**
 *
 */
class UserController extends Controller
{
    /**
     * Авторизация пользователя в pastebin и добавление ключа пользователю в случае успеха.
     * @return string
     * Возвращает результат добавление, либо успех, либо ошибка.
     * @param Request $request
     * @throws ConnectionException
     */
    public function store(Request $request): string
    {
        $request->validate([
            'user_name' => 'required|string',
            'user_password' => 'required|string',
        ]);

        $user = new UserDTO(
            $request->input('user_name'),
            $request->input('user_password'),
        );

        $response = app(UserService::class)->createUser($user);

        if(!$response){
            return redirect()->back()->with('errors','Неверные учетные данные.');
        }
        else {
            $user = Auth::user();
            $user->api_key = $response;
            $user->save();
            return redirect()->back()->with('success','Успешный вход в аккаунт Pastebin.');
        }
    }

    /**
     * @return View
     * возвращает страницу авторизации в pastebin.
     */
    public function login() : View
    {
        return view('paste.login');
    }

    /**
     * @return Screen
     */
    public function index(): Screen
    {
        return Screen::create('Users', 'Admin.Users')
            ->addLayout(UserListLayout::class);
    }

    /**
     * Бан пользователя.
     * @param User $user
     * @return RedirectResponse
     */
    public function ban(User $user) : RedirectResponse
    {
        $user->update(['is_banned' => true]);
        Alert::info('Пользователь заблокирован.');

        return redirect()->route('admin.users');
    }

    /**
     * Анбан пользователя
     * @param User $user
     * @return RedirectResponse
     */
    public function unban(User $user) : RedirectResponse
    {
        $user->update(['is_banned' => false]);
        Alert::info('Пользователь разблокирован.');

        return redirect()->route('admin.users');
    }
}
