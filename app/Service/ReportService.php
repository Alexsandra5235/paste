<?php

namespace App\Service;

use App\Models\Report;
use App\Repository\ReportRepository;
use Illuminate\Http\Request;

class ReportService
{
    protected ReportRepository $reportRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function create(Request $request) : Report
    {
        return $this->reportRepository->create($request);
    }
    public function deleteByUrl(string $paste_url): void
    {
        $this->reportRepository->deleteByUrl($paste_url);
    }
}
