<?php

namespace App\Interfaces;

use App\DTO\PasteDTO;

interface PasteApiRepositoryInterface
{
    public function create(PasteDTO $pasteDTO) : string;
    public function getPasteByUser($userId) : array;
}
