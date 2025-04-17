<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Service\PasteApiService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;

class MainController extends Controller
{
    protected PasteApiService $pasteApiService;
    public function __construct(PasteApiService $pasteApiService){
        $this->pasteApiService = $pasteApiService;
    }

    /**
     * @throws ConnectionException
     */
    public function dashboard(Request $request) : object
    {
        $pastesAll = $this->pasteApiService->findAll(0);
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
