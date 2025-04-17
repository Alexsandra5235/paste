<?php

namespace App\Repository;

use App\DTO\UserDTO;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Авторизация пользователя в аккаунте pastebin через api. Если
     * запрос успешный, в возврате будет ключ пользователя, если нет,
     * вернется null.
     * @param UserDTO $user
     * @return string|null
     * @throws ConnectionException
     */
    public function create(UserDTO $user) : ?string
    {
        $response = Http::asForm()->post(config('services.pastebin.paste_user_url'), [
            'api_dev_key' => config('services.pastebin.pastebin_api_key'),
            'api_user_name' => $user->name,
            'api_user_password' => $user->password,
        ]);

        if ($response->successful()) {
            return $response->body();
        } else {
            return null;
        }
    }
}
