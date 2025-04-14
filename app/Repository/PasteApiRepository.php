<?php

namespace App\Repository;

use App\DTO\PasteDTO;
use App\Interfaces\PasteApiRepositoryInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class PasteApiRepository implements PasteApiRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }



    public function getPasteByUser($userId): array
    {
        // TODO: Implement getPasteByUser() method.
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
}
