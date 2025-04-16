<?php

namespace App\DTO;

class UserDTO
{
    public string $name;
    public string $password;
    /**
     * Create a new class instance.
     */
    public function __construct(string $name,string $password)
    {
        $this->name = $name;
        $this->password = $password;
    }
}
