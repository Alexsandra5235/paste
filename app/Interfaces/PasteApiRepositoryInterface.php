<?php

namespace App\Interfaces;

use App\DTO\PasteDTO;

interface PasteApiRepositoryInterface
{
    public function create(PasteDTO $pasteDTO) : array;
    public function getPasteByUser(string $user_key) : array;
    public function findAll(int $paste_private) : ?array;
    public function delete(string $user_key, string $paste_key): array;
    public function getUrlPasteUser(): ?array;
    public function findLastPastes();
}
