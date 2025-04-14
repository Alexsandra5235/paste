<?php

namespace App\Repository;

use App\DTO\PasteDTO;
use App\Interfaces\PasteApiRepositoryInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Auth;
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


    /**
     * @throws ConnectionException
     */
    public function getPasteByUser(): array
    {
        $response = Http::asForm()->post(env('PASTEBIN_USER_PASTE_URL'), [
            'api_dev_key' => env('PASTEBIN_API_KEY'),
            'api_user_key' => Auth::user()->api_key,
            'api_option' => 'list'
        ]);


        if($response->successful()) {
            if (stripos($response, '<paste>') !== false) {
                $response = "<pastes>{$response}</pastes>";
                $xmlObject = simplexml_load_string($response);
                $pastesArray = json_decode(json_encode($xmlObject), true);
                return $pastesArray['paste'];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'No pastes found.'
                ];
            }
        } else {
            return [
                'status' => 'error',
                'message' => 'Unable to fetch pastes. Please try again later.',
                'error' => $response->body()
            ];
        }
    }

    /**
     * @throws ConnectionException
     */
    public function create(PasteDTO $pasteDTO): array
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

        if (str_contains($response->body(), 'Bad API request')) {
            return [
                'status' => 'error',
                'message' => 'Bad API request. Please try again later.',
                'error' => $response->body()
            ];
        } else {
            return [
                'status' => 'success',
                'message' => 'Successfully created paste.',
                'paste_url' => $response->body()
            ];
        }
    }
}
