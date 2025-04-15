<?php

namespace App\Service;

use App\Models\Report;
use App\Repository\ReportRepository;
use Illuminate\Http\Request;

/**
 *
 */
class ReportService
{
    /**
     * @var ReportRepository
     */
    protected ReportRepository $reportRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    /**
     * Создание жалобы в базе данных. Возвращает созданную жалобу.
     * @param Request $request
     * @return Report
     */
    public function create(Request $request) : Report
    {
        return $this->reportRepository->create($request);
    }

    /**
     * Удаление жалоб по-связанному url пасты. Если удаление прошло успешно,
     * вернет true, в противном случае false.
     * @param string $paste_url
     * @return bool
     */
    public function deleteByUrl(string $paste_url): bool
    {
        foreach (Report::all() as $report) {
            if ($report->paste_url == $paste_url) {
                $isDelete = $this->reportRepository->deleteByUrl($paste_url);
                if(!$isDelete) return false;
            }
        }
        return true;
    }
}
