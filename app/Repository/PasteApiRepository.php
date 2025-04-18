<?php

namespace App\Repository;

use App\DTO\PasteDTO;
use App\Interfaces\PasteApiRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use function Pest\Laravel\delete;

/**
 *
 */
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
     * Получение паст пользователя через api.
     * Преобразование xml в массив с данными.
     * @param string $user_key
     * @return array<string,mixed>
     *@throws ConnectionException
     */
    public function getPasteByUser(string $user_key): array
    {
        $response = Http::asForm()->post(config('services.pastebin.pastebin_url'), [
            'api_dev_key' => config('services.pastebin.pastebin_api_key'),
            'api_user_key' => $user_key,
            'api_option' => 'list'
        ]);

        if($response->successful()) {
            if (stripos($response, '<paste>') !== false) {

                return $this->toArray($response);

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
     * Преобразование данных в формате xml в массив
     * @param Response $response
     * @return array<string,mixed>
     */
    public function toArray(Response $response) : array
    {
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
    }

    /**
     * Отправка запроса для публикации пасты и получение ответа.
     * В случае успеха ответ в виде ссылки на пасту. Если нет, вернет тело ошибки.
     * @throws ConnectionException
     * @return array<string,mixed>
     */
    public function create(PasteDTO $pasteDTO): array
    {
        $response = Http::asForm()->post(config('services.pastebin.pastebin_url'), [
            'api_dev_key' => config('services.pastebin.pastebin_api_key'),
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
     * Получение всех public паст пользователей, которые вошли в аккаунт Pastebin.
     * Если паст не найдено возвращает null.
     * @return array<string,array<string,mixed>>|null
     * @throws ConnectionException
     * @param int $paste_private
     * Если вернуть только public пасты - 0. Если все пасты - 1.
     */
    public function findAll(int $paste_private): ?array
    {
        $pastes = [];
        foreach (User::all() as $user) {

            if($user->api_key) {
                $userPaste = $this->getPasteByUser($user->api_key);
                if(!array_key_exists('status',$userPaste)){

                    if($paste_private == 0){
                        $currentPaste = [];
                        foreach ($userPaste['paste'] as $paste) {
                            if($paste['paste_private'] == 0){
                                $currentPaste[] = $paste;
                            }
                        }
                        $pastes["user.$user->id"]['paste'] = $currentPaste;
                    }
                    else {
                        $pastes["user.$user->id"] = $userPaste;
                    }



                }
            }
        }
        if($pastes == []) return null;
        return $pastes;
    }

    /**
     * Удаление пасты через api. Ответ возвращается
     * в виде массива.
     * @throws ConnectionException
     * @return array<string,string>
     */
    public function delete(string $user_key, string $paste_key): array
    {
        $response = Http::asForm()->post(config('services.pastebin.pastebin_url'), [
            'api_dev_key' => config('services.pastebin.pastebin_api_key'),
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
     * Возвращает список url адресов паст авторизированного пользователя.
     * Где ключ - это название пасты, а значение - это url адрес пасты.
     * Если паст не найдено или пользователь не авторизован в Pastebin,
     * возвращается null.
     * @return array<string,string>|null
     * @throws ConnectionException
     */
    public function getUrlPasteUser(): ?array
    {
        $user_key = Auth::user()->api_key ?? null;

        if($user_key == null){
            return null;
        }
        $response = $this->getPasteByUser($user_key);

        if(array_key_exists('status', $response)){
            return null;
        }
        $result = [];

        foreach ($response['paste'] as $paste) {
            $result[$paste['paste_title']] = $paste['paste_url'];
        }
        return $result;
    }
}
