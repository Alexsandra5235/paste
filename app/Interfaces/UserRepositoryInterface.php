<?php

namespace App\Interfaces;

use App\DTO\UserDTO;

interface UserRepositoryInterface
{
    public function create(UserDTO $user) : ?string;
}
