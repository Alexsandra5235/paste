<?php

namespace App\Interfaces;

use App\Models\Report;
use Illuminate\Http\Request;

interface ReportRepositoryInterface
{
    public function create(Request $request) : Report;
    public function deleteByUrl(string $paste_url): void;
    public function getReportByUser(array $pastes, array $countedUrls): array;
}
