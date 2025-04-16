<?php

namespace App\Interfaces;

use App\DTO\PasteDTO;

/**
 *
 */
interface PasteApiRepositoryInterface
{
    /**
     * @param PasteDTO $pasteDTO
     * @return array[]
     */
    public function create(PasteDTO $pasteDTO) : array;

    /**
     * @param string $user_key
     * @return array
     */
    public function getPasteByUser(string $user_key) : array;

    /**
     * @param int $paste_private
     * @return array|null
     */
    public function findAll(int $paste_private) : ?array;

    /**
     * @param string $user_key
     * @param string $paste_key
     * @return array
     */
    public function delete(string $user_key, string $paste_key): array;

    /**
     * @return array|null
     */
    public function getUrlPasteUser(): ?array;

    /**
     * @return mixed
     */
    public function findLastPastes();
}
