<?php

namespace App\Repository;

use App\DTO\UserDTO;
use App\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class UserRepository implements UserRepositoryInterface
{


    /**
     * @throws ConnectionException
     * @throws Exception
     */
    public function create(UserDTO $user) : string
    {
        $response = Http::asForm()->post(env('PASTEBIN_USER_URL'), [
            'api_dev_key' => env('PASTEBIN_API_KEY'),
            'api_user_name' => $user->name,
            'api_user_password' => $user->password,
        ]);

        if ($response->successful()) {
            return $response->body();
        } else {
            return '';
        }
    }
}
