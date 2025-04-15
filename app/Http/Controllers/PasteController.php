<?php

namespace App\Http\Controllers;

use App\DTO\PasteDTO;
use App\Models\InfoPastes;
use App\Models\Paste;
use App\Service\InfoPasteService;
use App\Service\PasteApiService;
use App\Service\PasteService;
use App\Service\ReportService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Pest\Laravel\withMiddleware;

class PasteController extends Controller
{
    protected PasteService $pasteService;
    protected PasteApiService $pasteApiService;
    protected InfoPasteService $infoPasteService;
    protected ReportService  $reportService;

    public function __construct(PasteService $pasteService, PasteApiService $pasteApiService,
                                InfoPasteService $infoPasteService, ReportService $reportService){
        $this->pasteService = $pasteService;
        $this->pasteApiService = $pasteApiService;
        $this->infoPasteService = $infoPasteService;
        $this->reportService = $reportService;
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
        if($pasteDTO->userKey){
            $this->pasteService->createPaste($pasteDTO, $response['paste_url']);
            $this->infoPasteService->create($response['paste_url']);
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
        $response = $this->pasteApiService->getPasteByUser(Auth::user()->api_key);

        $countReport = $this->pasteApiService->countReportUser();

        return view('paste.pastesUser', ['response' => $response, 'countUrl' => $countReport]);
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

    /**
     * @throws ConnectionException
     */
    public function delete(int $user_key, string $paste_key): void
    {
        $this->pasteApiService->delete($user_key, $paste_key);
    }

}
