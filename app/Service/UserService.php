<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Repository\UserRepository;
use Exception;
use Illuminate\Http\Client\ConnectionException;

class UserService
{
    protected UserRepository $userRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Авторизация пользователя в аккаунте Pastebin. При
     * успехе возвращается ключ пользователя. При неудаче null.
     * @param UserDTO $user
     * @return string|null
     * @throws ConnectionException
     */
    public function createUser(UserDTO $user) : ?string
    {
        $user_key = $this->userRepository->create($user);
        if($user_key) return $user_key;
        return null;
    }
}
