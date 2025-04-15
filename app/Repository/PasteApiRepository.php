<?php

namespace App\Repository;

use App\DTO\PasteDTO;
use App\Interfaces\PasteApiRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use function Pest\Laravel\delete;

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
    public function getPasteByUser(string $user_key): array
    {
        $response = Http::asForm()->post(env('PASTEBIN_USER_PASTE_URL'), [
            'api_dev_key' => env('PASTEBIN_API_KEY'),
            'api_user_key' => $user_key,
            'api_option' => 'list'
        ]);

        if($response->successful()) {
            if (stripos($response, '<paste>') !== false) {

                $isArray = substr_count($response, '<paste>') === 1;

                if($isArray){
                    $response = "<pastes>{$response}{$response}</pastes>";
                } else{
                    $response = "<pastes>{$response}</pastes>";
                }

                $xmlObject = simplexml_load_string($response);
                $pastesArray = json_decode(json_encode($xmlObject), true);

                if ($isArray){
                    array_splice($pastesArray['paste'], 1);
                }

                return $pastesArray;
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

    /**
     * @throws ConnectionException
     */
    public function findAll(): array
    {
        $pastes = [];
        foreach (User::all() as $user) {

            if($user->api_key != null){
                $userPaste = $this->getPasteByUser($user->api_key);

                if(!array_key_exists('status',$userPaste)){
                    $pastes["user.$user->id"] = $userPaste;
                }
            }
        }
//        dd($pastes);
        return $pastes;
    }

    /**
     * @throws ConnectionException
     */
    public function delete(string $user_key, string $paste_key): array
    {
        $response = Http::asForm()->post(env('PASTEBIN_USER_PASTE_URL'), [
            'api_dev_key' => env('PASTEBIN_API_KEY'),
            'api_user_key' => $user_key,
            'api_paste_key' => $paste_key,
            'api_option' => 'delete',
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
                'message' => $response->body(),
            ];
        }
    }

    /**
     * @return array
     * @throws ConnectionException
     * Возвращает список url адресов паст пользователя
     * Где ключ - это название пасты, а значение - это url адрес пасты
     */
    public function getUrlPasteUser(): array
    {
        $response = $this->getPasteByUser(Auth::user()->api_key);

        if(array_key_exists('error', $response)){
            return [];
        }
        $result = [];

        foreach ($response['paste'] as $paste) {
            $result[$paste['paste_title']] = $paste['paste_url'];
        }
        return $result;
    }
}
