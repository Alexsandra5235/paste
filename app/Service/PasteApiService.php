<?php

namespace App\Service;

use App\DTO\PasteDTO;
use App\Models\InfoPastes;
use App\Models\Paste;
use App\Models\Report;
use App\Models\User;
use App\Repository\PasteApiRepository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
class PasteApiService
{
    /**
     * @var PasteApiRepository
     */
    protected PasteApiRepository $pasteApiRepository;
    /**
     * @var ReportService
     */
    protected ReportService $reportService;
    /**
     * @var InfoPasteService
     */
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
     * @param string $user_key
     * @return array
     * Возвращает пасты, связанные с пользователем
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
     * Возвращает пасты всех авторизированных в аккаунте Pastebin пользователей системы
     */
    public function findAll() : array
    {
        return $this->pasteApiRepository->findAll();
    }

    /**
     * @throws ConnectionException
     * Возвращает все пасты в виде коллекции Paste
     */
    public function findAllRenderPastes(): Collection
    {
        $pastes = $this->findAll();
        $allPastes = [];
        foreach ($pastes as $user) {
            if (isset($user['paste'])) {
                foreach ($user['paste'] as $paste) {
                    $allPastes[] = $paste;
                }
            }
        }
        return Paste::query()->hydrate($allPastes);
    }

    /**
     * @throws ConnectionException
     * @param string $paste_url
     * @return array
     * Получение ключа пользователя пасты
     * Удаление пасты через api
     * Удаление связанных жалоб
     */
    public function delete(string $paste_url): array
    {
//        $user_key = $this->infoPasteService->getUserKeyByUrl($paste_url);
        $user_key = $this->getUserKeyByUrl($paste_url);
        if ($user_key == null) {
            return [
                'status' => 'error',
                'message' => 'Невозможно получить ключ пользователя для удаления пасты',
            ];
        }
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

    /**
     * @param string $url
     * @return int
     * Возвращает кол-во жалоб на пасту
     */
    public function getCountReportByUrl(string $url) : int
    {
        return Report::query()->where('paste_url', $url)->count();
    }

    /**
     * @throws ConnectionException
     * @return array
     * Возвращает список url адресов паст авторизированного пользователя
     * Где ключ - это название пасты, а значение - это url адрес пасты
     */
    public function getUrlUser(): array
    {
        return $this->pasteApiRepository->getUrlPasteUser();
    }

    /**
     * @return array
     * Возвращает массив с ключами пользователя и его пастами
     * Ключ - это ключ пользователя, а значение - это массив паст пользователя
     * @throws ConnectionException
     */
    public function getUserKeyOrPaste() : array
    {
        $result = [];
        foreach (User::all() as $user) {
            $pastes = $this->pasteApiRepository->getPasteByUser($user->api_key);
            $result[$user->api_key] = $pastes;
        }
        return $result;
    }

    /**
     * @param string $url
     * @return string | null
     * Возвращает ключ пользователя, связанного с url пасты
     * Если не находит, возвращает null
     * @throws ConnectionException
     */
    public function getUserKeyByUrl(string $url) : string | null
    {
        $userKey = $this->getUserKeyOrPaste();
        foreach ($userKey as $userId => $userPastes) {
            foreach ($userPastes['paste'] as $paste) {
                if ($paste['paste_url'] === $url) {
                    return $userId;
                }
            }
        }
        return null;
    }
}
