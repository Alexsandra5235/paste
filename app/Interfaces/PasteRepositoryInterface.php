<?php

namespace App\Interfaces;

use App\DTO\PasteDTO;

interface PasteRepositoryInterface
{
    public function find($id);
    public function findAll();
    public function create(PasteDTO $pasteDTO) : string;
    public function update($id);
    public function delete($id);
}
