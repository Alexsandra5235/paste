<?php

namespace App\Http\Controllers;

use App\DTO\PasteDTO;

use App\Jobs\FetchPastebinPastes;
use App\Models\Paste;
use App\Service\PasteApiService;
use App\Service\ReportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use function Pest\Laravel\withMiddleware;

/**
 *
 */
class PasteController extends Controller
{
    /**
     * Отправка запроса api для публикации записи в Pastebin.com.
     * @param Request $request
     * @return string
     * В случае успеха возвращает ссылку на пасту,
     * в противном случае возвращает сообщение об ошибке.
     * @throws ConnectionException
     */
    public function store(Request $request): string
    {
        $request->validate([
            'paste_code' => 'required|string',
            'paste_name' => 'required|string',
            'paste_format' => 'required|string',
            'paste_private' => 'required|integer',
            'expire_date' => 'required|string',
        ]);

        $pasteDTO = new PasteDTO(
            $request->input('paste_code'),
            $request->input('paste_name'),
            $request->input('paste_format'),
            $request->input('paste_private'),
            $request->input('expire_date')
        );

        if(!$request->input('check_private')){
            $pasteDTO->userKey = $request->user()->api_key;
        }

        $response = app(PasteApiService::class)->createPaste($pasteDTO);

        if (isset($response['status']) && $response['status'] === 'error') {
            return redirect()->back()->with([
                'error' => implode(', ', $response),
            ]);
        }

        return redirect()->back()->with([
            'success' => $response['paste_url'],
        ]);
    }

    /**
     * Обновляет кэш паст и возвращает страницу публикации.
     * @return View
     * Возвращает страницу публикации пасты.
     */
    public function index() : View
    {
        dispatch_sync(new FetchPastebinPastes('pastes'));
        return view('paste.pasteStore');
    }

    /**
     * @throws ConnectionException
     * @return View
     * Возвращает страницу пользователя с его пастами и жалобами на
     * них, если таковые имеются.
     */
    public function getPasteByUser() : View
    {
        $response = app(PasteApiService::class)->getPastesByUser(null);
        $countReport = app(PasteApiService::class)->countReportUser();

        return view('paste.pastesUser', ['response' => $response, 'countUrl' => $countReport]);
    }

    /**
     * Удаление пасты через api.
     * @param string $paste_url
     * Ссылка на пасту, которую необходимо удалить
     * @return void
     * @throws ConnectionException
     */
    public function delete(string $paste_url): void
    {
        app(PasteApiService::class)->delete($paste_url);
    }

    /**
     * Отправляет пасты из кэша на главную страницу для их отображения.
     * @return JsonResponse
     * Возвращает пасты из кэша.
     */
    public function apiPastes(): JsonResponse
    {
        try {
            $pastes = app(PasteApiService::class)->getCachePaste();

            return response()->json([
                'success' => true,
                'data' => $pastes,
            ]);
        } catch (\Throwable $e) {
            Log::error('Ошибка загрузки паст из кеша: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ошибка загрузки паст.',
            ], 500);
        }
    }


    public function getUrlUser() : JsonResponse
    {
        try {
            $pastes = app(PasteApiService::class)->getUrlUser();

            return response()->json([
                'success' => true,
                'data' => $pastes,
            ]);
        } catch (\Throwable $e) {
            Log::error('Ошибка загрузке url: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ошибка загрузке url.',
            ], 500);
        }
    }

}
