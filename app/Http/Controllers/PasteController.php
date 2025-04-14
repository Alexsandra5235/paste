<?php

namespace App\Http\Controllers;

use App\DTO\PasteDTO;
use App\Models\Paste;
use App\Service\PasteApiService;
use App\Service\PasteService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function Pest\Laravel\withMiddleware;

class PasteController extends Controller
{
    protected PasteService $pasteService;
    protected PasteApiService $pasteApiService;

    public function __construct(PasteService $pasteService, PasteApiService $pasteApiService){
        $this->pasteService = $pasteService;
        $this->pasteApiService = $pasteApiService;
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

        if(!$request->check_private){
            $pasteDTO->userKey = $request->user()->api_key;
        }

        $response = $this->pasteApiService->createPaste($pasteDTO);

        if (isset($response['status']) && $response['status'] === 'error') {
            return redirect()->back()->with([
                'error' => implode(', ', $response),
            ]);
        }
        return redirect()->back()->with([
            'success' => $response['paste_url'],
        ]);
    }
    public function index() : object
    {
        return view('paste.pasteStore');
    }

    /**
     * @throws ConnectionException
     */
    public function getPasteByUser() : object
    {
        $response = $this->pasteApiService->getPasteByUser();

        if (isset($response['status']) && $response['status'] === 'error') {
            return view('paste.pastesUser', [
                'pastes' => [],
                'error' => $response['message']
            ]);
        }
        return view('paste.pastesUser', [
            'pastes' => $response,
            'error' => ''
        ]);
    }

    public function test(Request $request) : object
    {

        $pasteDTO = new PasteDTO(
            $request->paste_code,
            $request->paste_name,
            $request->paste_format,
            $request->paste_private,
            $request->expire_date
        );

        $this->pasteService->createPaste($pasteDTO, 'https://example.com');

        return redirect()->back();
    }

}
