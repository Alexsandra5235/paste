<?php

namespace App\Service;

use App\DTO\PasteDTO;
use App\Models\InfoPastes;
use App\Models\Report;
use App\Models\User;
use App\Repository\PasteApiRepository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasteApiService
{
    protected PasteApiRepository $pasteApiRepository;
    protected ReportService $reportService;
    protected InfoPasteService $infoPasteService;
    /**
     * Create a new class instance.
     */
    public function __construct(PasteApiRepository $pasteApiRepository, ReportService $reportService,
                                InfoPasteService $infoPasteService)
    {
        $this->pasteApiRepository = $pasteApiRepository;
        $this->reportService = $reportService;
        $this->infoPasteService = $infoPasteService;
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

    /**
     * @throws ConnectionException
     */
    public function delete(string $paste_url): array
    {
        $user_key = $this->infoPasteService->getUserKeyByUrl($paste_url);
        $response = $this->pasteApiRepository->delete($user_key, basename($paste_url));
        $this->reportService->deleteByUrl($paste_url);

        return $response;
    }

    /**
     * @throws ConnectionException
     * Возвращает кол-во жалоб на пасты пользователя
     * Где ключ - это название пасты, а значение это кол-во жалоб на нее
     */
    public function countReportUser() : array
    {
        $listUrl = $this->pasteApiRepository->getUrlPasteUser();

        $result = [];

        foreach ($listUrl as $title => $url) {
            $count = Report::query()->where('paste_url', $url)->count();
            if($count == 0) break;
            $result[$title] = $count;
        }

        return $result;

    }
}
