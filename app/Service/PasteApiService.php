<?php

namespace App\Service;

use App\DTO\PasteDTO;
use App\Repository\PasteApiRepository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasteApiService
{
    protected PasteApiRepository $pasteApiRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(PasteApiRepository $pasteApiRepository)
    {
        $this->pasteApiRepository = $pasteApiRepository;
    }

    /**
     * @throws ConnectionException
     */
    public function createPaste(PasteDTO $pasteDTO): array
    {
        return $this->pasteApiRepository->create($pasteDTO);
    }

    /**
     * @throws ConnectionException
     */
    public function getPasteByUser(string $user_key) : array
    {
        if(Auth::user()->api_key){
            $response = $this->pasteApiRepository->getPasteByUser($user_key);

            if (isset($response['status']) && $response['status'] === 'error') {
                return [
                    'pastes' => [],
                    'error' => $response['message']
                ];
            }
            return [
                'pastes' => $response,
                'error' => ''
            ];
        } else {
            return [
                'pastes' => [],
                'error' => 'Ваш аккаунт не авторизирован в системе Pastebin.'
            ];
        }
    }

    /**
     * @throws ConnectionException
     */
    public function findAll() : array
    {
        return $this->pasteApiRepository->findAll();
    }
}
