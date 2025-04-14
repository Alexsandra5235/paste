<?php

namespace App\Repository;

use App\DTO\PasteDTO;
use App\Interfaces\PasteRepositoryInterface;
use App\Models\Paste;
use http\Env\Response;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PasteRepository implements PasteRepositoryInterface
{

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function findAll() : Collection
    {
        return Paste::query()->orderBy('created_at', 'desc')->get();
    }

    public function update($id)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function create(string $url, PasteDTO $pasteDTO): Paste
    {
        return Paste::query()->create([
            'paste_name' => $pasteDTO->pasteName,
            'paste_code' => $pasteDTO->pasteCode,
            'paste_format' => $pasteDTO->pasteFormat,
            'paste_private' => $pasteDTO->pastePrivate,
            'paste_expire_date' => $pasteDTO->expireDate,
            'user_id' => Auth::id(),
            'url' => $url,
        ]);
    }
}
