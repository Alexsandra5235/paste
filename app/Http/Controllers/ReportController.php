<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Service\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected ReportService $reportService;
    public function __construct(ReportService $reportService){
        $this->reportService = $reportService;
    }
    public function store(Request $request): object
    {
        $validated = $request->validate([
            'paste_url' => 'required|url',
            'reason' => 'required',
        ]);

        $this->reportService->create($request);
        return redirect()->route('dashboard')->with('success', 'Жалоба успешно отправлена');
    }
    public function index(string $url) : object
    {
        return view('paste.report',['url' => $url]);
    }
}
