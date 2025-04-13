<?php

namespace App\Service;

use App\DTO\PasteDTO;
use App\Models\Paste;
use App\Repository\PasteRepository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;

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
    public function createPasteDB(Request $request, string $url) : Paste
    {
        return $this->pasteRepository->createDB($url,$request);
    }
}
