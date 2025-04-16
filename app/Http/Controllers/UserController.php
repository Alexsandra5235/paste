<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Models\User;
use App\Orchid\Layouts\User\UserListLayout;
use App\Service\UserService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
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

        $response = $this->userService->createUser($user);

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
    public function login() : object
    {
        return view('paste.login');
    }
    public function index(): Screen
    {
        return Screen::create('Users', 'Admin.Users')
            ->addLayout(UserListLayout::class);
    }

    public function ban(User $user) : RedirectResponse
    {
        $user->update(['is_banned' => true]);
        Alert::info('Пользователь заблокирован.');

        return redirect()->route('admin.users');
    }

    public function unban(User $user) : RedirectResponse
    {
        $user->update(['is_banned' => false]);
        Alert::info('Пользователь разблокирован.');

        return redirect()->route('admin.users');
    }
}
