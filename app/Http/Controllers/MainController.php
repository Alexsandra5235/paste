<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Service\PasteApiService;
use App\Service\PasteService;
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
    public function dashboard() : object
    {
        $pastes = $this->pasteApiService->findAll();
        $listUrl = $this->pasteApiService->getUrlUser();
        return view('dashboard',['pastes' => $pastes, 'listUrl' => $listUrl]);
    }
}
