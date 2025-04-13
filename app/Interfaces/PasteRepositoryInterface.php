<?php

namespace App\Interfaces;

use App\DTO\PasteDTO;
use App\Models\Paste;
use http\Env\Response;
use Illuminate\Http\Request;

interface PasteRepositoryInterface
{
    public function find($id);
    public function findAll();
    public function create(PasteDTO $pasteDTO) : string;
    public function createDB(string $url, PasteDTO $pasteDTO) : Paste;
    public function update($id);
    public function delete($id);
}
