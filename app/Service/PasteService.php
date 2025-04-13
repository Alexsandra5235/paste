<?php

namespace App\Service;

use App\DTO\PasteDTO;
use App\Repository\PasteRepository;
use Illuminate\Http\Client\ConnectionException;

class PasteService
{
    protected PasteRepository $pasteRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(PasteRepository $pasteRepository)
    {
        $this->pasteRepository = $pasteRepository;
    }

    /**
     * @throws ConnectionException
     */
    public function createPaste(PasteDTO $pasteDTO): string
    {
        return $this->pasteRepository->create($pasteDTO);
    }
}
