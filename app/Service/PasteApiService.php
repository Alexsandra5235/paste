<?php

namespace App\Service;

use App\DTO\PasteDTO;
use App\Repository\PasteApiRepository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;

class PasteApiService
{
    protected PasteApiRepository $pasteApiRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(PasteApiRepository $pasteApiRepository)
    {
        $this->pasteApiRepository = $pasteApiRepository;
    }

    /**
     * @throws ConnectionException
     */
    public function createPaste(PasteDTO $pasteDTO): array
    {
        return $this->pasteApiRepository->create($pasteDTO);
    }

    /**
     * @throws ConnectionException
     */
    public function getPasteByUser() : array
    {
        return $this->pasteApiRepository->getPasteByUser();
    }
}
