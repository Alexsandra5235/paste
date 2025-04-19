<?php

namespace App\Service;

use App\DTO\PasteDTO;
use App\Models\Paste;
use App\Models\Report;
use App\Models\User;
use App\Repository\PasteApiRepository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class PasteApiService
{
    /**
     * Публикация пасты через api. В ответ приходит массив
     * с ошибкой запроса или успехом, результат находится в поле "status".
     * @throws ConnectionException
     */
    public function createPaste(PasteDTO $pasteDTO): array
    {
        return app(PasteApiRepository::class)->create($pasteDTO);
    }

    /**
     * Если ключ существует, то возвращается api ответ с пастами пользователя.
     * Если ключ не указан, то возвращается api ответ по ключу аутентифицированному пользователя.
     * Если у пользователя нет ключа, возвращается сообщение об этом.
     * @param string|null $user_key
     * @return array
     * @throws ConnectionException
     */
    public function getPastesByUser(?string $user_key) : array
    {
        if($user_key){
            $key = $user_key;
        } else if (Auth::user()->api_key) {
            $key = Auth::user()->api_key;
        } else {
            Log::info('PasteApiService: Попытка получения паст: пользователь не авторизирован в pastebin.');
            return [
                'pastes' => [],
                'error' => 'Ваш аккаунт не авторизирован в системе Pastebin.'
            ];
        }

        $response = app(PasteApiRepository::class)->getPasteByUser($key);

        if (isset($response['status']) && $response['status'] === 'error') {
            Log::error('PasteApiService: Ошибка запроса получения паст ' . $response['error']);
            return [
                'pastes' => [],
                'error' => $response['message']
            ];
        }
        return [
            'pastes' => $response,
            'error' => ''
        ];

    }

    /**
     * Возвращает public пасты всех авторизированных в аккаунте Pastebin пользователей системы.
     * Вернется null, если паст не найдено.
     * @param int $paste_private
     * Если вернуть только public пасты - 0. Если все пасты - 1.
     * @return array|null
     * @throws ConnectionException
     */
    public function findAll(int $paste_private) : ?array
    {
        return app(PasteApiRepository::class)->findAll($paste_private);
    }

    /**
     * Получение кэшированных данных паст.
     * @return array|null
     * Возвращает отсортированные по дате создания
     * (от меньшей к большей) пасты. Если пасты не найдены в кэше,
     * возвращает null.
     *
     */
    public function getCachePaste(): ?array
    {
        $latestPastes = Cache::get('pastes');
        $pastes = [];
        if($latestPastes) {
            foreach ($latestPastes as $user) {
                $pastes = array_merge($pastes, $user['paste']);
            }
        } else {
            return null;
        }
        usort($pastes, function($a, $b) {
            return $b['paste_date'] <=> $a['paste_date'];
        });
        return $pastes;
    }

    /**
     * Возвращает все пасты в виде коллекции Paste.
     * Вернет пустую коллекцию, если паст не было найдено.
     * @return Collection
     * @throws ConnectionException
     */
    public function findAllRenderPastes(): Collection
    {
        $pastes = $this->findAll(1);
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
     * Получение ключа пользователя пасты.
     * Удаление пасты через api.
     * Удаление связанных жалоб.
     * @param string $paste_url
     * @return array
     *@throws ConnectionException
     */
    public function delete(string $paste_url): array
    {
        $user_key = $this->getUserKeyByUrl($paste_url);
        if (!$user_key) {
            Log::error('PasteApiService: Ошибка удаления пасты: пользователь не был найден');
            return [
                'status' => 'error',
                'message' => 'Невозможно получить ключ пользователя для удаления пасты',
            ];
        }
        $response = app(PasteApiRepository::class)->delete($user_key, basename($paste_url));
        app(ReportService::class)->deleteByUrl($paste_url);

        return $response;
    }

    /**
     * Возвращает кол-во жалоб на пасты авторизированного в системе
     * Pastebin пользователя. Где ключ - это название пасты,
     * а значение это кол-во жалоб на нее. Если жалоб не найдено
     * или пользователь не авторизован, то вернет null.
     * @return array|null
     *@throws ConnectionException
     */
    public function countReportUser() : ?array
    {
        $listUrl = app(PasteApiRepository::class)->getUrlPasteUser();
        if(!$listUrl) return null;

        $result = [];

        foreach ($listUrl as $title => $url) {
            $count = Report::query()->where('paste_url', $url)->count();
            if($count == 0) continue;
            $result[$title] = $count;
        }
        if($result == []) return null;
        return $result;
    }

    /**
     * @param string $url
     * @return int
     * Возвращает кол-во жалоб на пасту.
     */
    public function getCountReportByUrl(string $url) : int
    {
        return Report::query()->where('paste_url', $url)->count();
    }

    /**
     * @return array|null
     * Возвращает список url адресов паст авторизированного пользователя.
     * Где ключ - это название пасты, а значение - это url адрес пасты.
     * Если паст не найдено, вернет null.
     * @throws ConnectionException
     */
    public function getUrlUser(): ?array
    {
        $listUrl = app(PasteApiRepository::class)->getUrlPasteUser();
        if(!$listUrl) return null;
        return $listUrl;
    }

    /**
     * @return array|null
     * Возвращает массив с ключами всех пользователей и их пастами.
     * Если пасты не найдены возвращает null.
     * Ключ - это ключ пользователя, а значение - это массив паст пользователя.
     * @throws ConnectionException
     */
    public function getUserKeyOrPaste() : ?array
    {
        $result = [];
        foreach (User::all() as $user) {
            $user_key = $user->api_key ?? null;
            if ($user_key) {
                $pastes = app(PasteApiRepository::class)->getPasteByUser($user_key);
                $result[$user_key] = $pastes;
            }
        }
        if($result == []) return null;
        return $result;
    }

    /**
     * @param string $url
     * @return string|null
     * Возвращает ключ пользователя, связанного с url пасты.
     * Если не находит, возвращает null.
     * @throws ConnectionException
     */
    public function getUserKeyByUrl(string $url) : ?string
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
