<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Orchid\Screen\Screen;

class UserController extends Controller
{
    public function index(): Screen
    {
        return Screen::query()->create('Users', 'Admin.Users')
            ->addLayout(UserListLayout::class);
    }

    public function ban(User $user)
    {
        $user->update(['is_banned' => true]);
        Alert::info('Пользователь заблокирован.');

        return redirect()->route('admin.users');
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false]);
        Alert::info('Пользователь разблокирован.');

        return redirect()->route('admin.users');
    }
}
