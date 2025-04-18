<?php

namespace App\Http\Controllers;

use App\Jobs\FetchPastebinPastes;
use App\Models\Report;
use App\Service\PasteApiService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MainController extends Controller
{
    protected PasteApiService $pasteApiService;
    protected FetchPastebinPastes $fetchPastebinPastes;
    public function __construct(PasteApiService $pasteApiService, FetchPastebinPastes $fetchPastebinPastes){
        $this->pasteApiService = $pasteApiService;
        $this->fetchPastebinPastes = $fetchPastebinPastes;
    }

    /**
     * @throws ConnectionException
     */
    public function dashboard(Request $request) : object
    {

        $cacheKey = 'pastes';
        $pastesAll = Cache::get($cacheKey);

        FetchPastebinPastes::dispatch($cacheKey);

        $listUrl = $this->pasteApiService->getUrlUser();

        if (!$pastesAll) {
            return view('dashboard');
        }

        $pastes = [];
        foreach ($pastesAll as $user) {
            $pastes = array_merge($pastes, $user['paste']);
        }

        $perPage = 9;
        $currentPage = $request->input('page', 1);
        $totalItems = count($pastes);
        $totalPages = ceil($totalItems / $perPage);

        $offset = ($currentPage - 1) * $perPage;
        $currentItems = array_slice($pastes, $offset, $perPage);

        return view('dashboard',['listUrl' => $listUrl, 'currentItems' => $currentItems,
            'currentPage' => $currentPage, 'totalPages' => $totalPages]);
    }
}
