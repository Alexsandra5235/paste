<?php

namespace App\Http\Controllers;

use App\DTO\PasteDTO;

use App\Models\Paste;
use App\Service\PasteApiService;
use App\Service\ReportService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Pest\Laravel\withMiddleware;

class PasteController extends Controller
{
    protected PasteApiService $pasteApiService;
    protected ReportService  $reportService;

    public function __construct(PasteApiService $pasteApiService, ReportService $reportService){
        $this->pasteApiService = $pasteApiService;
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
        $response = $this->pasteApiService->getPastesByUser(null);
        $countReport = $this->pasteApiService->countReportUser();

        return view('paste.pastesUser', ['response' => $response, 'countUrl' => $countReport]);
    }

    /**
     * @throws ConnectionException
     */
    public function delete(string $paste_url): void
    {
        $this->pasteApiService->delete($paste_url);
    }

}
