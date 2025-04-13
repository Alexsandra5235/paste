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
     * @throws ConnectionException
     */
    public function createUser(UserDTO $user) : string
    {
        return $this->userRepository->create($user);
    }
}
