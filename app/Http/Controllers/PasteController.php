<?php

namespace App\Http\Controllers;

use App\DTO\PasteDTO;
use App\Service\PasteService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasteController extends Controller
{
    protected PasteService $pasteService;

    public function __construct(PasteService $pasteService){
        $this->pasteService = $pasteService;
    }

    /**
     * @throws ConnectionException
     */
    public function store(Request $request): object
    {
        if($request->check_login){
            return view('paste.login');
        }
        $request->validate([
            'paste_code' => 'required|string',
            'paste_name' => 'required|string',
            'paste_format' => 'required|string',
            'paste_private' => 'required|integer',
            'expire_date' => 'required|string',
        ]);

        $pasteDTO = new PasteDTO(
            $request->paste_code,
            $request->paste_name,
            $request->paste_format,
            $request->paste_private,
            $request->expire_date,
            $request->user()->api_user_key ?? null
        );

        $response = $this->pasteService->createPaste($pasteDTO);

        return redirect()->back();
    }
    public function index() : object
    {
        return view('paste.pasteStore');
    }
}
