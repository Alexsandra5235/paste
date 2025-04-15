<?php

namespace App\Repository;

use App\Interfaces\ReportRepositoryInterface;
use App\Models\Paste;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
class ReportRepository implements ReportRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Добавление в базу данных жалобы на пасту.
     * Возвращает созданную жалобу.
     * @param Request $request
     * @return Report
     */
    public function create(Request $request): Report
    {
        return Report::query()->create([
            'user_id' => Auth::id(),
            'paste_url' => $request->get('paste_url'),
            'reason' => $request->get('reason'),
        ]);
    }

    /**
     * Удаление жалобы из базы данных. Если удаление прошло успешно,
     * вернет true, в противном случае false.
     * @param string $paste_url
     * @return bool
     */
    public function deleteByUrl(string $paste_url): bool
    {
        $report = Report::query()->where('paste_url', $paste_url)->first();
        if(!$report) return false;
        $report->delete();
        return true;
    }

}
