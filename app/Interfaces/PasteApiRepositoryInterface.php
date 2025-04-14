<?php

namespace App\Interfaces;

use App\DTO\PasteDTO;

interface PasteApiRepositoryInterface
{
    public function create(PasteDTO $pasteDTO) : array;
    public function getPasteByUser() : array;
}
