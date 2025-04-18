<?php

namespace App\Http\Controllers;

use App\Jobs\FetchPastebinPastes;
use App\Models\Report;
use App\Service\PasteApiService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 *
 */
class MainController extends Controller
{
    /**
     * Реализует отображение данных на главной странице. Ставит задачу на кэширование
     * данных в очередь и подготавливает данные для пагинации на странице.
     * @throws ConnectionException
     * @return View
     * Возвращает главную страницу сайта.
     * @param Request $request
     */
    public function dashboard(Request $request) : View
    {

        $cacheKey = 'pastes';
        $pastesAll = app(PasteApiService::class)->getCachePaste();

        FetchPastebinPastes::dispatch($cacheKey);

        $listUrl = app(PasteApiService::class)->getUrlUser();

        if (!$pastesAll) {
            return view('dashboard');
        }

        $perPage = 9;
        $currentPage = $request->input('page', 1);
        $totalItems = count($pastesAll);
        $totalPages = ceil($totalItems / $perPage);

        $offset = ($currentPage - 1) * $perPage;
        $currentItems = array_slice($pastesAll, $offset, $perPage);

        return view('dashboard',['listUrl' => $listUrl, 'currentItems' => $currentItems,
            'currentPage' => $currentPage, 'totalPages' => $totalPages]);
    }
}
