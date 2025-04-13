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
use Illuminate\Support\Facades\Http;

class PasteRepository implements PasteRepositoryInterface
{

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function findAll()
    {
        // TODO: Implement findAll() method.
    }

    /**
     * @throws ConnectionException
     */
    public function create(PasteDTO $pasteDTO): string
    {
        $response = Http::asForm()->post(env('PASTEBIN_URL'), [
            'api_dev_key' => env('PASTEBIN_API_KEY'),
            'api_user_key' => $pasteDTO->userKey,
            'api_option' => 'paste',
            'api_paste_code' => $pasteDTO->pasteCode,
            'api_paste_name' => $pasteDTO->pasteName,
            'api_paste_private' => $pasteDTO->pastePrivate,
            'api_paste_expire_date' => $pasteDTO->expireDate,
            'api_paste_format' => $pasteDTO->pasteFormat,
        ]);


        if ($response->successful()) {
            return $response;
        } else {
            return '';
        }
    }

    public function update($id)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function createDB(string $url, PasteDTO $pasteDTO): Paste
    {
        return Paste::query()->create([
            'paste_name' => $pasteDTO->pasteName,
            'paste_code' => $pasteDTO->pasteCode,
            'paste_format' => $pasteDTO->pasteFormat,
            'paste_private' => $pasteDTO->pastePrivate,
            'paste_expire_date' => $pasteDTO->expireDate,
            'user_id' => $pasteDTO->userKey,
            'url' => $url,
        ]);
    }
}
