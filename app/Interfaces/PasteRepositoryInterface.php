<?php

namespace App\Interfaces;

use App\DTO\PasteDTO;
use App\Models\Paste;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface PasteRepositoryInterface
{
    public function find($id);
    public function findAll() : Collection;
    public function create(string $url, PasteDTO $pasteDTO) : Paste;
    public function update($id);
    public function delete($id);
}
