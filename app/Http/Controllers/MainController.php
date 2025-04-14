<?php

namespace App\Http\Controllers;

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
//        dd($pastes);
        return view('dashboard',['pastes' => $pastes]);
    }
}
