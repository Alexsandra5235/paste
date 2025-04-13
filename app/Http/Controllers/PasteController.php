<?php

namespace App\Http\Controllers;

use App\DTO\PasteDTO;
use App\Models\Paste;
use App\Service\PasteService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function Pest\Laravel\withMiddleware;

class PasteController extends Controller
{
    protected PasteService $pasteService;

    public function __construct(PasteService $pasteService){
        $this->pasteService = $pasteService;
    }

    /**
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
            $request->paste_code,
            $request->paste_name,
            $request->paste_format,
            $request->paste_private,
            $request->expire_date
        );

        $response = $this->pasteService->createPaste($pasteDTO, $request);

        if(empty($response)){
            return redirect()->back()->with('errors','Ошибка запроса.');
        }
        else {
            $this->pasteService->createPasteDB($request,$response);
            return redirect()->back()->with('success','Паста успешно загружена.')->with('paste',$response);
        }
    }
    public function index() : object
    {
        return view('paste.pasteStore');
    }
}
