<?php

namespace App\Interfaces;

use App\Models\InfoPastes;
use App\Models\Paste;

interface InfoPasteRepositoryInterface
{
    public function create(string $url): InfoPastes;
    public function getUserKeyByUrl(string $url) : string;
}
