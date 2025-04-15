<?php

namespace App\Service;

use App\Models\InfoPastes;
use App\Repository\InfoPasteRepository;

class InfoPasteService
{
    protected InfoPasteRepository $infoPasteRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(InfoPasteRepository $infoPasteRepository)
    {
        $this->infoPasteRepository = $infoPasteRepository;
    }
    public function create(string $url): InfoPastes
    {
        return $this->infoPasteRepository->create($url);
    }
    public function getUserKeyByUrl(string $url) : string | null
    {
        return $this->infoPasteRepository->getUserKeyByUrl($url);
    }

}
